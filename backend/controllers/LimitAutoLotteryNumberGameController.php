<?php

namespace backend\controllers;

use common\models\AuthRoles;
use common\models\PlayType;
use common\models\ThaiSharedGame;
use Yii;
use common\models\LimitAutoLotteryNumberGame;
use common\models\LimitAutoLotteryNumberGameSearch;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\ServerErrorHttpException;

/**
 * LimitAutoLotteryNumberGameController implements the CRUD actions for LimitAutoLotteryNumberGame model.
 */
class LimitAutoLotteryNumberGameController extends Controller
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
            if(!in_array('members', $arrRoles)){
                $this->layout = false;
                $this->redirect(Yii::$app->urlManager->createUrl(['user/logout']));
            }
        }
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
     * Lists all LimitAutoLotteryNumberGame models.
     * @return mixed
     */
    public function actionIndex($thaiSharedGameId)
    {
        $thaiSharedGame = ThaiSharedGame::find()->where(['id' => $thaiSharedGameId, 'limitAuto' => 1])->one();
        if (!$thaiSharedGame) {
            throw new NotFoundHttpException('Not Found');
        }
//        $gameLimit = ['หวยลาว', 'หวยลาว แบบมีส่วนลด', 'หวยลาวชุด 120', 'หวยลาวชุด 90', 'เวียดนาม/ฮานอย', 'หวยฮานอย VIP',
//            'เวียดนาม/ฮานอย (พิเศษ)', 'หวยเวียดนามชุด', 'หวยรัฐบาลไทย', 'หวยรัฐบาลไทย แบบมีส่วนลด', 'หวยออมสิน', 'หวย ธกส',
//            'หวยลาว จำปาสัก', 'หวยฮานอย 4D', 'หวยลาวทดแทน'];
//        if (!in_array($thaiSharedGame->title, $gameLimit, true)) {
//            throw new ServerErrorHttpException();
//        }
        if ($thaiSharedGame->title === 'หวยลาวชุด 120' || $thaiSharedGame->title === 'หวยลาวชุด 90' || $thaiSharedGame->title === 'หวยเวียดนามชุด') {
            throw new ServerErrorHttpException();
        }
        $searchModel = new LimitAutoLotteryNumberGameSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere(['thaiSharedGameId' => $thaiSharedGameId]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'thaiSharedGame' => $thaiSharedGame,
        ]);
    }

    /**
     * Displays a single LimitAutoLotteryNumberGame model.
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
     * Creates a new LimitAutoLotteryNumberGame model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($thaiSharedGameId)
    {
        $thaiSharedGame = ThaiSharedGame::find()->where(['id' => $thaiSharedGameId, 'limitAuto' => 1])->one();
        if (!$thaiSharedGame) {
            throw new NotFoundHttpException();
        }
//        $gameLimit = ['หวยลาว', 'หวยลาว แบบมีส่วนลด', 'หวยลาวชุด 120', 'หวยลาวชุด 90', 'เวียดนาม/ฮานอย', 'เวียดนาม/ฮานอย (พิเศษ)',
//            'หวยเวียดนามชุด', 'หวยฮานอย VIP', 'หวยรัฐบาลไทย', 'หวยรัฐบาลไทย แบบมีส่วนลด', 'หวยออมสิน', 'หวย ธกส', 'หวยลาว จำปาสัก',
//            'หวยฮานอย 4D', 'หวยลาวทดแทน'];
//        if (!in_array($thaiSharedGame->title, $gameLimit, true)) {
//            throw new ServerErrorHttpException();
//        }
        if ($thaiSharedGame->title === 'หวยลาวชุด 120' || $thaiSharedGame->title === 'หวยลาวชุด 90' || $thaiSharedGame->title === 'หวยเวียดนามชุด') {
            throw new ServerErrorHttpException();
        }
        $playTypeObj = PlayType::find()->where(['game_id' => $thaiSharedGame->gameId])->all();
        $playTypes = ArrayHelper::map($playTypeObj, 'id', 'title');
        $model = new LimitAutoLotteryNumberGame();
        $model->thaiSharedGameId = $thaiSharedGame->id;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'thaiSharedGameId' => $thaiSharedGameId]);
        }

        return $this->render('create', [
            'model' => $model,
            'playTypes' => $playTypes,
        ]);
    }

    /**
     * Updates an existing LimitAutoLotteryNumberGame model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $thaiSharedGame = ThaiSharedGame::find()->where(['id' => $model->thaiSharedGameId, 'limitAuto' => 1])->one();
        if (!$thaiSharedGame) {
            throw new NotFoundHttpException();
        }
//        $gameLimit = ['หวยลาว', 'หวยลาว แบบมีส่วนลด', 'หวยลาวชุด 120', 'หวยลาวชุด 90', 'เวียดนาม/ฮานอย', 'เวียดนาม/ฮานอย (พิเศษ)',
//            'หวยเวียดนามชุด', 'หวยฮานอย VIP', 'หวยรัฐบาลไทย', 'หวยรัฐบาลไทย แบบมีส่วนลด', 'หวยออมสิน', 'หวย ธกส', 'หวยลาว จำปาสัก',
//            'หวยฮานอย 4D', 'หวยลาวทดแทน'];
//        if (!in_array($thaiSharedGame->title, $gameLimit, true)) {
//            throw new ServerErrorHttpException();
//        }
        if ($thaiSharedGame->title === 'หวยลาวชุด 120' || $thaiSharedGame->title === 'หวยลาวชุด 90' || $thaiSharedGame->title === 'หวยเวียดนามชุด') {
            throw new ServerErrorHttpException();
        }
        $playTypeObj = PlayType::find()->where(['game_id' => $thaiSharedGame->gameId])->all();
        $playTypes = ArrayHelper::map($playTypeObj, 'id', 'title');
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'thaiSharedGameId' => $model->thaiSharedGameId]);
        }

        return $this->render('update', [
            'model' => $model,
            'playTypes' => $playTypes,
        ]);
    }

    /**
     * Deletes an existing LimitAutoLotteryNumberGame model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $thaiSharedGameId = $model->thaiSharedGameId;
        $model->delete();

        return $this->redirect(['index', 'thaiSharedGameId' => $thaiSharedGameId]);
    }

    /**
     * Finds the LimitAutoLotteryNumberGame model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return LimitAutoLotteryNumberGame the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = LimitAutoLotteryNumberGame::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
