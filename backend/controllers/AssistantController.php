<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use common\libs\Constants;
use common\models\UserSearch;
use common\models\AuthRoles;
use common\models\User;
use common\models\UsersForm;

/**
 * Site controller
 */
class AssistantController extends Controller
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
            if(!in_array('assistant', $arrRoles)){
                $this->layout = false;
                $this->redirect(Yii::$app->urlManager->createUrl(['user/logout']));
            }
        }
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['GET'],
                ],
            ],
            'access'=> [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ]
            ]
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
        $dataProvider = $searchModel->searchAssistant(Yii::$app->request->get());
        
        $AuthRolesList = AuthRoles::find()->select('id, name')
            ->where([
                'is_active'=>Constants::status_active
            ])
            ->asArray()->all();
        $RolesList = ArrayHelper::map($AuthRolesList,'id','name');
        
        return $this->render('list',[
            'searchModel'=>$searchModel,
            'dataProvider'=>$dataProvider,
            'RolesList'=>$RolesList
        ]);
    }
   
    public function actionChangestatus()
    {
        $status = false;
    	$params = \Yii::$app->request->get();
    	if($params){
            $User = UserSearch::find()
                ->where(['id'=>$params['id']])
                ->one();
            if(!empty($User)){
                $User->user_status = ($params['type'] == 'active' ? Constants::user_status_active : Constants::user_status_withhold);
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
    	return $this->redirect(['assistant/list']);
        
    }
    
    public function actionUpdate()
    {
    	$params = \Yii::$app->request->get();
        if(!empty($params) || !empty($params['id'])){
            
            $dataUser = User::find()
                ->where(['id'=>$params['id']])
                ->one();
            if(!empty($dataUser)){
                $modelAuthRoles = new AuthRoles();
                $modelUsers = new UsersForm();
                $RolesList = $modelAuthRoles->_getList();

                return $this->render('update',[
                    'modelUsers'=>$modelUsers,
                    'RolesList'=>$RolesList,
                    'dataUser'=>$dataUser
                ]);
            }else{
                return $this->redirect(['assistant/list']);
            }
        }else{
            return $this->redirect(['assistant/list']);
        }
    }
    
    public function actionSave()
    {
        $status = false;
    	$params = Yii::$app->request->post();
    	$paramsId = Yii::$app->request->get();
        if(!empty($params) && !empty($paramsId) && !empty($paramsId['id'])){
            $modelUser = User::find()->where(['id'=>$paramsId['id']])->one();
            if(!empty($modelUser)){
                if(!empty($params['UsersForm']['change_password'])){
                    $modelUser->setPassword($params['UsersForm']['change_password']);
                    $modelUser->generateAuthKey();
                }
                $modelUser->email = $params['UsersForm']['email'];
                $modelUser->registration_ip = \Yii::$app->request->userIP;
                $modelUser->tel = $params['UsersForm']['tel'];
                $modelUser->auth_roles_id = $params['UsersForm']['user_roles_id'];
                if($modelUser->save()){
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
    	return $this->redirect(['assistant/list']);
    }
    
}
