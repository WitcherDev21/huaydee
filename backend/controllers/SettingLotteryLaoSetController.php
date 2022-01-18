<?php

namespace backend\controllers;

use common\libs\Constants;
use common\models\Games;
use Yii;
use common\models\SettingLotteryLaoSet;
use common\models\SettingLotteryLaoSetSearch;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SettingLotteryLaoSetController implements the CRUD actions for SettingLotteryLaoSet model.
 */
class SettingLotteryLaoSetController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all SettingLotteryLaoSet models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SettingLotteryLaoSetSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SettingLotteryLaoSet model.
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
     * Creates a new SettingLotteryLaoSet model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SettingLotteryLaoSet();
        $games = Games::find()->where(['id' => [Constants::LOTTERYLAOGAME, Constants::LOTTERYLAODISCOUNTGAME]])->all();
        $gameObjs = ArrayHelper::map($games, 'id', 'title');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
            'gameObjs' => $gameObjs,
        ]);
    }

    /**
     * Updates an existing SettingLotteryLaoSet model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $games = Games::find()->where(['id' => [Constants::LOTTERYLAOGAME, Constants::LOTTERYLAODISCOUNTGAME]])->all();
        $gameObjs = ArrayHelper::map($games, 'id', 'title');
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
            'gameObjs' => $gameObjs,
        ]);
    }

    /**
     * Deletes an existing SettingLotteryLaoSet model.
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
     * Finds the SettingLotteryLaoSet model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SettingLotteryLaoSet the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SettingLotteryLaoSet::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
