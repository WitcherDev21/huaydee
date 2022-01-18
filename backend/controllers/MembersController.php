<?php
namespace backend\controllers;

use common\models\ThaiSharedGameChit;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use common\libs\Constants;
use common\models\User;
use common\models\UserSearch;
use common\models\UsersForm;
use common\models\AuthRoles;
use common\models\CreditTransectionSearch;
use common\models\PostCreditTransectionTopup;
use common\models\UserHasBankSearch;
use common\models\Credit;
use common\models\PostCreditTransection;
use common\models\UserHasBank;
use common\models\BankSearch;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

/**
 * Site controller
 */
class MembersController extends Controller
{
    public $defaultAction = 'list';
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
        if(empty($identity)){
            $this->layout = false;
            $this->redirect(Yii::$app->urlManager->createUrl(['user/login']));
        }else{
            $modelAuthRoles = new AuthRoles();
            $arrRoles = $modelAuthRoles->_getRoles($identity->auth_roles_id);
            if(!in_array('members', $arrRoles)){
                $this->layout = false;
                $this->redirect(Yii::$app->urlManager->createUrl(['user/logout']));
            }
        }
        return [
            'verbs' => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
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
    public function actionList()
    {
        $searchModel  = new UserSearch();
        $dataProvider = $searchModel->searchMember(Yii::$app->request->get());
    	$model = new PostCreditTransectionTopup();
        
//        $UserProfile = UserSearch::getUserProfile(Constants::auth_roles_member, 'auth_roles_id');
        
        return $this->render('list', [
            'searchModel'=>$searchModel,
            'dataProvider'=>$dataProvider,
            'model'=>$model
        ]);
    }

    public function actionWaitingApprove()
    {
        $searchModel  = new UserHasBankSearch();
        $dataProvider = $searchModel->searchWaiting(Yii::$app->request->get());

        return $this->render('waiting-approve', [
            'searchModel' =>$searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionChangstatus()
    {
        $status = false;
    	$params = \Yii::$app->request->get();
    	if($params){
            $User = UserSearch::find()
                ->where(['id'=>$params['id']])
                ->one();
            if(!empty($User)){
                $User->user_status = ($params['type']=='withhold' ? Constants::user_status_withhold : Constants::user_status_active);
                $User->tel = (!empty($User->tel) ? $User->tel : '-');
                if($User->save()){
                    $status = true;
                }
            }
        }
        if($status === true){
            Yii::$app->getSession()->setFlash('success', [
                'type' => 'success',
                'message' => 'สำเร็จ:: เปลี่ยนสถานะเรียบร้อยแล้ว',
            ]);
        }else{
            Yii::$app->getSession()->setFlash('error', [
                'type' => 'danger',
                'message' => 'เกิดข้อผิดพลาด!! ไม่สามารถเปลี่ยนสถานะได้',
            ]);
        }
    	return $this->redirect(['members/list']);
        
    }
    
    public function actionChit()
    {
    	$params = \Yii::$app->request->get();
    	if(!empty($params) && !empty($params['id'])){
            $obj = TemplateController::chit_list($params['id']);
            if(!empty($obj)){
                return $this->render($obj[0],$obj[1]);
            }
        }
        return $this->redirect(['members/list']);
    }

    public function actionChitBlackred($id)
    {
        $obj = TemplateController::chitBlackred($id);
        if(!empty($obj)){
            return $this->render($obj[0],$obj[1]);
        }
        return $this->redirect(['members/list']);
    }

    public function actionChitShared($id)
    {
        $obj = TemplateController::chitShared($id);
        if(!empty($obj)){
            return $this->render($obj[0],$obj[1]);
        }
        return $this->redirect(['members/list']);
    }
    
    public function actionChit_detail()
    {
    	$params = \Yii::$app->request->get();
    	if(!empty($params) && !empty($params['id'])){
            $obj = TemplateController::chit_detail($params['id']);
            if(!empty($obj)){
                return $this->render($obj[0],$obj[1]);
            }
        }
        return $this->redirect(['members/list']);
    }

    public function actionChitSharedDetail()
    {
        $params = \Yii::$app->request->get();
        if(!empty($params) && !empty($params['id'])){
            $thaiSharedGameChit = ThaiSharedGameChit::find()->where(['id' => $params['id']])->one();
            if ($thaiSharedGameChit->thaiSharedGame->gameId === Constants::LOTTERYLAODISCOUNTGAME || $thaiSharedGameChit->thaiSharedGame->gameId === Constants::LOTTERYLAOGAME || $thaiSharedGameChit->thaiSharedGame->gameId === Constants::LOTTERY_VIETNAM_SET) {
                $obj = TemplateController::chitLotteryLaoSetDetail($params['id']);
            } else {
                $obj = TemplateController::chitSharedDetail($params['id']);
            }
            if(!empty($obj)){
                return $this->render($obj[0],$obj[1]);
            }
        }
        return $this->redirect(['members/list']);
    }
    
    public function actionCredit()
    {
    	$params = \Yii::$app->request->get();
    	if(!empty($params) && !empty($params['id'])){
            $User = UserSearch::find()
                ->where(['id'=>$params['id']])
                ->one();
            if(!empty($User)){
                $searchModel = new CreditTransectionSearch();
                $searchModel->reciver_id = $User->id;
                $dataProvider = $searchModel->search('');
                return $this->render('credit', [
                    'dataUser'=>$User,
                    'dataProvider'=>$dataProvider
                ]);
            }
        }
        return $this->redirect(['members/list']);
    }
    
    public function actionUpdate()
    {
    	$params = \Yii::$app->request->get();
    	if(!empty($params) && !empty($params['id'])){
            $modelUsers = new UsersForm();
            $User = UserSearch::find()
                ->joinWith(['userHasBanks'])
                ->where(['user.id'=>$params['id']])
                ->one();
            
            $modelBankSearch = new BankSearch();
            $BankList = $modelBankSearch->_getList();
            
            if(!empty($User)){
                return $this->render('update', [
                    'dataUser'=>$User,
                    'modelUsers'=>$modelUsers,
                    'BankList'=>$BankList
                ]);
            }
        }
        return $this->redirect(['members/list']);
    }
    
    public function actionSaveupdate()
    {
        $status = false;
    	$params = \Yii::$app->request->get();
        $UsersForm = \Yii::$app->request->post();
    	if(!empty($params) && !empty($params['id']) && !empty($UsersForm) && !empty($UsersForm['UsersForm'])){
            
            $User = User::find()
                ->where(['id'=>$params['id']])
                ->one();
            if(!empty($User)){
                if(!empty($UsersForm['UsersForm']['change_password'])){
                    $User->setPassword($UsersForm['UsersForm']['change_password']);
                    $User->generateAuthKey();
                }
                foreach ($UsersForm['UsersForm']['user_has_bank_id'] as $key => $user_has_bank_id) {
                    $userHasBank = UserHasBank::find()->where(['id' => $user_has_bank_id])->one();
                    if ($userHasBank) {
                        $userHasBank->user_id = $User->id;
                        $userHasBank->bank_id = $UsersForm['UsersForm']['bank_id'][$key];
                        $userHasBank->bank_account_name = $UsersForm['UsersForm']['bank_account_name'][$key];
                        $userHasBank->bank_account_no = $UsersForm['UsersForm']['bank_account_no'][$key];
                        $userHasBank->version = 1;
                        $userHasBank->status = 1;
                        if (!$userHasBank->save()) {
                            if (isset($userHasBank->errors["bank_account_no"]) && $userHasBank->errors["bank_account_no"][0] !== '') {
                                throw new ServerErrorHttpException($userHasBank->errors["bank_account_no"][0]);
                            }
                            throw new ServerErrorHttpException('Can not save user has bank');
                        }
                    }elseif (isset($UsersForm['UsersForm']['bank_id'][$key]) && $UsersForm['UsersForm']['bank_id'][$key] !== '') {
                        $userHasBank = new UserHasBank();
                        $userHasBank->user_id = $User->id;
                        $userHasBank->bank_id = $UsersForm['UsersForm']['bank_id'][$key];
                        $userHasBank->bank_account_name = $UsersForm['UsersForm']['bank_account_name'][$key];
                        $userHasBank->bank_account_no = $UsersForm['UsersForm']['bank_account_no'][$key];
                        $userHasBank->version = 1;
                        $userHasBank->status = 1;
                        if (!$userHasBank->save()) {
                            if (isset($userHasBank->errors["bank_account_no"]) && $userHasBank->errors["bank_account_no"][0] !== '') {
                                throw new ServerErrorHttpException($userHasBank->errors["bank_account_no"][0]);
                            }
                            throw new ServerErrorHttpException('Can not save user has bank');
                        }
                    }
                }
                $User->email = $UsersForm['UsersForm']['email'];
                $User->tel = $UsersForm['UsersForm']['tel'];
                if($User->save()){
                    $status = true;
                }
            }
        }
        if($status === true){
            Yii::$app->getSession()->setFlash('success', [
                'type' => 'success',
                'message' => 'สำเร็จ:: บันทึกข้อมูลเรียบร้อยแล้ว',
            ]);
        }else{
            Yii::$app->getSession()->setFlash('error', [
                'type' => 'danger',
                'message' => 'เกิดข้อผิดพลาด!! ไม่สามารถบันทึกข้อมูลได้',
            ]);
        }
        return $this->redirect(['members/list']);
    }
    
    public function actionSavetopup()
    {
        $params = Yii::$app->request->post();
        $status = false;
        $txt = '';
    	if (!empty($params)) {
            $user_id = \Yii::$app->user->identity->id;
            $param = $params['PostCreditTransectionTopup'];
            $User = UserSearch::find()->where(['id'=>$param['user_id']])->one();
            $creditMasterBalance = CreditTransectionSearch::checkCreditMasterBalance(Constants::action_credit_top_up_admin, Constants::reason_credit_top_up_promotion, $param['amount']);
            if ($creditMasterBalance['amount'] < 0) {
                Yii::$app->getSession()->setFlash('error', [
                    'type' => 'danger',
                    'message' => 'กรุณาเติมเครดิตมาสเตอร์',
                ]);
                return $this->redirect(['members/list']);
            }
            if(!empty($User) && $User->user_status == Constants::user_status_active){
                $status = Credit::creditWalk($param['status'], $param['user_id'], $user_id, Constants::reason_credit_top_up_promotion, $param['amount'], $param['remark']);
            }else{
                $txt = ' เนื่องจากผู้ใช้งานถูกระงับ';
            }
        }
        if($status){
            Yii::$app->getSession()->setFlash('success', [
                'type' => 'success',
                'message' => 'สำเร็จ:: บันทึกข้อมูลเรียบร้อยแล้ว',
            ]);
        }else{
            Yii::$app->getSession()->setFlash('error', [
                'type' => 'danger',
                'message' => 'เกิดข้อผิดพลาด!! ไม่สามารถบันทึกข้อมูลได้'.$txt,
            ]);
        }
        return $this->redirect(['members/list']);
    }
    
    public function actionGettopup()
    {
        $this->layout = false;
        $params = Yii::$app->request->post();
        if(!empty($params)){
            $model = new PostCreditTransectionTopup();
            $User = UserSearch::find()
                ->where(['id'=>$params['id']])
                ->one();
            echo json_encode(
                    $this->render('_create_topup',[
                        'model'=>$model,
                        'dataUser'=>$User
                    ]),JSON_UNESCAPED_UNICODE);
        }
    }
    
    public function actionGetwithdraw()
    {
        $this->layout = false;
        $params = Yii::$app->request->post();
        if(!empty($params)){
            $model = new PostCreditTransection();
            $User = UserSearch::find()
                ->where(['id'=>$params['id']])
                ->one();
            $model->status = Constants::reason_credit_withdraw_direct;
            echo json_encode(
                    $this->render('_create_withdraw',[
                        'model'=>$model,
                        'dataUser'=>$User
                    ]),JSON_UNESCAPED_UNICODE);
        }
    }
    
    
    public function actionSavewithdraw()
    {
        $params = Yii::$app->request->post();
        $status = false;
        $txt = '';
    	if (!empty($params)) {
            $param = $params['PostCreditTransection'];
            $user_id = \Yii::$app->user->identity->id;
            $User = UserSearch::find()->where(['id'=>$param['poster_id']])->one();
            if(!empty($User) && $User->user_status == Constants::user_status_active){
                $status = Credit::creditWalk($param['status'], $param['poster_id'], $user_id, Constants::reason_credit_withdraw_direct, $param['amount'], $param['remark']);
            }else{
                $txt = ' เนื่องจากผู้ใช้งานถูกระงับ';
            }
        }
        if($status){
            Yii::$app->getSession()->setFlash('success', [
                'type' => 'success',
                'message' => 'สำเร็จ:: บันทึกข้อมูลเรียบร้อยแล้ว',
            ]);
        }else{
            Yii::$app->getSession()->setFlash('error', [
                'type' => 'danger',
                'message' => 'เกิดข้อผิดพลาด!! ไม่สามารถบันทึกข้อมูลได้'.$txt,
            ]);
        }
        return $this->redirect(['members/list']);
    }

    public function actionCreatewithdraw()
    {
    	$model = new PostCreditTransection();
        
    	$params = Yii::$app->request->post();
    	if ($params) {
            $model->load($params);
            $dataForm = $params['PostCreditTransection'];

            $paramsGet = Yii::$app->request->get();
            $user_id = \Yii::$app->user->identity->id;
            $model->poster_id = $paramsGet['id'];
            $model->action_id = Constants::action_credit_withdraw_admin;
            $model->user_has_bank_id = $dataForm['user_has_bank_id'];
            $model->user_has_bank_version = UserHasBankSearch::getCurrentVersion($dataForm['user_has_bank_id']);
            $model->status = Constants::status_approve;
            $model->amount = $dataForm['amount'];
            $model->create_at = date('Y-m-d H:i:s',time());
            $model->create_by = $user_id;
            if($model->validate()){
                if($model->save()){

                    Credit::creditWalk(Constants::action_credit_withdraw_admin, $paramsGet['id'], $user_id, Constants::reason_credit_withdraw, $dataForm['amount']);

                    Yii::$app->getSession()->setFlash('success', [
                        'type' => 'success',
                        'message' => 'สำเร็จ:: บันทึกข้อมูลเรียบร้อยแล้ว',
                    ]);
                }else{
                    Yii::$app->getSession()->setFlash('error', [
                        'type' => 'danger',
                        'message' => 'เกิดข้อผิดพลาด!! ไม่สามารถบันทึกข้อมูลได้',
                    ]);
                }
                return $this->redirect(['members/list']);
            }
    	}
        $params = \Yii::$app->request->get();
        if(!empty($params) && !empty($params['id'])){
            $modelUsers = new UsersForm();
            $User = UserSearch::find()
                ->where(['id'=>$params['id']])
                ->one();
            if(!empty($User)){
                return $this->render('create_withdraw', [
                    'dataUser'=>$User,
                    'model'=>$model
                ]);
            }
        }
        return $this->redirect(['members/list']);
    }

    public function actionDelete($id)
    {
        $user = User::find()->where(['id' => $id])->one();
        if (!$user) {
            throw new ServerErrorHttpException('Not found user');
        }
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $userHasBank = UserHasBank::find()->where(['user_id' => $id])->one();
            if (!$userHasBank->delete()) {
                throw new ServerErrorHttpException('Can not delete userHasBank');
            }
            $credit = Credit::find()->where(['user_id' => $id])->one();
            if (!$credit->delete()) {
                throw new ServerErrorHttpException('Can not delete credit');
            }
            if (!$user->delete()) {
                throw new ServerErrorHttpException('Can not delete user');
            }
            Yii::$app->getSession()->setFlash('success', [
                'type' => 'success',
                'message' => 'สำเร็จ:: ลบ user เรียบร้อย',
            ]);
            $transaction->commit();
            return $this->redirect(['members/list']);
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    public function actionChangeStatusBank($id, $status)
    {
        $userHasBank = UserHasBank::find()->where(['id' => $id])->one();
        if (!$userHasBank) {
            throw new NotFoundHttpException('Not Found');
        }
        $userHasBank->status = $status;
        if (!$userHasBank->save()) {
            throw new ServerErrorHttpException('Can not update user has bank status');
        }
        return $this->redirect(['members/waiting-approve']);
    }
}
