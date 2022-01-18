<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use common\libs\Constants;
use common\models\News;
use common\models\AuthRoles;

/**
 * Site controller
 */
class PageController extends Controller
{
    public $defaultAction = 'news';

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
            if(!in_array('page', $arrRoles)){
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
    public function actionNews()
    {
        $model = new News();
        $News = News::find()->all();

        return $this->render('news',[
            'model'=>$model,
            'News'=>$News,
        ]);
    }
    
    public function actionNewssave()
    {
        $status = false;
    	$params = \Yii::$app->request->post();
    	if(!empty($params) && !empty($params['News'])){
            foreach ($params['News'] as $key=>$val){
                $News = News::find()->where(['id'=>$key])->one();
                $News->message = $val['message'];
                $News->update_at = date('Y-m-d H:i:s');
                $News->update_by = \Yii::$app->user->identity->id;
                $News->status = empty($val['status'])?Constants::status_inactive:Constants::status_active;
                $News->save();
            }
            $status = true;
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
    	return $this->redirect(['page/news']);
    }
    
}
