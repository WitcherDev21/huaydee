<?php

namespace backend\controllers;

use Yii;
use common\models\PostCreditTransectionSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use common\libs\Constants;
use common\models\AuthRoles;

/**
 * PostCreditTransectionController implements the CRUD actions for PostCreditTransection model.
 */
class PostCreditAgentController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        $identity = \Yii::$app->user->getIdentity();
        if(empty($identity)){
            $this->layout = false;
            $this->redirect(Yii::$app->urlManager->createUrl(['user/login']));
        }else{
            $modelAuthRoles = new AuthRoles();
            $arrRoles = $modelAuthRoles->_getRoles($identity->auth_roles_id);
            if(!in_array('post-credit-agent', $arrRoles)){
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
        return $this->render('index');
    }
    public function actionListCurrent()
    {
    	$layout = \Yii::$app->request->get('layout');
    	if(!empty($layout)){
    		$this->layout = $layout;
    	}
    	 
    	$searchModel = new PostCreditTransectionSearch();
    	$searchModel->create_at = date('Y-m-d');
//    	$searchModel->agent = null;
    	$searchModel->auth_roles_id = Constants::auth_roles_agent;
    	$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
    
    	return $this->render('_list', [
    			'searchModel' => $searchModel,
    			'dataProvider' => $dataProvider,
    	]);
    }
    
    public function actionListHistory()
    {
    	$layout = \Yii::$app->request->get('layout');
    	if(!empty($layout)){
    		$this->layout = $layout;
    	}
    	$searchModel = new PostCreditTransectionSearch();
    	$searchModel->auth_roles_id = Constants::auth_roles_admin;
    	$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
    
    	return $this->render('_list', [
    			'searchModel' => $searchModel,
    			'dataProvider' => $dataProvider,
    	]);
    }
    
//    public function actionCreateTopup()
//    {
//    	$user_id = \Yii::$app->user->identity->id;
//    	$model = new PostCreditTransection();
//    	$layout = \Yii::$app->request->get('layout');
//    	if(!empty($layout)){
//    		$this->layout = $layout;
//    	}
//    
//    	$params = Yii::$app->request->post();
//    	if ($params) {
//    		$model->load($params);
//    		$dataForm = $params['PostCreditTransection'];
//    		 
//    		$model->poster_id = $user_id;
//    		$model->action_id = Constants::action_credit_top_up;
//    		$model->user_has_bank_id = $dataForm['user_has_bank_id'];
//    		$model->user_has_bank_version = UserHasBankSearch::getCurrentVersion($dataForm['user_has_bank_id']);
//    		$model->status = Constants::status_processing;
//    		$model->amount = $dataForm['amount'];
//    		$model->create_at = date('Y-m-d H:i:s',time());
//    		$model->create_by = $user_id;
//    
//    		if($model->save()){
//    			Yii::$app->session->setFlash('success', "Successfully");
//    			return $this->redirect(['post-credit-transection/index']);
//    		}else{
//    			Yii::$app->session->setFlash('warning', "Warning");
//    		}
//    	}
//    
//    	return $this->render('create_topup', [
//    			'model' => $model,
//    	]);
//    }
//    public function actionCreateWithdraw()
//    {
//    	$user_id = \Yii::$app->user->identity->id;
//    	$model = new PostCreditTransection();
//    	$layout = \Yii::$app->request->get('layout');
//    	if(!empty($layout)){
//    		$this->layout = $layout;
//    	}
//    
//    	$params = Yii::$app->request->post();
//    	if ($params) {
//    		$model->load($params);
//    		$dataForm = $params['PostCreditTransection'];
//    		 
//    		$model->poster_id = $user_id;
//    		$model->action_id = Constants::action_credit_withdraw;
//    		$model->user_has_bank_id = $dataForm['user_has_bank_id'];
//    		$model->user_has_bank_version = UserHasBankSearch::getCurrentVersion($dataForm['user_has_bank_id']);
//    		$model->status = Constants::status_processing;
//    		$model->amount = $dataForm['amount'];
//    		$model->create_at = date('Y-m-d H:i:s',time());
//    		$model->create_by = $user_id;
//    
//    		if($model->save()){
//    			Yii::$app->session->setFlash('success', "Successfully");
//    			return $this->redirect(['post-credit-transection/index']);
//    		}else{
//    			Yii::$app->session->setFlash('warning', "Warning");
//    		}
//    	}
//    
//    	return $this->render('create_withdraw', [
//    			'model' => $model,
//    	]);
//    }
//    public function actionPopup($id)
//    {
//    	$layout = \Yii::$app->request->get('layout');
//    	if(!empty($layout)){
//    		$this->layout = $layout;
//    	}
//    	return $this->render('popup', [
//    			'model' => $this->findModel($id),
//    	]);
//    }
//    public function actionPopupUpdateStatus(){
//    	$admin_id = \Yii::$app->user->identity->id;
//    	$params = \Yii::$app->request->post();
//    	$result = true;
//    	if(!empty($params)){
//    		$transaction = \Yii::$app->db->beginTransaction();
//    		
//    		$requestModel = PostCreditTransection::findOne(['id'=>$params['id']]);
//    		
//    		//ต้องเป็น status ที่ รออนุมัติแล้วเท่านั้น
//    		if(!in_array($requestModel->status,[Constants::status_waitting])){
//    			$transaction->rollBack();
//    			return false;
//    		}
//    		    		
//    		$requestModel->status = $params['status'];
//    		$result = $requestModel->save();
//    	
//    		if($result && $requestModel->status == Constants::status_approve){
//    			
//    			$reason_id = '';
//    			if($requestModel->action_id == Constants::action_credit_top_up){
//    				$reason_id = Constants::reason_credit_top_up;
//    			}else if($requestModel->action_id == Constants::action_credit_withdraw){
//    				$reason_id = Constants::reason_credit_withdraw;
//    			}    			
//    			$result = Credit::creditWalk($requestModel->action_id, $requestModel->poster_id, $admin_id, $reason_id, $requestModel->amount);	
//    			
//    		}
//    	}    	
//    	if($result){
//    		$transaction->commit();
//    	}else{
//    		$transaction->rollBack();
//    	}
//    	return $result;
//    }
//    /**
//     * Displays a single PostCreditTransection model.
//     * @param integer $id
//     * @return mixed
//     * @throws NotFoundHttpException if the model cannot be found
//     */
//    public function actionView($id)
//    {
//    	$layout = \Yii::$app->request->get('layout');
//    	if(!empty($layout)){
//    		$this->layout = $layout;
//    	}
//        return $this->render('view', [
//            'model' => $this->findModel($id),
//        ]);
//    }
//
//    /**
//     * Creates a new PostCreditTransection model.
//     * If creation is successful, the browser will be redirected to the 'view' page.
//     * @return mixed
//     */
//    public function actionCreate()
//    {
//        $model = new PostCreditTransection();
//
//        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            return $this->redirect(['view', 'id' => $model->id]);
//        }
//
//        return $this->render('create', [
//            'model' => $model,
//        ]);
//    }
//
//    /**
//     * Updates an existing PostCreditTransection model.
//     * If update is successful, the browser will be redirected to the 'view' page.
//     * @param integer $id
//     * @return mixed
//     * @throws NotFoundHttpException if the model cannot be found
//     */
//    public function actionUpdate($id)
//    {
//        $model = $this->findModel($id);
//
//        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            return $this->redirect(['view', 'id' => $model->id]);
//        }
//
//        return $this->render('update', [
//            'model' => $model,
//        ]);
//    }
//
//    /**
//     * Deletes an existing PostCreditTransection model.
//     * If deletion is successful, the browser will be redirected to the 'index' page.
//     * @param integer $id
//     * @return mixed
//     * @throws NotFoundHttpException if the model cannot be found
//     */
//    public function actionDelete($id)
//    {
//        $this->findModel($id)->delete();
//
//        return $this->redirect(['index']);
//    }
//
//    /**
//     * Finds the PostCreditTransection model based on its primary key value.
//     * If the model is not found, a 404 HTTP exception will be thrown.
//     * @param integer $id
//     * @return PostCreditTransection the loaded model
//     * @throws NotFoundHttpException if the model cannot be found
//     */
//    protected function findModel($id)
//    {
//        if (($model = PostCreditTransection::findOne($id)) !== null) {
//            return $model;
//        }
//
//        throw new NotFoundHttpException('The requested page does not exist.');
//    }
}
