<?php
namespace backend\controllers;


use common\models\DiscountGameSearch;
use common\models\PlayTypeSearch;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use common\libs\Constants;
use common\models\SettingBenefit;
use common\models\AuthRoles;
use common\models\AuthPermission;
use common\models\AuthPermissionParent;
use common\models\UsersForm;
use common\models\User;
use common\models\SettingBenefitForm;
use common\models\SettingCommissionCredit;
use common\models\SettingCommissionCreditForm;
use common\models\PlayType;
use common\models\PlayTypeForm;
use common\models\UserSearch;
use common\models\BankSearch;
use common\models\Bank;
use common\models\SettingGameBenefit;
use common\models\SettingGameBenefitForm;
use common\models\CreditMasterForm;
use common\models\CreditTransection;
use common\models\CreditTransectionSearch;

/**
 * Site controller
 */
class SettingController extends Controller
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
        if(empty($identity)){
            $this->layout = false;
            $this->redirect(Yii::$app->urlManager->createUrl(['user/login']));
        }else{
            $modelAuthRoles = new AuthRoles();
            $arrRoles = $modelAuthRoles->_getRoles($identity->auth_roles_id);
            if(!in_array('setting', $arrRoles)){
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
    public function actionIndex()
    {
//        $listAuthPermission = AuthPermission::find()
//            ->joinWith(['auth_permission_items','auth_permission_items.auth_items'])
//            ->where(['auth_permission.is_active'=>Constants::status_active])->all();
        $listAuthPermission = AuthPermission::find()
            ->where(['is_active'=>Constants::status_active])
            ->orderBy(['sorting'=>SORT_ASC])->all();

        $modelAuthRoles = new AuthRoles();
        $dataAuthRoles = $modelAuthRoles->search(Yii::$app->request->queryParams);

        $modelSettingBenefitForm = new SettingBenefitForm();
    	$yeekee = SettingBenefit::find()->where(['game_id'=>Constants::YEEKEE])->one();
        $blackred = SettingBenefit::find()->where(['game_id'=>Constants::BLACKRED])->one();

        $modelSettingCommissionCreditForm = new SettingCommissionCreditForm();
    	$ComInvite = SettingCommissionCredit::find()->where(['type'=>Constants::setting_commission_credit_invite,'is_active'=>Constants::status_active])->one();
        $ComAgent = SettingCommissionCredit::find()->where(['type'=>Constants::setting_commission_credit_agent,'is_active'=>Constants::status_active])->one();
        $ComAgentBlackred = SettingCommissionCredit::find()->where(['type'=>Constants::setting_commission_game_blackred_credit_invite,'is_active'=>Constants::status_active])->one();
        $CreditAmount = SettingCommissionCredit::find()->where(['type'=>Constants::setting_commission_credit_credit,'is_active'=>Constants::status_active])->one();

        $searchModelDiscountGame = new DiscountGameSearch();
        $dataProviderDiscountGame = $searchModelDiscountGame->search(Yii::$app->request->queryParams);
        $dataProviderDiscountGame->query->andWhere(['status' => 1]);
        $playTypes = PlayType::find()->joinWith('game')->all();
        $gameObjs = ArrayHelper::map($playTypes, 'id', function($model) {
            return $model['game']['title'].'-'.$model['title'];
        });
        $searchModelPlayType = new PlayTypeSearch();
        $dataProviderPlayType = $searchModelPlayType->search(Yii::$app->request->queryParams);
        $dataProviderPlayType->pagination->pageSize = 100;

        $modelUsers = new UsersForm();
        $RolesList = $modelAuthRoles->_getList();
        $modelPlayTypeForm = new PlayTypeForm();
        $dataPlayType = array();
        $listPlayType = PlayType::find()->all();
        if (!empty($listPlayType)){
            foreach ($listPlayType as $val) {
                $dataPlayType[$val->code] = $val->jackpot_per_unit;
            }
        }
        $modelBlackRed = new PlayTypeForm();
        $modelBlackRed->setScenario('black-red');

        $modelGameBenefit = new SettingGameBenefitForm();
        $dataGameBenefit = array();
        $listGameBenefit = SettingGameBenefit::find()->all();
        if(!empty($listGameBenefit)){
            foreach ($listGameBenefit as $val){
                $dataGameBenefit[$val->code] = $val->value;
            }
        }

        $modelBank = new BankSearch();
        $dataBank = $modelBank->search('');

        $modelCreditMasterIn = new CreditMasterForm();
        $modelCreditMasterOut = new CreditMasterForm();
        $modelCreditTransection = new CreditTransectionSearch();

        $dataCreditmaster = $modelCreditTransection->searchCreditMaster();

        return $this->render('index',[
            'modelAuthRoles' => $modelAuthRoles,
            'dataAuthRoles' => $dataAuthRoles,
            'listAuthPermission' => $listAuthPermission,
            'modelUsers' => $modelUsers,
            'RolesList' => $RolesList,
            'modelSettingBenefitForm' => $modelSettingBenefitForm,
            'yeekee' => $yeekee,
            'blackred' => $blackred,
            'modelSettingCommissionCreditForm' => $modelSettingCommissionCreditForm,
            'ComInvite' => $ComInvite,
            'ComAgent' => $ComAgent,
            'CreditAmount' => $CreditAmount,
            'modelPlayTypeForm' => $modelPlayTypeForm,
            'dataPlayType' => $dataPlayType,
            'modelBank' => $modelBank,
            'dataBank' => $dataBank,
            'modelGameBenefit' => $modelGameBenefit,
            'dataGameBenefit' => $dataGameBenefit,
            'dataCreditmaster' => $dataCreditmaster,
            'modelCreditMasterIn' => $modelCreditMasterIn,
            'modelCreditMasterOut' => $modelCreditMasterOut,
            'tabActive' => (!empty(\Yii::$app->request->get()) ? \Yii::$app->request->get() : ''),
            'modelBlackRed' => $modelBlackRed,
            'ComAgentBlackred' => $ComAgentBlackred,
            'searchModelDiscountGame' => $searchModelDiscountGame,
            'dataProviderDiscountGame' => $dataProviderDiscountGame,
            'gameObjs' => $gameObjs,
            'searchModelPlayType' => $searchModelPlayType,
            'dataProviderPlayType' => $dataProviderPlayType,
        ]);
    }


    public function actionBank()
    {
        $status = false;
    	$params = \Yii::$app->request->post();
    	if($params){
            $modelBank = new BankSearch();
            $fileName = '';
            if(!empty($_FILES)){
                if(!empty($_FILES['BankSearch']['name']['icon_add'])){
                    $modelBank->icon_add = UploadedFile::getInstances($modelBank, 'icon_add');
                    $fileName = $modelBank->uploadadd();
                }elseif(!empty($_FILES['BankSearch']['name']['icon_edit'])){
                    $modelBank->icon_edit = UploadedFile::getInstances($modelBank, 'icon_edit');
                    $fileName = $modelBank->uploadedit();
                }
            }

            $params['file_name'] = $fileName;
            $status = $modelBank->_save($params);

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
    	return $this->redirect(['setting/index/bank']);
    }

    public function actionBankgetdate()
    {
        $Bank = Bank::find()
            ->where(['id'=>$_POST['id'],'status'=>Constants::status_active])
            ->asArray()
            ->one();
        echo json_encode($Bank,JSON_UNESCAPED_UNICODE);
    }


    public function actionBankdelete()
    {
        $status = false;
    	$params = \Yii::$app->request->get();
        if(!empty($params) && !empty($params['id'])){

            $Bank = Bank::find()
                ->where(['id'=>$params['id'],'status'=>Constants::status_active])
                ->one();
            if(!empty($Bank)){
                $Bank->status = Constants::status_inactive;
                if($Bank->save()){
                    $status = true;
                }
            }
        }
        if($status === true){
            Yii::$app->getSession()->setFlash('success', [
                'type' => 'success',
                'message' => 'สำเร็จ:: ลบข้อมูลเรียบร้อยแล้ว',
            ]);
        }else{
            Yii::$app->getSession()->setFlash('error', [
                'type' => 'danger',
                'message' => 'เกิดข้อผิดพลาด!! ไม่สามารถลบข้อมูลได้',
            ]);
        }
    	return $this->redirect(['setting/index/bank']);
    }

    public function actionRole()
    {
        $status = false;
    	$params = \Yii::$app->request->post();
    	if($params){
            $modelAuthRoles = new AuthRoles();
            $AuthRoles = AuthRoles::find()
                ->where(['name'=>$params['AuthRoles']['name'],'is_active'=>Constants::status_active])
                ->andWhere(['<>','id', $params['AuthRoles']['id']])
                ->one();
            if(empty($AuthRoles)){
                $status = $modelAuthRoles->_save($params);
            }else{
                Yii::$app->getSession()->setFlash('error', [
                    'type' => 'danger',
                    'message' => 'เกิดข้อผิดพลาด!! มี Role นี้ในระบบแล้ว',
                ]);
                return $this->redirect(['setting/index/roles']);
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
    	return $this->redirect(['setting/index/roles']);
    }

    public function actionUsers()
    {
        $status = false;
    	$params = \Yii::$app->request->post();
        $txtStatus = '';
        if(!empty($params) && !empty($params['UsersForm'])){
            $checkData = User::find()->where(['username'=>$params['UsersForm']['username']])->one();
            if(!empty($checkData)){
                $txtStatus = ' เนื่องจากมี User Name นี้อยู่ในระบบแล้ว';
            }else{
                $modelUser = new User();
                $modelUser->username = $params['UsersForm']['username'];
                $modelUser->setPassword($params['UsersForm']['password']);
                $modelUser->generateAuthKey();
                $modelUser->email = $params['UsersForm']['email'];
                $modelUser->registration_ip = \Yii::$app->request->userIP;
                $modelUser->tel = $params['UsersForm']['tel'];
                $modelUser->auth_roles_id = $params['UsersForm']['user_roles_id'];
                $modelUser->user_status = 1;
                if($modelUser->save()){
                    $status = true;
                }
            }
        }

        if($status === true){
            Yii::$app->getSession()->setFlash('success', [
                'type' => 'success',
                'message' => 'สำเร็จ:: เพิ่มข้อมูลเรียบร้อยแล้ว',
            ]);
        }else{
            Yii::$app->getSession()->setFlash('error', [
                'type' => 'danger',
                'message' => 'เกิดข้อผิดพลาด!! ไม่สามารถเพิ่มข้อมูลได้'.$txtStatus,
            ]);
        }
    	return $this->redirect(['setting/index']);
    }

    public function actionRolesdelete()
    {
        $status = false;
    	$params = \Yii::$app->request->get();
        if(!empty($params) && !empty($params['id'])){

            $AuthRoles = AuthRoles::find()
                ->where(['id'=>$params['id'],'is_active'=>Constants::status_active])
                ->one();
            if(!empty($AuthRoles)){
                $AuthRoles->updated_by = Yii::$app->user->identity->id;
                $AuthRoles->updated_date = date('Y-m-d H:i:s');
                $AuthRoles->is_active = Constants::status_inactive;
                if($AuthRoles->save()){
                    $status = true;
                }
            }
        }
        if($status === true){
            Yii::$app->getSession()->setFlash('success', [
                'type' => 'success',
                'message' => 'สำเร็จ:: ลบข้อมูลเรียบร้อยแล้ว',
            ]);
        }else{
            Yii::$app->getSession()->setFlash('error', [
                'type' => 'danger',
                'message' => 'เกิดข้อผิดพลาด!! ไม่สามารถลบข้อมูลได้',
            ]);
        }
    	return $this->redirect(['setting/index/roles']);

    }

    public function actionRolesgetdate()
    {
        $AuthRoles = AuthRoles::find()
            ->where(['id'=>$_POST['id'],'is_active'=>Constants::status_active])
            ->one();
        $data = array();
        if(!empty($AuthRoles)){
            $AuthPermission = AuthPermissionParent::find()
                ->select('auth_permission_id,auth_permission.name')
//                ->joinWith(['auth_permission','auth_permission_child','auth_permission_child.auth_items'])
                ->joinWith(['auth_permission'])
                ->where(['auth_permission_parent.auth_rule_id'=>$AuthRoles->id,'auth_permission_parent.is_active'=>Constants::status_active])
                ->orderBy(['auth_permission_parent.id'=>SORT_ASC])
                ->asArray()
                ->all();
            $data = [
                'roles_id'=>$AuthRoles->id,
                'roles_name'=>$AuthRoles->name
            ];
            if(!empty($AuthPermission)){
                foreach ($AuthPermission as $val){
                    $data['permission'][] = [
                        'id'=>$val['auth_permission_id'],
                        'name'=>$val['name'],
                    ];
                }
            }else{
                $data['permission'] = '';
            }
        }
        echo json_encode($data,JSON_UNESCAPED_UNICODE);
    }

    public function actionBenefit()
    {
        $status = false;
    	$params = \Yii::$app->request->post();
    	if(!empty($params) && !empty($params['SettingBenefitForm'])){
            if(isset($params['SettingBenefitForm']['yeekee_value'])){
    		$yeekee = SettingBenefit::find()->where(['game_id'=>Constants::YEEKEE])->one();
    		$yeekee->value = $params['SettingBenefitForm']['yeekee_value'];
    		$yeekee->status = $params['SettingBenefitForm']['yeekee_status'];
    		if($yeekee->save()){
                    $status = true;
    		}
            }
//            if(isset($params['SettingBenefitForm']['blackred_value'])){
//    		$blackred = SettingBenefit::find()->where(['game_id'=>Constants::BLACKRED])->one();
//    		$blackred->value = $params['SettingBenefitForm']['blackred_value'];
//                $blackred->status = $params['SettingBenefitForm']['blackred_status'];
//    		if($blackred->save()){
//                    $status = true;
//    		}
//            }
    	}
        if($status === true){
            Yii::$app->getSession()->setFlash('success', [
                'type' => 'success',
                'message' => 'สำเร็จ:: แก้ไขข้อมูลเรียบร้อยแล้ว',
            ]);
        }else{
            Yii::$app->getSession()->setFlash('error', [
                'type' => 'danger',
                'message' => 'เกิดข้อผิดพลาด!! ไม่สามารถแก้ไขข้อมูลได้',
            ]);
        }
    	return $this->redirect(['setting/index/benefit']);
    }

    public function actionInvite()
    {
        $status = false;
    	$params = \Yii::$app->request->post();
    	if(!empty($params) && !empty($params['SettingCommissionCreditForm'])){
            if(isset($params['SettingCommissionCreditForm']['invite_value'])){
    		$ComInvite = SettingCommissionCredit::find()->where(['type'=>Constants::setting_commission_credit_invite,'is_active'=>Constants::status_active])->one();
                $ComInvite->value = $params['SettingCommissionCreditForm']['invite_value'];
    		if($ComInvite->save()){
                    $status = true;
    		}
            }
    	}
        if($status === true){
            Yii::$app->getSession()->setFlash('success', [
                'type' => 'success',
                'message' => 'สำเร็จ:: แก้ไขข้อมูลเรียบร้อยแล้ว',
            ]);
        }else{
            Yii::$app->getSession()->setFlash('error', [
                'type' => 'danger',
                'message' => 'เกิดข้อผิดพลาด!! ไม่สามารถแก้ไขข้อมูลได้',
            ]);
        }
    	return $this->redirect(['setting/index/commission_invite']);
    }


    public function actionAgent()
    {
        $status = false;
    	$params = \Yii::$app->request->post();
    	if(!empty($params) && !empty($params['SettingCommissionCreditForm'])){
            if(isset($params['SettingCommissionCreditForm']['agent_value'])){
    		$ComAgent = SettingCommissionCredit::find()->where(['type'=>Constants::setting_commission_credit_agent,'is_active'=>Constants::status_active])->one();
    		$ComAgent->value = $params['SettingCommissionCreditForm']['agent_value'];
    		if($ComAgent->save()){
                    $status = true;
    		}
            }
    	}
        if($status === true){
            Yii::$app->getSession()->setFlash('success', [
                'type' => 'success',
                'message' => 'สำเร็จ:: แก้ไขข้อมูลเรียบร้อยแล้ว',
            ]);
        }else{
            Yii::$app->getSession()->setFlash('error', [
                'type' => 'danger',
                'message' => 'เกิดข้อผิดพลาด!! ไม่สามารถแก้ไขข้อมูลได้',
            ]);
        }
    	return $this->redirect(['setting/index/commission_agent']);
    }

    public function actionAgentBlackred()
    {
        $status = false;
        $params = \Yii::$app->request->post();
        if(!empty($params) && !empty($params['SettingCommissionCreditForm'])){
            if(isset($params['SettingCommissionCreditForm']['agent_value'])){
                $ComAgent = SettingCommissionCredit::find()->where(['type'=>Constants::setting_commission_game_blackred_credit_invite,'is_active'=>Constants::status_active])->one();
                if (!$ComAgent) {
                    $ComAgent = new SettingCommissionCredit();
                    $ComAgent->type = Constants::setting_commission_game_blackred_credit_invite;
                }
                $ComAgent->value = $params['SettingCommissionCreditForm']['agent_value'];
                if($ComAgent->save()){
                    $status = true;
                }
            }
        }
        if($status === true){
            Yii::$app->getSession()->setFlash('success', [
                'type' => 'success',
                'message' => 'สำเร็จ:: แก้ไขข้อมูลเรียบร้อยแล้ว',
            ]);
        }else{
            Yii::$app->getSession()->setFlash('error', [
                'type' => 'danger',
                'message' => 'เกิดข้อผิดพลาด!! ไม่สามารถแก้ไขข้อมูลได้',
            ]);
        }
        return $this->redirect(['setting/index/commission_agent_blackred']);
    }

    public function actionCredit()
    {
        $status = false;
    	$params = \Yii::$app->request->post();
    	if(!empty($params) && !empty($params['SettingCommissionCreditForm'])){
            if(isset($params['SettingCommissionCreditForm']['credit_value'])){
    		$CreditAmount = SettingCommissionCredit::find()->where(['type'=>Constants::setting_commission_credit_credit,'is_active'=>Constants::status_active])->one();
    		$CreditAmount->value = $params['SettingCommissionCreditForm']['credit_value'];
    		if($CreditAmount->save()){
                    $status = true;
    		}
            }
    	}
        if($status === true){
            Yii::$app->getSession()->setFlash('success', [
                'type' => 'success',
                'message' => 'สำเร็จ:: แก้ไขข้อมูลเรียบร้อยแล้ว',
            ]);
        }else{
            Yii::$app->getSession()->setFlash('error', [
                'type' => 'danger',
                'message' => 'เกิดข้อผิดพลาด!! ไม่สามารถแก้ไขข้อมูลได้',
            ]);
        }
    	return $this->redirect(['setting/index/credit']);
    }

    public function actionPlay_type()
    {
        $status = false;
    	$params = \Yii::$app->request->post();
    	if (!empty($params)  && !empty($params['SettingGameBenefitForm'])) {
            foreach ($params['SettingGameBenefitForm'] as $key=>$val){
    		$GameBenefit = SettingGameBenefit::find()->where(['code'=>$key])->one();
    		$GameBenefit->value = $val;
                $GameBenefit->save();
            }
            $status = true;
    	}
        if($status === true){
            Yii::$app->getSession()->setFlash('success', [
                'type' => 'success',
                'message' => 'สำเร็จ:: แก้ไขข้อมูลเรียบร้อยแล้ว',
            ]);
        }else{
            Yii::$app->getSession()->setFlash('error', [
                'type' => 'danger',
                'message' => 'เกิดข้อผิดพลาด!! ไม่สามารถแก้ไขข้อมูลได้',
            ]);
        }
    	return $this->redirect(['setting/index/play_type']);
    }

    public function actionUserAccount()
    {
        $modelUsers = new UsersForm();
        $User = UserSearch::find()
            ->where(['user.id'=>Yii::$app->user->identity->id])
            ->one();
            if(!empty($User)){
                return $this->render('update_account', [
                    'dataUser'=>$User,
                    'modelUsers'=>$modelUsers
                ]);
            }
    }

    public function actionSaveAccount()
    {
        $status = false;
        $UsersForm = \Yii::$app->request->post();
    	if(!empty($UsersForm) && !empty($UsersForm['UsersForm']) && !empty($UsersForm['UsersForm']['id'])){

            $User = User::find()
                ->where(['id'=>$UsersForm['UsersForm']['id']])
                ->one();
            if(!empty($User)){
                if(!empty($UsersForm['UsersForm']['change_password'])){
                    $User->setPassword($UsersForm['UsersForm']['change_password']);
                    $User->generateAuthKey();
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
        return $this->redirect(['setting/user-account']);
    }

    public function actionCreditMaster()
    {
        $status = false;
        $param = \Yii::$app->request->post();
    	if(!empty($param) && !empty($param['CreditMasterForm']) && (!empty($param['CreditMasterForm']['income']) || !empty($param['CreditMasterForm']['outcome']))){
            $model = new CreditTransectionSearch();
            $balance = (!empty($param['CreditMasterForm']['income']) ? $model->getCreditMasterBalance() + $param['CreditMasterForm']['income'] : $model->getCreditMasterBalance() - $param['CreditMasterForm']['outcome']);
            if($balance > 0){
                $model = new CreditTransection();
                $model->action_id = (!empty($param['CreditMasterForm']['income']) ? Constants::action_credit_master_top_up : Constants::action_credit_master_withdraw);
                $model->operator_id = \Yii::$app->user->identity->id;
                $model->reciver_id = Constants::user_system_id;
                $model->amount = (!empty($param['CreditMasterForm']['income']) ? $param['CreditMasterForm']['income'] : $param['CreditMasterForm']['outcome']);
                $model->balance = 0;
                $model->credit_master_balance = $balance;
                $model->create_at = date('Y-m-d H:i:s');
                $model->create_by = \Yii::$app->user->identity->id;
                $model->reason_action_id = (!empty($param['CreditMasterForm']['income']) ? Constants::reason_credit_top_up : Constants::reason_credit_withdraw);
                if($model->save()){
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
        return $this->redirect(['setting/index/credit_master']);
    }

    public function actionPlayBlackRed()
    {
        $status = false;
        $params = \Yii::$app->request->post();
        if(!empty($params) && !empty($params['PlayTypeForm'])){
            foreach ($params['PlayTypeForm'] as $key=>$val){
                $PlayType = PlayType::find()->where(['code'=>$key])->one();
                $PlayType->jackpot_per_unit = $val;
                $PlayType->save();
            }
            $status = true;
        }
        if($status === true){
            Yii::$app->getSession()->setFlash('success', [
                'type' => 'success',
                'message' => 'สำเร็จ:: แก้ไขข้อมูลเรียบร้อยแล้ว',
            ]);
        }else{
            Yii::$app->getSession()->setFlash('error', [
                'type' => 'danger',
                'message' => 'เกิดข้อผิดพลาด!! ไม่สามารถแก้ไขข้อมูลได้',
            ]);
        }
        return $this->redirect(['setting/index/play_black_red']);
    }
}
