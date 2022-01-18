<?php

namespace backend\controllers;

use common\libs\Constants;
use Yii;
use common\models\Pusher;
use common\models\PusherSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\ServerErrorHttpException;

/**
 * PusherController implements the CRUD actions for Pusher model.
 */
class PusherController extends Controller
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
     * Lists all Pusher models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PusherSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Pusher model.
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

    /**
     * Creates a new Pusher model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Pusher();
//        $pusher = new \Pusher\Pusher(Constants::app_key, Constants::app_secret, Constants::app_id, array('cluster' => Constants::app_cluster, 'useTLS' => true));
        if (Yii::$app->request->isPost) {
            if(isset($_FILES['Pusher']['name']['image']) && $_FILES['Pusher']['name']['image'] !== '') {
                $model->scenario = 'image';
            }else{
                $model->scenario = 'message';
            }
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                if (isset($_FILES['Pusher']['name']['image']) && $_FILES['Pusher']['name']['image'] !== '') {
                    $model->image = $model->upload($model, 'image');
                }
//            $messages['title'] = $model->title;
//            $messages['message'] = $model->message;
//            $pusher->trigger('my-channel', 'my-event', $messages);
                if (!$model->save()) {
                    throw new ServerErrorHttpException('Can not Save');
                }
                return $this->redirect(['index']);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Pusher model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
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
                if (!$model->save()) {
                    throw new ServerErrorHttpException('Can not update');
                }
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Pusher model.
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
     * Finds the Pusher model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Pusher the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Pusher::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
