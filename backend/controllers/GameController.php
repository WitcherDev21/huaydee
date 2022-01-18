<?php
namespace backend\controllers;

use common\models\GameSearch;
use common\models\AuthRoles;
use common\models\Games;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

/**
 * Created by PhpStorm.
 * User: topte
 * Date: 9/25/2018
 * Time: 8:27 PM
 */

class GameController extends Controller
{
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
        $identity = Yii::$app->user->getIdentity();
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

    public function actionIndex()
    {
        $searchModel = new GameSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index',[
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionCreate()
    {
        $model = new Games();
        $model->create_by = Yii::$app->user->id;
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->image = $model->upload($model,'image');
            $model->gameAuthKey = Yii::$app->security->generateRandomString(12);
            $model->create_at = date('Y-m-d H:i:s');
            if (!$model->save()) {
                throw new ServerErrorHttpException('Can not Save');
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);

    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $request = Yii::$app->request;
        if ($request->post()) {
            if ($model->image && file_exists($_FILES['Games']['tmp_name']['image'])) {
                $model->deleteFile('image');
            }
            if ($model->load($request->post()) && $model->validate()) {
                $model->image = $model->upload($model, 'image');
                $model->update_by = Yii::$app->user->id;
                $model->update_at = date('Y-m-d H:i:s');
                if (!$model->save()) {
                    throw new ServerErrorHttpException('Can not update');
                }
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        return $this->render('update', [
            'model' => $model,
        ]);

    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    protected function findModel($id)
    {
        if (($model = Games::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}