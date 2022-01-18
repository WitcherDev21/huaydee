<?php

namespace frontend\controllers;

use common\libs\Constants;
use common\models\Credit;
use common\models\CreditTransectionSearch;
use common\models\PostCreditTransection;
use common\models\PostCreditTransectionSearch;
use common\models\PostCreditTransectionTopup;
use common\models\Queue;
use common\models\SmsMessage;
use common\models\User;
use common\models\UserHasBank;
use common\models\UserHasBankSearch;
use Yii;
use yii\base\Model;
use yii\bootstrap\ActiveForm;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\ServerErrorHttpException;

/**
 * PostCreditTransectionController implements the CRUD actions for PostCreditTransection model.
 */
class PostCreditTransectionController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'send-deposit' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionAll()
    {
        $userId = Yii::$app->user->id;
        $postCreditTransections = PostCreditTransection::find()
            ->where(['create_by' => $userId])
            ->andWhere('MONTH(create_at) = MONTH(CURRENT_DATE())')
            ->andWhere('YEAR(create_at) = YEAR(CURRENT_DATE())')
            ->orderBy('create_at DESC')
            ->all();

        return $this->render('all', [
            'postCreditTransections' => $postCreditTransections,
        ]);
    }

    /**
     * Lists all PostCreditTransection models.
     * @return mixed
     */
    public function actionDeposit($message = null)
    {
        $userId = Yii::$app->user->id;
        $postCreditTransections = PostCreditTransection::find()
            ->where([
                'create_by' => $userId,
                'action_id' => [Constants::action_credit_top_up, Constants::action_credit_top_up_admin],
            ])
            ->andWhere('MONTH(create_at) = MONTH(CURRENT_DATE())')
            ->andWhere('YEAR(create_at) = YEAR(CURRENT_DATE())')
            ->orderBy('create_at DESC')
            ->all();

        return $this->render('deposit', [
            'postCreditTransections' => $postCreditTransections,
            'message' => $message,
        ]);
    }

    public function actionWithdraw($message = null)
    {
        $userId = Yii::$app->user->id;
        $postCreditTransections = PostCreditTransection::find()
            ->where([
                'create_by' => $userId,
                'action_id' => [Constants::action_credit_withdraw, Constants::action_credit_withdraw_admin, Constants::action_commission_withdraw_direct],
            ])
            ->andWhere('MONTH(create_at) = MONTH(CURRENT_DATE())')
            ->andWhere('YEAR(create_at) = YEAR(CURRENT_DATE())')
            ->orderBy('create_at DESC')
            ->all();

        return $this->render('withdraw', [
            'postCreditTransections' => $postCreditTransections,
            'message' => $message,
        ]);
    }

    /**
     * Creates a new PostCreditTransection model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateTopup()
    {
        $userId = \Yii::$app->user->id;
        $postCreditTransectionCount = PostCreditTransection::find()->where([
            'status' => 6,
            'action_id' => [Constants::action_credit_top_up, Constants::action_commission_withdraw],
            'create_by' => $userId
        ])->count();
        if ($postCreditTransectionCount) {
            return $this->redirect(['deposit', 'message' => 'ท่านกำลังมีรายการรออนุมัติอยู่กรุณารอสักครู่แล้วทำรายการใหม่อีกครั้ง']);
        }
        $model = new PostCreditTransectionTopup();

        $userHasBanks = UserHasBankSearch::getBankAccountSystem();
        $userBanks = UserHasBankSearch::getBankAccountUser($userId);
        if (!$userBanks) {
            return $this->redirect(['/setting/bank-status']);
        }

        return $this->render('create_topup', [
            'model' => $model,
            'userHasBanks' => $userHasBanks,
            'userBanks' => $userBanks,
        ]);
    }

    public function actionSendDeposit()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $userId = Yii::$app->user->id;
        $params = Yii::$app->request->post();
        $checkQueueProcess = Queue::find()->where([
            'userId' => $userId,
            'status' => Constants::status_active
        ])->count();
        if ($checkQueueProcess) {
            return [
                'result' => 'show_message',
                'message' => 'กรุณารอสักครู่ แล้วกดส่งโพยอีกครั้ง',
            ];
        }
        if ($params) {
            $money = str_replace( ',', '', $params['money']);
            $decimal = str_replace( ',', '', $params['decimal']);
            $amount = number_format($money.'.'.$decimal, 2);
            $date = \DateTime::createFromFormat('d/m/Y', $params['date']);
            $post_requir_time = $date->format('Y-m-d') ." ". $params['time']. ":00";
            $bankSystem = explode('-', $params['svbank']);
            $userHasBank = UserHasBank::find()->where(['id' => $bankSystem[0]])->one();
            $transaction = Yii::$app->db->beginTransaction();
            try {

                $smsMessage = SmsMessage::find()->where([
                    'action' => 'ฝาก/โอนเงินเข้า',
                    'amount' => $amount,
                    'date' => $post_requir_time,
                    'bank' => $userHasBank->bank->title,
                    'is_used' => Constants::status_inactive,
                ])->count();
                $status = (int)$smsMessage === 1 ? Constants::status_approve : Constants::status_waitting;
                $model = new PostCreditTransection();
                $model->poster_id = $userId;
                $model->action_id = Constants::action_credit_top_up;
                $model->user_has_bank_id = $bankSystem[0];
                $model->user_has_bank_version = UserHasBankSearch::getCurrentVersion($bankSystem[0]);
                $model->user_has_bank_id_user = $params['mybank'];
                $model->status = $status;
                $model->amount = (float)(str_replace(',', '', $amount));
                $model->remark = $params['note'];
                $model->create_at = date('Y-m-d H:i:s');
                $model->create_by = $userId;
                $model->channel = $params['channel'];
                $model->post_requir_time = $post_requir_time;
                $model->is_auto = $status === Constants::status_approve ? 1 : 0;
                if (!$model->save()) {
                    return ['result' => 'can not save post credit transection'];
                }
                if ((int)$status === (int)Constants::status_approve) {
                    $CreditMasterBalance = CreditTransectionSearch::checkCreditMasterBalance($model->action_id, Constants::reason_credit_top_up, $model->amount);
                    if (!empty($CreditMasterBalance) && isset($CreditMasterBalance['amount']) && $CreditMasterBalance['amount'] < 0) {
                        return ['result' => 'not allow method post only'];
                    }
                    $result = Credit::creditWalk($model->action_id, $model->poster_id, Constants::user_system_id, Constants::reason_credit_top_up, $model->amount);
                    if (!$result) {
                        throw new ServerErrorHttpException('Can not save credit');
                    }
                    $smsMessage = SmsMessage::find()->where([
                        'action' => 'ฝาก/โอนเงินเข้า',
                        'amount' => $amount,
                        'date' => $post_requir_time,
                        'bank' => $userHasBank->bank->title
                    ])->one();
                    $smsMessage->is_used = Constants::status_active;
                    $smsMessage->save();
                }
                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            }
            return ['result' => 'success', 'id' => $model->id];
        }
        return ['result' => 'not allow method post only'];
    }

    public function actionSuccess($id)
    {
        $userId = Yii::$app->user->id;
        $postCreditTransection = PostCreditTransection::find()->where(['create_by' => $userId, 'id' => $id])->one();
        if (!$postCreditTransection) {
            throw new NotFoundHttpException();
        }
        return $this->render('success', [
            'postCreditTransection' => $postCreditTransection,
        ]);
    }

    public function actionCreateWithdraw()
    {
        $userId = Yii::$app->user->id;
        $user = User::find()->where(['id' => $userId])->one();
        $userBanks = UserHasBankSearch::getBankAccountUser($userId);
        if (!$userBanks) {
            return $this->redirect(['/setting/bank-status']);
        }
        $postCreditTransection =  CreditTransectionSearch::getCanWithdraw($userId, 1, 0);
        if (!$postCreditTransection['result']) {
            return $this->redirect(['withdraw', 'message' => $postCreditTransection['reason']]);
        }
        $checkQueueProcess = Queue::find()->where([
            'userId' => $userId,
            'status' => Constants::status_active
        ])->count();
        if ($checkQueueProcess) {
            return $this->redirect(['withdraw', 'message' => 'กรุณารอสักครู่ แล้วกดส่งโพยอีกครั้ง']);
        }
        $params = Yii::$app->request->post();
        if ($params) {
            $userHasBank = UserHasBank::findOne(['user_id' => $userId, 'id' => $params['bank']]);
            if (!$userHasBank) {
                return $this->render('create_withdraw', [
                    'user' => $user,
                ]);
            }
            $model = new PostCreditTransection();
            $model->poster_id = $userId;
            $model->action_id = Constants::action_credit_withdraw;
            $model->user_has_bank_id = $userHasBank->id;
            $model->user_has_bank_version = UserHasBankSearch::getCurrentVersion($userHasBank->id);
            $model->status = Constants::status_waitting;
            $model->user_has_bank_id_user = $userHasBank->id;
            $model->amount = floatval(str_replace(',', '', $params['wmoney']));
            $model->create_at = date('Y-m-d H:i:s');
            $model->remark = $params['note'];
            $model->create_by = $userId;
            $model->post_requir_time = date('Y-m-d H:i:s');
            if (!$model->save()) {
                return $this->render('create_withdraw', [
                    'user' => $user,
                ]);
            }
            return $this->render('withdraw_success');

        }
        return $this->render('create_withdraw', [
            'user' => $user,
            'userBanks' => $userBanks,
        ]);
    }

    /**
     * Displays a single PostCreditTransection model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function performAjaxValidation(Model $model)
    {
        if (\Yii::$app->request->isAjax && $model->load(\Yii::$app->request->post())) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            \Yii::$app->response->data = ActiveForm::validate($model);
            \Yii::$app->response->send();
            \Yii::$app->end();
        }
    }

    /**
     * Updates an existing PostCreditTransection model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing PostCreditTransection model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the PostCreditTransection model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PostCreditTransection the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PostCreditTransection::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
