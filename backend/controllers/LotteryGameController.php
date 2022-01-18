<?php

namespace backend\controllers;

use common\models\AuthRoles;
use common\models\Games;
use Yii;
use common\models\LotteryGame;
use common\models\LotteryGameSearch;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\ServerErrorHttpException;

/**
 * LotteryGameController implements the CRUD actions for LotteryGame model.
 */
class LotteryGameController extends Controller
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
            if(!in_array('lottery-game', $arrRoles)){
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
     * Lists all LotteryGame models.
     * @return mixed
     */
    public function actionIndex()
    {
        $identity = Yii::$app->user->getIdentity();
        $modelAuthRoles = new AuthRoles();
        $arrRoles = $modelAuthRoles->_getRoles($identity->auth_roles_id);
        if(!in_array('lottery-game', $arrRoles)){
            throw new ServerErrorHttpException('คุณไม่มีสิทธิ์ในการดูรายการหวยหุ้น');
        }
        $searchModel = new LotteryGameSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $gameObj = Games::find()->where(['status' => 1])->andWhere(['like', 'title', 'หวยรัฐ'])->all();
        $games = ArrayHelper::map($gameObj, 'id', 'title');

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'arrRoles' => $arrRoles,
            'games' => $games,
        ]);
    }

    /**
     * Displays a single LotteryGame model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $identity = Yii::$app->user->getIdentity();
        $modelAuthRoles = new AuthRoles();
        $arrRoles = $modelAuthRoles->_getRoles($identity->auth_roles_id);
        if(!in_array('lottery-game', $arrRoles)){
            throw new ServerErrorHttpException('คุณไม่มีสิทธิ์ในการดูรายการหวยหุ้น');
        }
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new LotteryGame model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $identity = Yii::$app->user->getIdentity();
        $modelAuthRoles = new AuthRoles();
        $arrRoles = $modelAuthRoles->_getRoles($identity->auth_roles_id);
        if(!in_array('create-lottery-game', $arrRoles)){
            throw new ServerErrorHttpException('คุณไม่มีสิทธิ์ในการดูรายการหวยหุ้น');
        }
        $model = new LotteryGame();
        $model->setScenario('create');
        $gameObj = Games::find()->where(['status' => 1])->andWhere(['like', 'title', 'หวยหุ้น'])->all();
        $games = ArrayHelper::map($gameObj, 'id', 'title');
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'games' => $games,
        ]);
    }

    /**
     * Updates an existing LotteryGame model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $identity = Yii::$app->user->getIdentity();
        $modelAuthRoles = new AuthRoles();
        $arrRoles = $modelAuthRoles->_getRoles($identity->auth_roles_id);
        if(!in_array('update-lottery-game', $arrRoles)){
            throw new ServerErrorHttpException('คุณไม่มีสิทธิ์ในการดูรายการหวยหุ้น');
        }
        $model = $this->findModel($id);
        $gameObj = Games::find()->where(['status' => 1])->andWhere(['like', 'title', 'หวยหุ้น'])->all();
        $games = ArrayHelper::map($gameObj, 'id', 'title');
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'games' => $games,
        ]);
    }

    public function actionCancel($id)
    {
       // todo
    }


    /**
     * Finds the LotteryGame model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return LotteryGame the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = LotteryGame::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
