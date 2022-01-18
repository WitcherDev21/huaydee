<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use common\models\AuthRoles;
use common\models\UserHasBankSearch;
use common\libs\Constants;
use common\models\Bank;
use common\models\BankSearch;
use common\models\UserHasBank;

/**
 * Site controller
 */
class TransectionsController extends Controller
{
    public $defaultAction = 'accountrefill';
    
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
            if(!in_array('transections', $arrRoles)){
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
    public function actionAccountrefill()
    {
        $searchModel = new UserHasBankSearch();
        if(!empty(Yii::$app->request->get()) && (!empty(Yii::$app->request->get()['id']) || !empty(Yii::$app->request->get()['type']))){
            if(Yii::$app->request->get()['id']=='create'){
                $modelBank = new BankSearch();
                $BankList = $modelBank->_getList();
                return $this->render('account-refill-create', [
                    'modelUserHasBankSearch'=>$searchModel,
                    'BankList'=>$BankList
                ]);
                
            }elseif(!empty(Yii::$app->request->get()['type']) && Yii::$app->request->get()['type']=='update'){
                $modelBank = new BankSearch();
                $BankList = $modelBank->_getList();
                
                $dataUserHasBank = UserHasBank::find()
                    ->where(['id'=>Yii::$app->request->get()['id']])
                    ->one();
                
                return $this->render('account-refill-update', [
                    'modelUserHasBankSearch'=>$searchModel,
                    'BankList'=>$BankList,
                    'dataUserHasBank'=>$dataUserHasBank
                ]);
                
                
            }elseif(Yii::$app->request->get()['id']=='save'){
                
                $status = false;
                $params = \Yii::$app->request->post();
                if(!empty($params) && !empty($params['UserHasBankSearch'])){
                    if(!empty($params['UserHasBankSearch']['id'])){
                        $modelUserHasBank = UserHasBank::find()
                            ->where(['id'=>$params['UserHasBankSearch']['id']])
                            ->one();
                        $modelUserHasBank->update_at = date('Y-m-d H:i:s');
                        $modelUserHasBank->update_by = Yii::$app->user->getIdentity()->id;
                    }else{
                        $modelUserHasBank = new UserHasBank();
                        $modelUserHasBank->create_at = date('Y-m-d H:i:s');
                        $modelUserHasBank->create_by = Yii::$app->user->getIdentity()->id;
                    }
                    $modelUserHasBank->user_id = Constants::user_system_id;
                    $modelUserHasBank->bank_id = $params['UserHasBankSearch']['bank_id'];
                    $modelUserHasBank->bank_account_name = $params['UserHasBankSearch']['bank_account_name'];
                    $modelUserHasBank->bank_account_no = $params['UserHasBankSearch']['bank_account_no'];
                    $modelUserHasBank->description = $params['UserHasBankSearch']['description'];
                    $modelUserHasBank->status = Constants::status_active;
                    $modelUserHasBank->version = 1;
                    if($modelUserHasBank->save()){
                        $status = true;
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
                return $this->redirect(['transections/accountrefill']);
            }elseif(!empty(Yii::$app->request->get()['type']) && Yii::$app->request->get()['type']=='delete'){
                $status = false;
                if(!empty(Yii::$app->request->get()['id'])){
                    $UserHasBank = UserHasBank::find()
                        ->where(['id'=>Yii::$app->request->get()['id']])
                        ->one();
                    if(!empty($UserHasBank)){
                        $UserHasBank->scenario = 'bank_block';
                        $UserHasBank->update_at = date('Y-m-d H:i:s');
                        $UserHasBank->update_by = Yii::$app->user->identity->id;
                        $UserHasBank->status = Constants::status_inactive;
                        if($UserHasBank->save()){
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
                return $this->redirect(['transections/accountrefill']);
            }elseif(!empty(Yii::$app->request->get()['type']) && Yii::$app->request->get()['type']=='withhold'){
                $status = false;
                if(!empty(Yii::$app->request->get()['id'])){
                    $UserHasBank = UserHasBank::find()
                        ->where(['id'=>Yii::$app->request->get()['id']])
                        ->one();
                    if(!empty($UserHasBank)){
                        $UserHasBank->scenario = 'bank_block';
                        $UserHasBank->update_at = date('Y-m-d H:i:s');
                        $UserHasBank->update_by = Yii::$app->user->identity->id;
                        $UserHasBank->status = Constants::status_withhold;
                        if($UserHasBank->save()){
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
                return $this->redirect(['transections/accountrefill']);
            }elseif(!empty(Yii::$app->request->get()['type']) && Yii::$app->request->get()['type']=='active'){
                $status = false;
                if(!empty(Yii::$app->request->get()['id'])){
                    $UserHasBank = UserHasBank::find()
                        ->where(['id'=>Yii::$app->request->get()['id']])
                        ->one();
                    if(!empty($UserHasBank)){
                        $UserHasBank->scenario = 'bank_block';
                        $UserHasBank->update_at = date('Y-m-d H:i:s');
                        $UserHasBank->update_by = Yii::$app->user->identity->id;
                        $UserHasBank->status = Constants::status_active;
                        if($UserHasBank->save()){
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
                return $this->redirect(['transections/accountrefill']);
            }
        }else{
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            $bankList = Bank::find()->select('id, title as name')->where(['status'=>Constants::status_active])->asArray()->all();
            return $this->render('account-refill', [
                'searchModel'=>$searchModel,
                'dataProvider'=>$dataProvider,
                'bankList'=>$bankList
            ]);
        }
    }

    
}
