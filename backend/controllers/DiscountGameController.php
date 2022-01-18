<?php

namespace backend\controllers;

use common\models\AuthRoles;
use common\models\PlayType;
use Yii;
use common\models\DiscountGame;
use common\models\DiscountGameSearch;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\ServerErrorHttpException;

/**
 * DiscountGameController implements the CRUD actions for DiscountGame model.
 */
class DiscountGameController extends Controller
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
            if(!in_array('discount-management', $arrRoles)){
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
     * Lists all DiscountGame models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DiscountGameSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere(['status' => 1]);
        $playTypes = PlayType::find()->joinWith('game')->all();
        $gameObjs = ArrayHelper::map($playTypes, 'id', function($model) {
            return $model['game']['title'].'-'.$model['title'];
        });

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'gameObjs' => $gameObjs,
        ]);
    }

    /**
     * Displays a single DiscountGame model.
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
     * Creates a new DiscountGame model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new DiscountGame();
        $model->status = 1;
        $playTypes = PlayType::find()->joinWith('game')->all();
        $gameObjs = ArrayHelper::map($playTypes, 'id', function($model) {
            return $model['game']['title'].'-'.$model['title'];
        });
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'gameObjs' => $gameObjs,
        ]);
    }

    /**
     * Updates an existing DiscountGame model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $playTypes = PlayType::find()->joinWith('game')->all();
        $gameObjs = ArrayHelper::map($playTypes, 'id', function($model) {
            return $model['game']['title'].'-'.$model['title'];
        });

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'gameObjs' => $gameObjs,
        ]);
    }

    public function actionCopy($id)
    {
        $model = $this->findModel($id);
        $playTypes = PlayType::find()->joinWith('game')->all();
        $gameObjs = ArrayHelper::map($playTypes, 'id', function($model) {
            return $model['game']['title'].'-'.$model['title'];
        });

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $disCountGame = new DiscountGame();
            $disCountGame->playTypeId = $model->playTypeId;
            $disCountGame->status = $model->status;
            $disCountGame->discount = $model->discount;
            if (!$disCountGame->save()) {
                throw new ServerErrorHttpException('Can not copy discount game');
            }
            return $this->redirect(['view', 'id' => $disCountGame->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'gameObjs' => $gameObjs,
        ]);
    }

    /**
     * Deletes an existing DiscountGame model.
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
     * Finds the DiscountGame model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DiscountGame the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DiscountGame::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
