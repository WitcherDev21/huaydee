<?php

namespace backend\controllers;

use common\models\AuthRoles;
use common\models\Games;
use common\models\PlayTypeGourp;
use Yii;
use common\models\PlayType;
use common\models\PlayTypeSearch;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\ServerErrorHttpException;

/**
 * PlayTypeController implements the CRUD actions for PlayType model.
 */
class PlayTypeController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        $identity = Yii::$app->user->getIdentity();
        if(empty($identity)){
            $this->layout = false;
            $this->redirect(Yii::$app->urlManager->createUrl(['user/login']));
        }else{
            $modelAuthRoles = new AuthRoles();
            $arrRoles = $modelAuthRoles->_getRoles($identity->auth_roles_id);
            if(!in_array('play-type-game', $arrRoles)){
                throw new ServerErrorHttpException('คุณไม่มีสิทธิ์ในการเข้าใช้งาน');
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
     * Lists all PlayType models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PlayTypeSearch();

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize = 100;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PlayType model.
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
     * Creates a new PlayType model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PlayType();
        $playTypeGroups = PlayTypeGourp::find()->all();
        $titlePlayTypeGroups = ArrayHelper::map($playTypeGroups, 'id', 'title');
        $games = Games::find()->all();
        $titleGames = ArrayHelper::map($games, 'id', 'title');

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->create_by = Yii::$app->user->id;
            $model->create_at = date('Y-m-d H:i:s');
            if(!$model->save()) {
                throw new ServerErrorHttpException('can not save');
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'titlePlayTypeGroups' => $titlePlayTypeGroups,
            'titleGames' => $titleGames,
        ]);
    }

    /**
     * Updates an existing PlayType model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $playTypeGroups = PlayTypeGourp::find()->all();
        $titlePlayTypeGroups = ArrayHelper::map($playTypeGroups, 'id', 'title');
        $games = Games::find()->all();
        $titleGames = ArrayHelper::map($games, 'id', 'title');

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->update_by = Yii::$app->user->id;
            $model->update_at = date('Y-m-d H:i:s');
            if(!$model->save()) {
                throw new ServerErrorHttpException('can not update');
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'titlePlayTypeGroups' => $titlePlayTypeGroups,
            'titleGames' => $titleGames,
        ]);
    }

    /**
     * Deletes an existing PlayType model.
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
     * Finds the PlayType model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PlayType the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PlayType::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}