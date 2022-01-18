<?php

namespace backend\controllers;

use common\libs\Constants;
use common\models\AuthRoles;
use common\models\LogLimitLotteryNumberGame;
use common\models\PlayType;
use common\models\ThaiSharedGame;
use Yii;
use common\models\LimitLotteryNumberGame;
use common\models\LimitLotteryNumberGameSearch;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\ServerErrorHttpException;

/**
 * LimitLotteryNumberGameController implements the CRUD actions for LimitLotteryNumberGame model.
 */
class LimitLotteryNumberGameController extends Controller
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
     * Lists all LimitLotteryNumberGame models.
     * @return mixed
     */
    public function actionIndex($thaiSharedGameId)
    {
        $thaiSharedGame = ThaiSharedGame::find()->where(['id' => $thaiSharedGameId])->one();
        if (!$thaiSharedGame) {
            throw new NotFoundHttpException();
        }
//        $gameLimit = ['หวยลาว', 'หวยลาว แบบมีส่วนลด', 'หวยลาวชุด 120', 'หวยลาวชุด 90', 'เวียดนาม/ฮานอย', 'หวยฮานอย VIP',
//            'เวียดนาม/ฮานอย (พิเศษ)', 'หวยเวียดนามชุด', 'หวยรัฐบาลไทย', 'หวยรัฐบาลไทย แบบมีส่วนลด', 'หวยออมสิน', 'หวย ธกส',
//            'หวยลาว จำปาสัก', 'หวยฮานอย 4D', 'หวยลาวทดแทน'];
//        if (!in_array($thaiSharedGame->title, $gameLimit, true)) {
//            throw new ServerErrorHttpException();
//        }
        $searchModel = new LimitLotteryNumberGameSearch();
        $searchModel->thaiSharedGameId = $thaiSharedGameId;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere(['isLimitByUser' => 0]);
        $thaiSharedGame = ThaiSharedGame::findOne($thaiSharedGameId);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'thaiSharedGame' => $thaiSharedGame,
        ]);
    }

    public function actionNumberList($thaiSharedGameId)
    {
        $thaiSharedGame = ThaiSharedGame::find()->where(['id' => $thaiSharedGameId])->one();
        if (!$thaiSharedGame) {
            throw new NotFoundHttpException();
        }
//        $gameLimit = ['หวยลาว', 'หวยลาว แบบมีส่วนลด', 'หวยลาวชุด 120', 'หวยลาวชุด 90', 'เวียดนาม/ฮานอย', 'เวียดนาม/ฮานอย (พิเศษ)',
//            'หวยฮานอย VIP', 'หวยเวียดนามชุด', 'หวยรัฐบาลไทย', 'หวยรัฐบาลไทย แบบมีส่วนลด', 'หวยออมสิน', 'หวย ธกส', 'หวยลาว จำปาสัก',
//            'หวยฮานอย 4D', 'หวยลาวทดแทน'];
//        if (!in_array($thaiSharedGame->title, $gameLimit, true)) {
//            throw new ServerErrorHttpException();
//        }
        $searchModel = new LimitLotteryNumberGameSearch();
        $searchModel->thaiSharedGameId = $thaiSharedGameId;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere(['isLimitByUser' => 1]);
        $thaiSharedGame = ThaiSharedGame::findOne($thaiSharedGameId);

        return $this->render('number-list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'thaiSharedGame' => $thaiSharedGame,
        ]);
    }

    /**
     * Displays a single LimitLotteryNumberGame model.
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
     * Creates a new LimitLotteryNumberGame model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($thaiSharedGameId)
    {
        $thaiSharedGame = ThaiSharedGame::find()->where(['id' => $thaiSharedGameId])->one();
        if (!$thaiSharedGame) {
            throw new NotFoundHttpException();
        }
//        $gameLimit = ['หวยลาว', 'หวยลาว แบบมีส่วนลด', 'หวยลาวชุด 120', 'หวยลาวชุด 90', 'เวียดนาม/ฮานอย', 'เวียดนาม/ฮานอย (พิเศษ)',
//            'หวยเวียดนามชุด', 'หวยฮานอย VIP', 'หวยรัฐบาลไทย', 'หวยรัฐบาลไทย แบบมีส่วนลด', 'หวยออมสิน', 'หวย ธกส', 'หวยลาว จำปาสัก',
//            'หวยฮานอย 4D', 'หวยลาวทดแทน'];
//        if (!in_array($thaiSharedGame->title, $gameLimit, true)) {
//            throw new ServerErrorHttpException();
//        }
        $model = new LimitLotteryNumberGame();
        $threetod = PlayType::find()->where(['game_id' => $thaiSharedGame->gameId, 'code' => 'three_tod'])->one();
        $threetop = PlayType::find()->where(['game_id' => $thaiSharedGame->gameId, 'code' => 'three_top'])->one();
        if (Yii::$app->request->post('LimitLotteryNumberGame')['limits']) {
            $lottteryLimits = Yii::$app->request->post('LimitLotteryNumberGame')['limits'];
            $transaction = Yii::$app->db->beginTransaction();
            try {
                foreach ($lottteryLimits as $key => $lottteryLimit) {
                    $model = new LimitLotteryNumberGame();
                    $model->thaiSharedGameId = $thaiSharedGameId;
                    if ($thaiSharedGame->title === 'หวยลาวชุด 120' || $thaiSharedGame->title === 'หวยลาวชุด 90' || $thaiSharedGame->title === 'หวยเวียดนามชุด') {
                        $model->scenario = 'lotteryLaoSet';
                        $model->number = $lottteryLimit['number'];
                        $model->totalSetNumber = $lottteryLimit['totalSetNumber'];
                    }else{
                        $model->playTypeId = $lottteryLimit['playTypeId'];
                        $model->numberFrom = $lottteryLimit['numberFrom'];
                        $model->numberTo = $lottteryLimit['numberTo'];
                        $model->jackPotPerUnit = $lottteryLimit['jackPotPerUnit'];
                    }
                    $numberLength = strlen((string)$model->numberFrom);
                    if ($model->validate()) {
                        if ($model->numberFrom !== '' && $model->numberTo !== '' && $model->numberFrom !== null && $model->numberTo !== null) {
                            $numberLength = strlen((string)$model->numberFrom);
                            if ($numberLength === 3) {
                                $format = '%03d';
                            } elseif ($numberLength === 2) {
                                $format = '%02d';
                            } else {
                                $format = null;
                            }
                            foreach (range($model->numberFrom, $model->numberTo) as $number) {
                                if ($format) {
                                    $number = sprintf($format, $number);
                                }
                                $limitLotteryNumberGame = new LimitLotteryNumberGame();
                                $limitLotteryNumberGame->number = $number;
                                $limitLotteryNumberGame->thaiSharedGameId = $thaiSharedGameId;
                                $limitLotteryNumberGame->playTypeId = $model->playTypeId;
                                $limitLotteryNumberGame->jackPotPerUnit = $model->jackPotPerUnit;
                                if (!$limitLotteryNumberGame->save(false)) {
                                    throw new ServerErrorHttpException('Can not save limit lottery number game');
                                }
                            }
                        } elseif ($numberLength === 3 && !empty($model->numberFrom) && $model->numberFrom != '') {
                            if (Constants::PLAY_TYPE_THAI_SHARED_THREE_TOP !== (int)$model->playTypeId &&
                                Constants::PLAY_TYPE_THAI_SHARED_THREE_TOP_VIETNAM_VIP !== (int)$model->playTypeId &&
                                Constants::PLAY_TYPE_LOTTERY_GAME !== (int)$model->playTypeId &&
                                Constants::PLAY_TYPE_LOTTERY_DISCOUNT_GAME !== (int)$model->playTypeId &&
                                Constants::PLAY_TYPE_THREE_TOP_LAOS_CHAMPASAK_LOTTERY_GAME !== (int)$model->playTypeId &&
                                Constants::PLAY_TYPE_THREE_TOP_GSB_GAME !== (int)$model->playTypeId &&
                                Constants::PLAY_TYPE_THREE_TOP_VITE_NAME_GAME !== (int)$model->playTypeId &&
                                Constants::PLAY_TYPE_THREE_TOP_LAOS_SUBSTITUTE !== (int)$model->playTypeId) {
                                $threetods = PlayType::instance()->getfindThreeTod($model->numberFrom);
                                foreach ($threetods as $threetod) {
                                    $limitLotteryNumberGame = new LimitLotteryNumberGame();
                                    $limitLotteryNumberGame->number = $threetod;
                                    $limitLotteryNumberGame->thaiSharedGameId = $thaiSharedGameId;
                                    $limitLotteryNumberGame->playTypeId = $model->playTypeId;
                                    $limitLotteryNumberGame->jackPotPerUnit = $model->jackPotPerUnit;
                                    if (!$limitLotteryNumberGame->save(false)) {
                                        throw new ServerErrorHttpException('Can not save limit lottery number game');
                                    }
                                }
                            }else{
                                $limitLotteryNumberGame = new LimitLotteryNumberGame();
                                $limitLotteryNumberGame->number = $model->numberFrom;
                                $limitLotteryNumberGame->thaiSharedGameId = $thaiSharedGameId;
                                $limitLotteryNumberGame->playTypeId = $model->playTypeId;
                                $limitLotteryNumberGame->jackPotPerUnit = $model->jackPotPerUnit;
                                if (!$limitLotteryNumberGame->save(false)) {
                                    throw new ServerErrorHttpException('Can not save limit lottery number game');
                                }
                            }
                        }  else {
                            if ($thaiSharedGame->title === 'หวยลาวชุด 120' || $thaiSharedGame->title === 'หวยลาวชุด 90' || $thaiSharedGame->title === 'หวยเวียดนามชุด') {
                                $playType = PlayType::find()->where(['game_id' => $thaiSharedGame->gameId, 'code' => 'four_dt'])->one();
                                $model->playTypeId = $playType->id;
                            } else {
                                if ($model->numberFrom !== '') {
                                    $model->number = $model->numberFrom;
                                } elseif ($model->numberTo !== '') {
                                    $model->number = $model->numberTo;
                                }
                            }
                            $model->thaiSharedGameId = $thaiSharedGameId;
                            if (!$model->save()) {
                                throw new ServerErrorHttpException('Can not save limit lottery number game');
                            }
                        }
                    }else {
                        Yii::$app->getSession()->setFlash('alert', [
                            'body' => 'กรุณากรอกข้อมูลให้ถูกต้อง',
                            'options' => ['class' => 'alert-danger']
                        ]);
                        $transaction->rollBack();
                    }
                }
                if ($model->validate()) {
                    $transaction->commit();
                    return $this->redirect(['index',
                        'thaiSharedGameId' => $model->thaiSharedGameId
                    ]);
                }

            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            }

        }
        $playTypeObj = PlayType::find()->where(['game_id' => $thaiSharedGame->gameId])->all();
        $playTypes = ArrayHelper::map($playTypeObj, 'id', 'title');
        foreach ($playTypeObj as $playType) {
            $maxLengths[$playType->id] = ['data-length' => $playType->group->number_length];
        }
        return $this->render('create', [
            'model' => $model,
            'playTypes' => $playTypes,
            'maxLengths' => $maxLengths,
            'thaiSharedGame' => $thaiSharedGame,
        ]);
    }


    public function actionCreateNumberList($thaiSharedGameId)
    {
        $thaiSharedGame = ThaiSharedGame::find()->where(['id' => $thaiSharedGameId])->one();
        if (!$thaiSharedGame) {
            throw new NotFoundHttpException();
        }
//        $gameLimit = ['หวยลาว', 'หวยลาว แบบมีส่วนลด', 'หวยลาวชุด 120', 'หวยลาวชุด 90', 'เวียดนาม/ฮานอย', 'เวียดนาม/ฮานอย (พิเศษ)',
//            'หวยเวียดนามชุด', 'หวยฮานอย VIP', 'หวยรัฐบาลไทย', 'หวยรัฐบาลไทย แบบมีส่วนลด', 'หวยออมสิน', 'หวย ธกส', 'หวยลาว จำปาสัก',
//            'หวยฮานอย 4D', 'หวยลาวทดแทน'];
//        if (!in_array($thaiSharedGame->title, $gameLimit, true)) {
//            throw new ServerErrorHttpException();
//        }
        $model = new LimitLotteryNumberGame();
        $model->thaiSharedGameId = $thaiSharedGameId;
        $threetod = PlayType::find()->where(['game_id' => $thaiSharedGame->gameId, 'code' => 'three_tod'])->one();
        $threetop = PlayType::find()->where(['game_id' => $thaiSharedGame->gameId, 'code' => 'three_top'])->one();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->numberFrom !== '' && $model->numberTo !== '') {
                $numberLength = strlen((string)$model->numberFrom);
                if ($numberLength === 3) {
                    $format = '%03d';
                } elseif ($numberLength === 2) {
                    $format = '%02d';
                } else {
                    $format = null;
                }
                foreach (range($model->numberFrom , $model->numberTo) as $number) {
                    if ($format) {
                        $number = sprintf($format, $number);
                    }
                    $limitLotteryNumberGame = new LimitLotteryNumberGame();
                    $limitLotteryNumberGame->number = $number;
                    $limitLotteryNumberGame->thaiSharedGameId = $thaiSharedGameId;
                    $limitLotteryNumberGame->playTypeId = $model->playTypeId;
                    $limitLotteryNumberGame->jackPotPerUnit = $model->jackPotPerUnit;
                    $limitLotteryNumberGame->isLimitByUser = 1;
                    $limitLotteryNumberGame->amountLimit = $model->amountLimit;
                    if (!$limitLotteryNumberGame->save(false)) {
                        throw new ServerErrorHttpException('Can not save limit lottery number game');
                    }
                }
            } elseif (intval($threetod->id) === intval($model->playTypeId) && $model->numberFrom !== '') {
                $threetods = $threetod->getfindThreeTod($model->numberFrom);
                foreach ($threetods as $threetod) {
                    $limitLotteryNumberGame = new LimitLotteryNumberGame();
                    $limitLotteryNumberGame->number = $threetod;
                    $limitLotteryNumberGame->thaiSharedGameId = $thaiSharedGameId;
                    $limitLotteryNumberGame->playTypeId = $model->playTypeId;
                    $limitLotteryNumberGame->jackPotPerUnit = $model->jackPotPerUnit;
                    $limitLotteryNumberGame->isLimitByUser = 1;
                    $limitLotteryNumberGame->amountLimit = $model->amountLimit;
                    if (!$limitLotteryNumberGame->save(false)) {
                        throw new ServerErrorHttpException('Can not save limit lottery number game');
                    }
                }
            } elseif (intval($threetop->id) === intval($model->playTypeId) && $model->numberFrom !== ''){
                $threetops = $threetop->getfindThreeTod($model->numberFrom);
                foreach ($threetops as $threetop) {
                    $limitLotteryNumberGame = new LimitLotteryNumberGame();
                    $limitLotteryNumberGame->number = $threetop;
                    $limitLotteryNumberGame->thaiSharedGameId = $thaiSharedGameId;
                    $limitLotteryNumberGame->playTypeId = $model->playTypeId;
                    $limitLotteryNumberGame->jackPotPerUnit = $model->jackPotPerUnit;
                    $limitLotteryNumberGame->isLimitByUser = 1;
                    $limitLotteryNumberGame->amountLimit = $model->amountLimit;
                    if (!$limitLotteryNumberGame->save(false)) {
                        throw new ServerErrorHttpException('Can not save limit lottery number game');
                    }
                }
            }
            return $this->redirect(['number-list',
                'thaiSharedGameId' => $model->thaiSharedGameId
            ]);
        }
        $playTypeObj = PlayType::find()->where(['game_id' => $thaiSharedGame->gameId])->all();
        $playTypes = ArrayHelper::map($playTypeObj, 'id', 'title');
        foreach ($playTypeObj as $playType) {
            $maxLengths[$playType->id] = ['data-length' => $playType->group->number_length];
        }
        return $this->render('_form_number_list', [
            'model' => $model,
            'playTypes' => $playTypes,
            'maxLengths' => $maxLengths,
            'thaiSharedGame' => $thaiSharedGame,
        ]);
    }
    /**
     * Updates an existing LimitLotteryNumberGame model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $thaiSharedGame = ThaiSharedGame::find()->where(['id' => $model->thaiSharedGameId])->one();
        if (!$thaiSharedGame) {
            throw new NotFoundHttpException();
        }
        $playTypeObj = PlayType::find()->where(['game_id' => $thaiSharedGame->gameId])->all();
        $playTypes = ArrayHelper::map($playTypeObj, 'id', 'title');
        foreach ($playTypeObj as $playType) {
            $maxLengths[$playType->id] = ['data-length' => $playType->group->number_length];
        }
        if ($model->load(Yii::$app->request->post()) && $this->actionLog($id) && $model->save()) {
            return $this->redirect(['index',
                'thaiSharedGameId' => $model->thaiSharedGameId
            ]);
        }

        return $this->render('update', [
            'model' => $model,
            'playTypes' => $playTypes,
            'maxLengths' => $maxLengths,
            'thaiSharedGame' => $thaiSharedGame,
        ]);
    }

    /**
     * Deletes an existing LimitLotteryNumberGame model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $thaiSharedGame = ThaiSharedGame::findOne($model->thaiSharedGameId);
        if (!$thaiSharedGame) {
            throw new NotFoundHttpException();
        }
        $model->delete();

        return $this->redirect([
            'index','thaiSharedGameId' => $model->thaiSharedGameId
        ]);
    }

    public function actionLog($id)
    {
        $limitLotteryNumberGame = $this->findModel($id);
        $logLimitLotteryNumberGame = new LogLimitLotteryNumberGame();
        $logLimitLotteryNumberGame->thaiSharedGameId = $limitLotteryNumberGame->thaiSharedGameId;
        $logLimitLotteryNumberGame->playTypeId = $limitLotteryNumberGame->playTypeId;
        $logLimitLotteryNumberGame->number = $limitLotteryNumberGame->number;
        $logLimitLotteryNumberGame->jackPotPerUnit = $limitLotteryNumberGame->jackPotPerUnit;
        $logLimitLotteryNumberGame->createdBy = $limitLotteryNumberGame->createdBy;
        $logLimitLotteryNumberGame->updatedBy = $limitLotteryNumberGame->updatedBy;
        $logLimitLotteryNumberGame->createdAt = $limitLotteryNumberGame->createdAt;
        $logLimitLotteryNumberGame->updatedAt = $limitLotteryNumberGame->updatedAt;
        if ($limitLotteryNumberGame->isLimitByUser === '1') {
            $logLimitLotteryNumberGame->amountLimit = $limitLotteryNumberGame->amountLimit;
        } else {
            $logLimitLotteryNumberGame->amountLimit = 0;
        }
        if (!$logLimitLotteryNumberGame->save()) {
            return false;
        }
        return true;
    }

    /**
     * Finds the LimitLotteryNumberGame model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return LimitLotteryNumberGame the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = LimitLotteryNumberGame::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
