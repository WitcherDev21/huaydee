<?php

namespace backend\controllers;

use common\models\Commission;
use common\models\Queue;
use Yii;
use common\models\PostCreditTransection;
use common\models\PostCreditTransectionSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\libs\Constants;
use common\models\CreditTransection;
use common\models\Credit;
use common\models\AuthRoles;
use common\models\UserSearch;
use common\models\CreditTransectionSearch;
use yii\web\ServerErrorHttpException;

/**
 * PostCreditTransectionController implements the CRUD actions for PostCreditTransection model.
 */
class PostCreditMemberController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        $identity = \Yii::$app->user->getIdentity();
        if (empty($identity)) {
            $this->layout = false;
            $this->redirect(Yii::$app->urlManager->createUrl(['user/login']));
        } else {
            $modelAuthRoles = new AuthRoles();
            $arrRoles = $modelAuthRoles->_getRoles($identity->auth_roles_id);
            if (!in_array('post-credit-member', $arrRoles)) {
                $this->layout = false;
                $this->redirect(Yii::$app->urlManager->createUrl(['user/logout']));
            }
        }
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
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

    /**
     * Lists all PostCreditTransection models.
     * @return mixed
     */
    public function actionIndex()
    {
        $active = '';
        if (!empty(Yii::$app->getSession()->getFlash('type'))) {
            $active = Yii::$app->getSession()->getFlash('type');
        }

        $param = Yii::$app->request->get();
        $param1 = '';
        $param2 = '';
        if (isset($param['type'])) {
            if ($param['type'] == 'Current') {
                $param1 = $param;
            } else {
                $param2 = $param;
            }
            $active = $param['type'];
        }
        $searchModelCurrent = new PostCreditTransectionSearch();
        $searchModelCurrent->create_at = date('Y-m-d');
        $searchModelCurrent->auth_roles_id = Constants::auth_roles_member;

        $dataProviderCurrent = $searchModelCurrent->search($param1);

        $searchModelHistory = new PostCreditTransectionSearch();
        $searchModelHistory->auth_roles_id = Constants::auth_roles_member;
        $dataProviderHistory = $searchModelHistory->search($param2);


        return $this->render('index', [
            'searchModelCurrent' => $searchModelCurrent,
            'dataProviderCurrent' => $dataProviderCurrent,
            'searchModelHistory' => $searchModelHistory,
            'dataProviderHistory' => $dataProviderHistory,
            'active' => $active,
        ]);
    }

    public function actionCredit()
    {
        $params = \Yii::$app->request->get();
        if (!empty($params) && !empty($params['id'])) {
            $User = UserSearch::find()
                ->where(['id' => $params['id']])
                ->one();
            if (!empty($User)) {
                $searchModel = new CreditTransectionSearch();
                $searchModel->reciver_id = $User->id;
                $dataProvider = $searchModel->search('');
                return $this->render('credit', [
                    'dataUser' => $User,
                    'dataProvider' => $dataProvider
                ]);
            }
        }
        return $this->redirect(['index']);
    }

    public function actionCreateTopup()
    {
        $user_id = \Yii::$app->user->identity->id;
        $model = new PostCreditTransection();
        $layout = \Yii::$app->request->get('layout');
        if (!empty($layout)) {
            $this->layout = $layout;
        }

        $params = Yii::$app->request->post();
        if ($params) {
            $model->load($params);
            $dataForm = $params['PostCreditTransection'];
            $checkQueueProcess = Queue::find()->count();
            if ($checkQueueProcess) {
                $canWithdraw = ['reason' => 'ระบบกำลังประมวลผลอยู่ กรุณาลองใหม่อีกครั้ง ภายใน 2 นาที'];
                return $this->render('/info/index', ['canWithdraw' => $canWithdraw]);
            }
            $model->poster_id = $user_id;
            $model->action_id = Constants::action_credit_top_up;
            $model->user_has_bank_id = $dataForm['user_has_bank_id'];
            $model->user_has_bank_version = UserHasBankSearch::getCurrentVersion($dataForm['user_has_bank_id']);
            $model->status = Constants::status_processing;
            $model->amount = $dataForm['amount'];
            $model->create_at = date('Y-m-d H:i:s', time());
            $model->create_by = $user_id;

            if ($model->save()) {
                Yii::$app->session->setFlash('success', "Successfully");
                return $this->redirect(['post-credit-member/index']);
            } else {
                Yii::$app->session->setFlash('warning', "Warning");
            }
        }

        return $this->render('create_topup', [
            'model' => $model, updatestatus
        ]);
    }

    public function actionCreateWithdraw()
    {
        $user_id = \Yii::$app->user->identity->id;
        $model = new PostCreditTransection();
        $layout = \Yii::$app->request->get('layout');
        if (!empty($layout)) {
            $this->layout = $layout;
        }

        $params = Yii::$app->request->post();
        if ($params) {
            $model->load($params);
            $dataForm = $params['PostCreditTransection'];
            $checkQueueProcess = Queue::find()->count();
            if ($checkQueueProcess) {
                $canWithdraw = ['reason' => 'ระบบกำลังประมวลผลอยู่ กรุณาลองใหม่อีกครั้ง ภายใน 2 นาที'];
                return $this->render('/info/index', ['canWithdraw' => $canWithdraw]);
            }
            $model->poster_id = $user_id;
            $model->action_id = Constants::action_credit_withdraw;
            $model->user_has_bank_id = $dataForm['user_has_bank_id'];
            $model->user_has_bank_version = UserHasBankSearch::getCurrentVersion($dataForm['user_has_bank_id']);
            $model->status = Constants::status_processing;
            $model->amount = $dataForm['amount'];
            $model->create_at = date('Y-m-d H:i:s', time());
            $model->create_by = $user_id;

            if ($model->save()) {
                Yii::$app->session->setFlash('success', "Successfully");
                return $this->redirect(['post-credit-member/index']);
            } else {
                Yii::$app->session->setFlash('warning', "Warning");
            }
        }

        return $this->render('create_withdraw', [
            'model' => $model,
        ]);
    }

    public function actionPopup($id)
    {
        $layout = \Yii::$app->request->get('layout');
        if (!empty($layout)) {
            $this->layout = $layout;
        }
        return $this->render('popup', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCancel($id)
    {
        $postCreditTransetion = PostCreditTransection::findOne(['id' => $id]);
        if (!$postCreditTransetion) {
            throw new \yii\web\HttpException(404, 'The requested Item could not be found.');
        }
        return $this->render('_cancel', [
            'postCreditTransetion' => $postCreditTransetion,
        ]);
    }

    public function actionUpdatestatus()
    {
        $admin_id = \Yii::$app->user->identity->id;
        $params = \Yii::$app->request->get();
        $result = true;
        $txt = '';
        if (!empty($params)) {

            $requestModel = PostCreditTransection::findOne(['id' => $params['id']]);

            //ต้องเป็น status ที่ รออนุมัติแล้วเท่านั้น
            if (in_array($requestModel->status, [Constants::status_waitting])) {
                $reason_id = '';
                if ($requestModel->action_id == Constants::action_credit_top_up) {
                    $reason_id = Constants::reason_credit_top_up;
                } else if ($requestModel->action_id == Constants::action_credit_withdraw) {
                    $reason_id = Constants::reason_credit_withdraw;
                } else if ($requestModel->action_id == Constants::action_commission_withdraw_direct) {
                    $reason_id = Constants::reason_commission_withdraw_direct;
                }

                //------------ตรวจสอบ credit master------------//
                $result_CreditMaster = true;
                if ($params['type'] == 'approve') {
                    $CreditMasterBalance = CreditTransectionSearch::checkCreditMasterBalance($requestModel->action_id, $reason_id, $requestModel->amount);
                    if (!empty($CreditMasterBalance) && isset($CreditMasterBalance['amount']) && $CreditMasterBalance['amount'] < 0) {
                        $result_CreditMaster = false;
                        $result = false;
                        $txt = 'เนื่องจาก Credit Master ไม่เพียงพอต่อการทำรายการ';
                    }
                }
                //------------ตรวจสอบ credit master------------//

                if ($result_CreditMaster === true) {
                    $requestModel->remark = isset(Yii::$app->request->post()['PostCreditTransection']['remark']) ? Yii::$app->request->post()['PostCreditTransection']['remark'] : '';
                    $requestModel->status = ($params['type'] == 'approve' ? Constants::status_approve : Constants::status_cancel);
                    $result = $requestModel->save();

                    if ($result && $requestModel->status == Constants::status_approve && $requestModel->action_id !== Constants::action_commission_withdraw_direct) {
                        $result = Credit::creditWalk($requestModel->action_id, $requestModel->poster_id, $admin_id, $reason_id, $requestModel->amount);
                    } else if ($result && $requestModel->status == Constants::status_approve && $requestModel->action_id === Constants::action_commission_withdraw_direct) {
                        $commission = Commission::find()->where(['user_id' => $requestModel->poster_id])->one();
                        if (!$commission) {
                            throw new ServerErrorHttpException('Not Found Commission');
                        }
                        $creditTransectionModel = new CreditTransection();
                        $creditTransectionModel->action_id = $requestModel->action_id;
                        $creditTransectionModel->reason_action_id = Constants::reason_commission_withdraw_direct;
                        $creditTransectionModel->operator_id = Yii::$app->user->id;
                        $creditTransectionModel->reciver_id = $requestModel->poster_id;
                        $creditTransectionModel->amount = $requestModel->amount;
                        $creditTransectionModel->balance = $commission->balance;
                        $creditTransectionModel->credit_master_balance = $CreditMasterBalance['amount'];
                        $creditTransectionModel->create_at = date('Y-m-d H:i:s');
                        $creditTransectionModel->create_by = Yii::$app->user->id;
                        $creditTransectionModel->remark = 'ถอนตรงระบบแนะนำ';
                        $result = $creditTransectionModel->save();
                    } else if ($result && $requestModel->status == Constants::status_cancel && $requestModel->action_id === Constants::action_commission_withdraw_direct) {
                        $remark = 'คืนค่าคอมมินชั่นที่ได้ทำการยกเลิก';
                        Commission::commissionWalk(Constants::action_commission_top_up, $requestModel->poster_id, Yii::$app->user->id, Constants::reason_commission_top_up, $requestModel->amount, $remark);
                    }
                }
            }
        }
        if ($result === true) {
            Yii::$app->getSession()->setFlash('success', [
                'type' => 'success',
                'message' => 'สำเร็จ:: เปลี่ยนสถานะเรียบร้อยแล้ว',
            ]);
        } else {
            Yii::$app->getSession()->setFlash('error', [
                'type' => 'danger',
                'message' => 'เกิดข้อผิดพลาด!! ไม่สามารถเปลี่ยนสถานะได้' . $txt,
            ]);
        }

        Yii::$app->getSession()->setFlash('type', $params['active']);
        return $this->redirect(['post-credit-member/index']);

    }

    /**
     * Displays a single PostCreditTransection model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $layout = \Yii::$app->request->get('layout');
        if (!empty($layout)) {
            $this->layout = $layout;
        }

        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new PostCreditTransection model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PostCreditTransection();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
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
