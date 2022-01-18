<?php

namespace backend\controllers;

use common\models\Credit;
use common\models\FixNumberYeekee;
use common\models\YeekeeChitDetail;
use common\models\YeekeeChitSearch;
use common\models\YeekeeSearch;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use common\models\Running;
use common\models\Yeekee;
use common\libs\Constants;
use common\models\AuthRoles;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

/**
 * Site controller
 */
class SystemController extends Controller
{

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function behaviors()
    {
        $identity = \Yii::$app->user->getIdentity();
        if (empty($identity)) {
            $this->layout = false;
            $this->redirect(Yii::$app->urlManager->createUrl(['user/login']));
        } else {
            $modelAuthRoles = new AuthRoles();
            $arrRoles = $modelAuthRoles->_getRoles($identity->auth_roles_id);
            if (!in_array('system', $arrRoles)) {
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
     * Displays homepage.
     *
     * @return string
     */
    public function actionManageresult()
    {
        $identity = Yii::$app->user->getIdentity();
        $modelAuthRoles = new AuthRoles();
        $arrRoles = $modelAuthRoles->_getRoles($identity->auth_roles_id);
        $searchModel  = new YeekeeSearch();
        if (isset(Yii::$app->request->get()['YeekeeSearch']['date_at_search'])) {
            $date = date('Y-m-d', strtotime(Yii::$app->request->get()['YeekeeSearch']['date_at_search']));
        } else {
            $date = date('Y-m-d');
        }
        $dataProvider = $searchModel->searchManageResult(Yii::$app->request->get());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'arrRoles' => $arrRoles,
            'searchModel' => $searchModel,
            'date' => $date,
        ]);
    }

    public function actionStatus($id)
    {
        $userId = Yii::$app->user->id;
        $model = Yeekee::find()->where(['id' => $id])->one();
        $identity = \Yii::$app->user->getIdentity();
        if (!$model) {
            throw new ServerErrorHttpException('Yeekee Not Found.');
        }
        $modelAuthRoles = new AuthRoles();
        $arrRoles = $modelAuthRoles->_getRoles($identity->auth_roles_id);
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {

                $model->update_by = Yii::$app->user->id;
                $model->update_at = date('Y-m-d H:i:s');
                if (intval($model->status) === intval(Constants::status_inactive)) {
                    $yeekeeChits = YeekeeChitSearch::find()->where(['yeekee_id' => $id])->all();
                    foreach ($yeekeeChits as $yeekeeChit) {
                        $remark = 'คืนโพย จับยี่กี รอบที่ ' . $model->round . ' / ' . date('d/m/Y', strtotime($model->date_at)) . ' #' . $yeekeeChit->getOrder();
                        $resutl = Credit::creditWalk(Constants::action_credit_top_up, $yeekeeChit->user_id, $userId, Constants::reason_credit_return_chit, $yeekeeChit->total_amount, $remark);
                        $yeekeeChit->status = Constants::status_cancel;
                        $yeekeeChit->update_at = date('Y-m-d H:i:s');
                        $yeekeeChit->update_by = $userId;
                        $yeekeeChit->total_amount = 0;
                        if ($yeekeeChit->save()) {
                            YeekeeChitDetail::updateAll(['amount' => 0], ['yeekee_chit_id' => $yeekeeChit->id]);
                        }
                    }
                }
                if (!$model->save()) {
                    throw new ServerErrorHttpException('Can not save');
                }
                $transaction->commit();
                return $this->redirect(['manageresult']);
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            }
        }
        return $this->render('_form', [
            'model' => $model,
            'arrRoles' => $arrRoles,
        ]);
    }

