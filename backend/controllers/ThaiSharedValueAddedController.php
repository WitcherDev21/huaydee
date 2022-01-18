<?php

namespace backend\controllers;

use common\libs\Constants;
use common\models\AuthRoles;
use common\models\Games;
use common\models\PlayType;
use Yii;
use common\models\ThaiSharedValueAdded;
use common\models\ThaiSharedValueAddedSearch;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\ThaiSharedGame;

/**
 * ThaiSharedValueAddedController implements the CRUD actions for ThaiSharedValueAdded model.
 */
class ThaiSharedValueAddedController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        $identity = Yii::$app->user->getIdentity();
        if (empty($identity)) {
            $this->layout = false;
            $this->redirect(Yii::$app->urlManager->createUrl(['user/login']));
        } else {
            $modelAuthRoles = new AuthRoles();
            $arrRoles = $modelAuthRoles->_getRoles($identity->auth_roles_id);
            if (!in_array('thai-shared-value-added', $arrRoles)) {
                $this->layout = false;
                $this->redirect(Yii::$app->urlManager->createUrl(['user/logout']));
            }
        }
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST']
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
     * Lists all ThaiSharedValueAdded models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ThaiSharedValueAddedSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new ThaiSharedValueAdded model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($thaiSharedGameId)
    {
        $thaiSharedGame = ThaiSharedGame::findOne($thaiSharedGameId);
        if (!$thaiSharedGame) {
            throw new NotFoundHttpException();
        }
        $model = new ThaiSharedValueAdded();
        $model->thaiSharedGameId = $thaiSharedGame->id;
        $playTypes = PlayType::find()->where([
            'game_id' => $thaiSharedGame->gameId
        ])->all();
        $playTypesObj = ArrayHelper::map($playTypes, 'id',  function($model) {
            $game = Games::find()->where(['id' => $model->game_id])->one();
            return $model['title'].'-'.$game['title'];
        });
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
            'playTypesObj' => $playTypesObj,
        ]);
    }

    /**
     * Deletes an existing ThaiSharedValueAdded model.
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
     * Finds the ThaiSharedValueAdded model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ThaiSharedValueAdded the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ThaiSharedValueAdded::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