    public function actionAnswer($id)
    {
        $identity = Yii::$app->user->getIdentity();
        $modelAuthRoles = new AuthRoles();
        $arrRoles = $modelAuthRoles->_getRoles($identity->auth_roles_id);
        if(!in_array('fix-yeekee-number', $arrRoles)){
            throw new ServerErrorHttpException('คุณไม่มีสิทธิ์ในการล็อคเลขยี่กี่');
        }
        $yeekee = Yeekee::find()->where(['id' => $id])->one();
        if (!$yeekee) {
            throw new ServerErrorHttpException('Yeekee Not Found.');
        }
        $model = FixNumberYeekee::find()->where(['yeekeeId' => $id])->one();
        if (!$model) {
            $model = new FixNumberYeekee();
            $model->createdAt = date('Y-m-d H:i:s');
            $model->createdBy = Yii::$app->user->id;
        }else{
            $model->updatedAt = date('Y-m-d H:i:s');
            $model->updatedBy = Yii::$app->user->id;
        }
        $model->yeekeeId = $id;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect('manageresult');
        }
        return $this->render('_answer', [
            'model' => $model,
            'yeekee' => $yeekee,
        ]);
    }

    public function actionView($id)
    {
        $model = Yeekee::find()->where(['id' => $id])->one();
        $threeTop = $this->getResult($model->result, 'three_top');
        $threeTod = $this->getResult($model->result, 'three_tod');
        $twoTop = $this->getResult($model->result, 'two_top');
        $twoUnder = $this->getResult($model->result, 'two_under');
        $runTop = $this->getResult($model->result, 'run_top');
        $runUnder = $this->getResult($model->result, 'run_under');

        return $this->render('view', [
            'model' => $model,
            'threeTop' => $threeTop,
            'threeTod' => $threeTod,
            'twoTop' => $twoTop,
            'twoUnder' => $twoUnder,
            'runTop' => $runTop,
            'runUnder' => $runUnder,
        ]);
    }


    public function actionViewFixNumber($id)
    {
        $identity = Yii::$app->user->getIdentity();
        $modelAuthRoles = new AuthRoles();
        $arrRoles = $modelAuthRoles->_getRoles($identity->auth_roles_id);
        if(!in_array('fix-yeekee-number', $arrRoles)){
            throw new ServerErrorHttpException('คุณไม่มีสิทธิ์ในการดูรายการล็อคเลขยี่กี่');
        }
        $fixNumberYeekee = FixNumberYeekee::find()->where(['yeekeeId' => $id])->one();
        if(!$fixNumberYeekee) {
            throw new NotFoundHttpException();
        }
        $model = Yeekee::find()->where(['id' => $id])->one();
        return $this->render('view-fix-number', [
            'model' => $model,
            'fixNumberYeekee' => $fixNumberYeekee,
        ]);
    }
    public function actionCancel($id)
    {
        if (!FixNumberYeekee::deleteAll(['yeekeeId' => $id])) {
            throw new ServerErrorHttpException('Can not delete fix number');
        }
        return $this->redirect(['manageresult']);
    }

    public function getResult($number, $type)
    {
        $lenght = strlen($number);
        $result = substr('' . $number, $lenght - 5, $lenght);

        //สามตัวบน : สามตัว นับจากขวา  xx???
        if ($type == 'three_top') {
            $result = substr($result, 2, 3);

            //สามตัว โต๊ด : สามตัว ขวาสลับกันได้ xx???
        } else if ($type == 'three_tod') {
            $result = substr($result, 2, 3);
            $arr_num = str_split($result);
            $n = 0;
            $arr_swap_num = [];
            for ($i = 0; $i < count($arr_num); $i++) {
                $tmp = $arr_num[$i];
                for ($j = 0; $j < count($arr_num); $j++) {
                    if ($i != $j) {
                        $tmp .= '' . $arr_num[$j];
                    }
                }
                if (!in_array($tmp, $arr_swap_num)) {
                    $arr_swap_num[$n++] = $tmp;
                }

                $tmp = $arr_num[$i];
                for ($j = (count($arr_num) - 1); $j >= 0; $j--) {
                    if ($i != $j) {
                        $tmp .= '' . $arr_num[$j];
                    }
                }
                if (!in_array($tmp, $arr_swap_num)) {
                    $arr_swap_num[$n++] = $tmp;
                }
            }
            return $arr_swap_num;
            //สองตัวบน : สองตัว นับจากขวา xxx??
        } else if ($type == 'two_top') {
            $result = substr($result, 3, 2);

            //สองตัวล่าง : สองตัว นับจากซ้าย ??xxx
        } else if ($type == 'two_under') {
            $result = substr($result, 0, 2);

            //วิ่งบน : มีหนึ่งใน สามตัวขวา
        } else if ($type == 'run_top') {
            $result = substr($result, 2, 3);
            $result = str_split($result);

            //วิ่งล่าง : มีหนึ่งใน สองตัวซ้าย
        } else if ($type == 'run_under') {
            $result = substr($result, 0, 2);
            $result = str_split($result);

            //อื่นๆ
        } else if ($type == 'other') {
            $result = substr('' . $this->result, 0, $lenght - 5);
        }

        return $result;
    }

    public function actionCheckDuplicate($id)
    {
        $model = Yeekee::find()->where(['id' => $id])->one();
        if (!$model) {
            throw new NotFoundHttpException();
        }
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $request = Yii::$app->request->post();
            $numbers = $request['result'];
            $message = '';
            foreach ($numbers as $number) {
                if ($number) {
                    $threeTop = $this->getResult($number, 'three_top');
                    $isYeekeeThreeTop = YeekeeChitDetail::find()->joinWith('yeekeeChit')->where([
                        'yeekee_id' => $model->id,
                        'number' => $threeTop,
                        'play_type_code' => 'three_top',
                    ])->count();
                    if ($isYeekeeThreeTop) {
                        $message .= $number.'เลขสามตัวบนมีคนซื้อไปแล้ว ';
                    }
                    $threeTod = $this->getResult($number, 'three_tod');
                    $isYeekeeThreeTod = YeekeeChitDetail::find()->joinWith('yeekeeChit')->where([
                        'yeekee_id' => $model->id,
                        'number' => $threeTod,
                        'play_type_code' => 'three_tod',
                    ])->count();
                    if ($isYeekeeThreeTod) {
                        $message .= $number.'เลขสามตัวโต๊ดมีคนซื้อไปแล้ว ';
                    }
                    $twoTop = $this->getResult($number, 'two_top');
                    $isYeekeeTwoTop = YeekeeChitDetail::find()->joinWith('yeekeeChit')->where([
                        'yeekee_id' => $model->id,
                        'number' => $twoTop,
                        'play_type_code' => 'two_top',
                    ])->count();
                    if ($isYeekeeTwoTop) {
                        $message .= $number.'เลขสองตัวบนมีคนซื้อไปแล้ว ';
                    }
                    $twoUnder = $this->getResult($number, 'two_under');
                    $isYeekeeTwoUnder = YeekeeChitDetail::find()->joinWith('yeekeeChit')->where([
                        'yeekee_id' => $model->id,
                        'number' => $twoUnder,
                        'play_type_code' => 'two_under',
                    ])->count();
                    if ($isYeekeeTwoUnder) {
                        $message .= $number.'เลขสองตัวล่างมีคนซื้อไปแล้ว ';
                    }
                    $tunTop = $this->getResult($number, 'run_top');
                    $isYeekeeRunTop = YeekeeChitDetail::find()->joinWith('yeekeeChit')->where([
                        'yeekee_id' => $model->id,
                        'number' => $tunTop,
                        'play_type_code' => 'run_top',
                    ])->count();
                    if ($isYeekeeRunTop) {
                        $message .= $number.'เลขวิ่งบนมีคนซื้อไปแล้ว ';
                    }
                    $runUnder = $this->getResult($number, 'run_under');
                    $isYeekeeRunUnder = YeekeeChitDetail::find()->joinWith('yeekeeChit')->where([
                        'yeekee_id' => $model->id,
                        'number' => $runUnder,
                        'play_type_code' => 'run_under',
                    ])->count();
                    if ($isYeekeeRunUnder) {
                        $message .= 'เลขวิ่งล่างมีคนซื้อไปแล้ว ';
                    }
                }
                return [
                    'data' => [
                        'success' => true,
                        'message' => $message,
                    ],
                    'code' => 0,
                ];
            }
            return [
                'data' => [
                    'success' => true,
                    'message' => '',
                ],
                'code' => 0, // Some semantic codes that you know them for yourself
            ];
        }
    }

    public function actionCancelYeekee($id)
    {
        $yeekee = YeekeeSearch::find()->where([
            'id' => $id,
            'status' => [Constants::status_active, Constants::status_processing, Constants::status_processing_2]
        ])->one();
        if (!$yeekee) {
            throw new NotFoundHttpException();
        }
        $result = true;
        //คืนเครดิต
        $yeekeeChitAll = YeekeeChitSearch::find()->where(['yeekee_id' => $yeekee->id])->all();
        $transaction = \Yii::$app->db->beginTransaction();
        $remark = Constants::$reason_credit[Constants::reason_credit_cancel] . ' จับยี่กีรอบที่ ' . $yeekee->round . '/' . date('d/m/Y', strtotime($yeekee->date_at)) . ' #' . $yeekee->getOrderId();
        foreach ($yeekeeChitAll as $yeekeeChit) {

            if (Credit::creditWalk(Constants::action_credit_top_up, $yeekeeChit->user_id, Constants::user_system_id, Constants::reason_credit_cancel, $yeekeeChit->total_amount, $remark)) {
                $yeekeeChit->status = Constants::status_cancel;
                $result = $yeekeeChit->save();
            } else {
                $result = false;
            }

            if (!$result) {
                $error = true;
                var_dump($yeekeeChit->getErrors());
                exit;
            }
        }
        //ปรับสถานะ ยกเลิก
        $yeekee->status = Constants::status_cancel;
        $yeekee->update_at = date('Y-m-d H:i:s');
        $yeekee->update_by = Constants::user_system_id;
        if (!$yeekee->save()) {
            $result = false;
        }

        if ($result) {
            $transaction->commit();
        } else {
            $transaction->rollBack();
        }
        return $this->redirect(['manageresult']);
    }
}
