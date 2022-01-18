<?php

namespace backend\controllers;

use common\libs\Constants;
use common\models\AuthRoles;
use common\models\Commission;
use common\models\Credit;
use common\models\LotteryShowNumberResultWin;
use common\models\PlayType;
use common\models\Queue;
use common\models\SettingCommissionCredit;
use common\models\ThaiSharedAnswerGame;
use common\models\ThaiSharedAnswerGameForm;
use common\models\ThaiSharedGame;
use common\models\ThaiSharedGameChit;
use common\models\ThaiSharedGameChitDetail;
use common\models\ThaiSharedGameChitDetailSearch;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\ServerErrorHttpException;

/**
 * LotteryGameAnswerChitController implements the CRUD actions for LotteryGameChitAnswer model.
 */
class ThaiSharedAnswerGameController extends Controller
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
            if (!in_array('answer-shared-game', $arrRoles)) {
                $this->layout = false;
                $this->redirect(Yii::$app->urlManager->createUrl(['user/logout']));
            }
        }
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'tricker' => ['POST']
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
     * Creates a new LotteryGameChitAnswer model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $identity = Yii::$app->user->getIdentity();
        $modelAuthRoles = new AuthRoles();
        $arrRoles = $modelAuthRoles->_getRoles($identity->auth_roles_id);
        if (!in_array('answer-shared-game', $arrRoles)) {
            throw new ServerErrorHttpException('คุณไม่มีสิทธิ์ในการสร้างเฉลยหวยหุ้น');
        }
        $thaiSharedAnswerGame = ThaiSharedAnswerGame::find()->where(['thaiSharedGameId' => $id])->count();
        if ($thaiSharedAnswerGame) {
            throw new ServerErrorHttpException('หวยหุ้นนี้ถูกสร้างแล้วไม่สามารสร้างเฉลยซ้ำได้');
        }
        $model = new ThaiSharedAnswerGameForm();
        $thaiSharedGame = ThaiSharedGame::findOne(['id' => $id]);
        if ($thaiSharedGame->status !== 1) {
            throw new ServerErrorHttpException('หวยหุ้นนี้ถูกเฉลยไปแล้วไม่สามารถเฉลยซ้ำได้');
        }
        if ($thaiSharedGame->startDate >= date('Y-m-d H:i:s') || $thaiSharedGame->endDate > date('Y-m-d H:i:s')) {
            throw new ServerErrorHttpException('ไม่สามารถออกผลได้เนื่องจากยังไม่สิ้นสุดเวลาหรือยังไม่สิ้นสุดเวลา');
        }
        if ($thaiSharedGame->gameId === Constants::LOTTERYGAMEDISCOUNT || $thaiSharedGame->gameId === Constants::LOTTERYGAME) {
            $model->setScenario('lotteryGame');
        } elseif ($thaiSharedGame->gameId === Constants::LOTTERYLAOGAME || $thaiSharedGame->gameId === Constants::LOTTERYLAODISCOUNTGAME || $thaiSharedGame->gameId === Constants::LOTTERY_VIETNAM_SET) {
            $model->setScenario('lottery-lao-set');
        }
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($thaiSharedGame->gameId === Constants::LOTTERYLAODISCOUNTGAME || $thaiSharedGame->gameId === Constants::LOTTERYLAOGAME || $thaiSharedGame->gameId === Constants::LOTTERY_VIETNAM_SET) {
                    $answers = [
                        'four_dt' => $this->findGenerateNumberByPlayType($model->four_dt, 'four_dt'),
                        'four_tod' => $this->findGenerateNumberByPlayType($model->four_dt, 'four_tod'),
                        'three_top' => $this->findGenerateNumberByPlayType($model->four_dt, 'three_top'),
                        'three_tod' => $this->findGenerateNumberByPlayType($model->four_dt, 'three_tod'),
                        'two_ft' => $this->findGenerateNumberByPlayType($model->four_dt, 'two_ft'),
                        'two_bk' => $this->findGenerateNumberByPlayType($model->four_dt, 'two_bk'),
                        'three_ft' => $this->findGenerateNumberByPlayType($model->four_dt, 'three_ft'),
                    ];
                } else {
                    if ($thaiSharedGame->gameId === Constants::LOTTERYGAMEDISCOUNT || $thaiSharedGame->gameId === Constants::LOTTERYGAME) {
                        $model->three_top = substr($model->firstResult, 3, 3);
                        $lotteryShowNumberResultWin = new LotteryShowNumberResultWin();
                        $lotteryShowNumberResultWin->number = $model->firstResult;
                        $lotteryShowNumberResultWin->thaiSharedGameId = $thaiSharedGame->id;
                        if (!$lotteryShowNumberResultWin->save()) {
                            throw new ServerErrorHttpException('Can not save Lottery Number Result Win');
                        }
                    }
                    $answers = [
                        'three_top' => $model->three_top,
                        'three_tod' => $this->findThreeTod($model->three_top),
                        'two_top' => $this->findTwoTop($model->three_top),
                        'two_under' => $model->two_under,
                        'run_top' => $this->findRunTop($model->three_top),
                        'run_under' => $this->findRunUnder($model->two_under),
                    ];
                    if ($thaiSharedGame->gameId === Constants::LOTTERYGAMEDISCOUNT || $thaiSharedGame->gameId === Constants::LOTTERYGAME) {
                        $answerLotteryGames = [
                            'three_top2' => [
                                $model->three_top2_1,
                                $model->three_top2_2
                            ],
                            'three_und2' => [
                                $model->three_under2_1,
                                $model->three_under2_2
                            ],
                        ];
                        $answers = array_merge($answers, $answerLotteryGames);
                    }
                }
                foreach ($answers as $key => $answer) {
                    $playType = PlayType::find()->where(['game_id' => $thaiSharedGame->gameId, 'code' => $key])->one();
                    if (!$playType) {
                        throw new ServerErrorHttpException('Not Found PlayType');
                    }
                    if (is_array($answer)) {
                        foreach ($answer as $number) {
                            $thaiSharedAnswerGame = new ThaiSharedAnswerGame();
                            $thaiSharedAnswerGame->thaiSharedGameId = $id;
                            $thaiSharedAnswerGame->playTypeId = $playType->id;
                            $thaiSharedAnswerGame->number = $number;
                            $thaiSharedAnswerGame->createdBy = Yii::$app->user->id;
                            if (!$thaiSharedAnswerGame->save()) {
                                throw new ServerErrorHttpException('Can not Save');
                            }
                        }
                    } else {
                        $thaiSharedAnswerGame = new ThaiSharedAnswerGame();
                        $thaiSharedAnswerGame->thaiSharedGameId = $id;
                        $thaiSharedAnswerGame->playTypeId = $playType->id;
                        $thaiSharedAnswerGame->number = $answer;
                        $thaiSharedAnswerGame->createdBy = Yii::$app->user->id;
                        if (!$thaiSharedAnswerGame->save()) {
                            throw new ServerErrorHttpException('Can not Save');
                        }
                    }
                }
                if ($thaiSharedGame->gameId === Constants::GSB_THAISHARD_GAME ||
                    $thaiSharedGame->gameId === Constants::BACC_THAISHARD_GAME ||
                    $thaiSharedGame->gameId === Constants::LAOS_CHAMPASAK_LOTTERY_GAME ||
                    $thaiSharedGame->gameId === Constants::LOTTERYRESERVEGAME) {
                    $thaiSharedGame->result = $model->result !== '' ? $model->result : null;
                    if (!$thaiSharedGame->save()) {
                        throw new ServerErrorHttpException('can not save result thai shared');
                    }
                }
                $transaction->commit();
                return $this->redirect(['view', 'id' => $id]);
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            }
        }

        return $this->render('create', [
            'model' => $model,
            'thaiSharedGame' => $thaiSharedGame,
        ]);
    }

    /**
     * Updates an existing LotteryGameChitAnswer model.
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
        if (!in_array('update-answer-shared-game', $arrRoles)) {
            throw new ServerErrorHttpException('คุณไม่มีสิทธิ์ในการแก้ไขเฉลยหวยหุ้น');
        }
        $thaiSharedGame = ThaiSharedGame::find()->where(['id' => $id])->one();
        if ($thaiSharedGame->status !== 1) {
            throw new ServerErrorHttpException('หวยหุ้นนี้ถูกเฉลยไปแล้วไม่สามารถแก้ไขได้');
        }
        if ($thaiSharedGame->startDate >= date('Y-m-d H:i:s') || $thaiSharedGame->endDate > date('Y-m-d H:i:s')) {
            throw new ServerErrorHttpException('ไม่สามารถออกผลได้เนื่องจากยังไม่สิ้นสุดเวลาหรือยังไม่สิ้นสุดเวลา');
        }
        $model = new ThaiSharedAnswerGameForm();
        if ($thaiSharedGame->gameId === Constants::LOTTERYLAOGAME || $thaiSharedGame->gameId === Constants::LOTTERYLAODISCOUNTGAME || $thaiSharedGame->gameId === Constants::LOTTERY_VIETNAM_SET) {
            $answers = [
                'four_dt',
            ];
            $model->setScenario('lottery-lao-set');
        } else if ($thaiSharedGame->gameId === Constants::THAISHARED || $thaiSharedGame->gameId === Constants::VIETNAMVIP) {
            $answers = [
                'three_top',
                'two_under',
            ];
        } else if ($thaiSharedGame->gameId === Constants::GSB_THAISHARD_GAME || $thaiSharedGame->gameId === Constants::BACC_THAISHARD_GAME ||
            $thaiSharedGame->gameId === Constants::LAOS_CHAMPASAK_LOTTERY_GAME || $thaiSharedGame->gameId === Constants::LOTTERYRESERVEGAME) {
            $answers = [
                'three_top',
                'two_under',
                'result',
            ];
        } else if ($thaiSharedGame->gameId === Constants::LOTTERYGAMEDISCOUNT || $thaiSharedGame->gameId === Constants::LOTTERYGAME) {
            $model->setScenario('lotteryGame');
            $answers = [
                'three_top',
                'two_under',
                'three_top2',
                'three_und2',
            ];
        }
        foreach ($answers as $answer) {
            if ($answer !== 'result') {
                $playType = PlayType::find()->where(['game_id' => $thaiSharedGame->gameId, 'code' => $answer])->one();
                if (!$playType) {
                    throw new ServerErrorHttpException('Not Found PlayType');
                }
            }
            if (!Yii::$app->request->post()) {
                if ($thaiSharedGame->gameId === Constants::LOTTERYGAMEDISCOUNT || $thaiSharedGame->gameId === Constants::LOTTERYGAME && $answer === 'three_top') {
                    $lotteryShowNumberResultWin = LotteryShowNumberResultWin::find()->where(['thaiSharedGameId' => $id])->one();
                    if (!$lotteryShowNumberResultWin) {
                        throw new ServerErrorHttpException('Not Found LotteryShowNumberResultWin');
                    }
                    $model->firstResult = $lotteryShowNumberResultWin->number;
                }
                if ($answer === 'three_top2') {
                    $thaiSharedAnswerGames = ThaiSharedAnswerGame::find()->where(['thaiSharedGameId' => $id, 'playTypeId' => $playType->id])->limit(2)->all();
                    if (!$thaiSharedAnswerGames) {
                        throw new ServerErrorHttpException('Not Found LotteryGameChitAnswer');
                    }
                    $model->three_top2_1 = $thaiSharedAnswerGames[0]->number;
                    $model->three_top2_2 = $thaiSharedAnswerGames[1]->number;
                } elseif ($answer === 'three_und2') {
                    $thaiSharedAnswerGames = ThaiSharedAnswerGame::find()->where(['thaiSharedGameId' => $id, 'playTypeId' => $playType->id])->limit(2)->all();
                    if (!$thaiSharedAnswerGames) {
                        throw new ServerErrorHttpException('Not Found LotteryGameChitAnswer');
                    }
                    $model->three_under2_1 = $thaiSharedAnswerGames[0]->number;
                    $model->three_under2_2 = $thaiSharedAnswerGames[1]->number;
                } elseif ($answer === 'result') {
                    $model->result = $thaiSharedGame->result;
                } else {
                    $thaiSharedAnswerGames = ThaiSharedAnswerGame::find()->where(['thaiSharedGameId' => $id, 'playTypeId' => $playType->id])->one();
                    $model->$answer = $thaiSharedAnswerGames->number;
                    if (!$thaiSharedAnswerGames) {
                        throw new ServerErrorHttpException('Not Found LotteryGameChitAnswer');
                    }
                }
            }
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($thaiSharedGame->gameId === Constants::LOTTERYLAODISCOUNTGAME || $thaiSharedGame->gameId === Constants::LOTTERYLAOGAME || $thaiSharedGame->gameId === Constants::LOTTERY_VIETNAM_SET) {
                    $answers = [
                        'four_dt' => $this->findGenerateNumberByPlayType($model->four_dt, 'four_dt'),
                        'four_tod' => $this->findGenerateNumberByPlayType($model->four_dt, 'four_tod'),
                        'three_top' => $this->findGenerateNumberByPlayType($model->four_dt, 'three_top'),
                        'three_tod' => $this->findGenerateNumberByPlayType($model->four_dt, 'three_tod'),
                        'two_ft' => $this->findGenerateNumberByPlayType($model->four_dt, 'two_ft'),
                        'two_bk' => $this->findGenerateNumberByPlayType($model->four_dt, 'two_bk'),
                        'three_ft' => $this->findGenerateNumberByPlayType($model->four_dt, 'three_ft'),
                    ];
                } else {
                    if ($thaiSharedGame->gameId === Constants::LOTTERYGAMEDISCOUNT || $thaiSharedGame->gameId === Constants::LOTTERYGAME) {
                        $model->three_top = substr($model->firstResult, 3, 3);
                        $lotteryShowNumberResultWin = LotteryShowNumberResultWin::find()->where(['thaiSharedGameId' => $thaiSharedGame->id])->one();
                        $lotteryShowNumberResultWin->number = $model->firstResult;
                        if (!$lotteryShowNumberResultWin->save()) {
                            throw new ServerErrorHttpException('Can not update lotteryShowNumberResultWin');
                        }
                    }
                    $answers = [
                        'three_top' => $model->three_top,
                        'three_tod' => $this->findThreeTod($model->three_top),
                        'two_top' => $this->findTwoTop($model->three_top),
                        'two_under' => $model->two_under,
                        'run_top' => $this->findRunTop($model->three_top),
                        'run_under' => $this->findRunUnder($model->two_under),
                    ];
                    if ($thaiSharedGame->gameId === Constants::LOTTERYGAMEDISCOUNT || $thaiSharedGame->gameId === Constants::LOTTERYGAME) {
                        $answerLotteryGames = [
                            'three_top2' => [
                                $model->three_top2_1,
                                $model->three_top2_2
                            ],
                            'three_und2' => [
                                $model->three_under2_1,
                                $model->three_under2_2
                            ],
                        ];
                        $answers = array_merge($answers, $answerLotteryGames);
                    }
                }
                ThaiSharedAnswerGame::deleteAll(['thaiSharedGameId' => $id]);
                foreach ($answers as $key => $answer) {
                    $playType = PlayType::find()->where(['game_id' => $thaiSharedGame->gameId, 'code' => $key])->one();
                    if (!$playType) {
                        throw new ServerErrorHttpException('Not Found PlayType');
                    }
                    if (is_array($answer)) {
                        foreach ($answer as $number) {
                            $thaiSharedAnswerGame = new ThaiSharedAnswerGame();
                            $thaiSharedAnswerGame->thaiSharedGameId = $id;
                            $thaiSharedAnswerGame->playTypeId = $playType->id;
                            $thaiSharedAnswerGame->number = $number;
                            $thaiSharedAnswerGame->createdBy = Yii::$app->user->id;
                            if (!$thaiSharedAnswerGame->save()) {
                                throw new ServerErrorHttpException('Can not Save');
                            }
                        }
                    } else {
                        $thaiSharedAnswerGame = new ThaiSharedAnswerGame();
                        $thaiSharedAnswerGame->thaiSharedGameId = $id;
                        $thaiSharedAnswerGame->playTypeId = $playType->id;
                        $thaiSharedAnswerGame->number = $answer;
                        $thaiSharedAnswerGame->createdBy = Yii::$app->user->id;
                        if (!$thaiSharedAnswerGame->save()) {
                            throw new ServerErrorHttpException('Can not Save');
                        }
                    }
                }
                if ($thaiSharedGame->gameId === Constants::GSB_THAISHARD_GAME ||
                    $thaiSharedGame->gameId === Constants::BACC_THAISHARD_GAME ||
                    $thaiSharedGame->gameId === Constants::LAOS_CHAMPASAK_LOTTERY_GAME ||
                    $thaiSharedGame->gameId === Constants::LOTTERYRESERVEGAME) {
                    $thaiSharedGame->result = $model->result;
                    if (!$thaiSharedGame->save()) {
                        throw new ServerErrorHttpException('can not save result thai shared');
                    }
                }
                $transaction->commit();
                return $this->redirect(['view', 'id' => $thaiSharedGame->id]);
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            }
        }

        return $this->render('update', [
            'model' => $model,
            'thaiSharedGame' => $thaiSharedGame
        ]);
    }

    public function actionView($id)
    {
        $identity = Yii::$app->user->getIdentity();
        $modelAuthRoles = new AuthRoles();
        $arrRoles = $modelAuthRoles->_getRoles($identity->auth_roles_id);
        if (!in_array('thai-shared-game', $arrRoles)) {
            throw new ServerErrorHttpException('คุณไม่มีสิทธิ์ในการดูรายการหวยหุ้น');
        }
        $lotteryGame = ThaiSharedGame::findOne($id);
        if (!$lotteryGame) {
            throw new NotFoundHttpException('Not Found Game');
        }
        return $this->render('view', [
            'model' => $lotteryGame,
            'arrRoles' => $arrRoles,
        ]);
    }


    public function actionTricker($id)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $identity = Yii::$app->user->getIdentity();
        $modelAuthRoles = new AuthRoles();
        $arrRoles = $modelAuthRoles->_getRoles($identity->auth_roles_id);
        if (!in_array('thai-shared-game-answer', $arrRoles)) {
            return ['message' => 'คุณไม่มีสิทธิ์ในการออกผลเฉลย'];
        }
        $thaiSharedAnswerGames = ThaiSharedAnswerGame::find()->where(['thaiSharedGameId' => $id])->groupBy('playTypeId, number')->all();
        $thaiSharedGame = ThaiSharedGame::find()->where(['id' => $id])->one();
        if ($thaiSharedGame->startDate >= date('Y-m-d H:i:s') || $thaiSharedGame->endDate > date('Y-m-d H:i:s')) {
            return ['message' => 'ไม่สามารถออกผลได้เนื่องจากยังไม่สิ้นสุดเวลาหรือยังไม่สิ้นสุดเวลา'];
        }
        if (!$thaiSharedAnswerGames) {
            return ['message' => 'lotteryGameChitAnswers Not Found'];
        }
        $transaction = Yii::$app->db->beginTransaction();
        $thaiSharedGameChits = ThaiSharedGameChit::find()->where([
            'thaiSharedGameId' => $id,
            'status' => Constants::status_playing
        ])->all();
        try {
            $thaiSharedGame->status = Constants::status_finish_show_result;
            $thaiSharedGame->updatedBy = Yii::$app->user->id;
            $thaiSharedGame->updateAt = date('Y-m-d H:i:s');
            if (!$thaiSharedGame->save()) {
                throw new ServerErrorHttpException('Can not save Thai Shared Game');
            }
            $thaiSharedGameChitIds = [];
            $commissionUserAgentPlay = [];
            if (!$thaiSharedGameChits){
                return ['message' => 'ไม่มีรอบที่ต้องออกผลแล้ว'];
            }
            $userIds = [];
            foreach ($thaiSharedGameChits as $thaiSharedGameChit) {
                $thaiSharedGameChitIds[] = $thaiSharedGameChit->id;
                $thaiSharedGameChit->status = Constants::status_finish_show_result;
                if (!$thaiSharedGameChit->save()) {
                    throw new ServerErrorHttpException('Can not save Thai Shared Game Chit');
                }
                if (!empty($thaiSharedGameChit->user->agent)) {
                    if (!isset($commissionUserAgentPlay[$thaiSharedGameChit->user->agent])) {
                        $commissionUserAgentPlay[$thaiSharedGameChit->user->agent] = 0;
                    }
                    $commissionUserAgentPlay[$thaiSharedGameChit->user->agent] += $thaiSharedGameChit->totalDiscount > 0 ?
                        $thaiSharedGameChit->totalDiscount : $thaiSharedGameChit->totalAmount;
                }
                $userIds[] = $thaiSharedGameChit->userId;
            }
            if (count($commissionUserAgentPlay) > 0) {
                $settingCommissionCredit = SettingCommissionCredit::find()->where(['type' => Constants::setting_commission_credit_invite])->one();
                $percent = 0;
                if (!empty($settingCommissionCredit)) {
                    $percent = empty($settingCommissionCredit->value) ? 0 : $settingCommissionCredit->value;
                }
                foreach ($commissionUserAgentPlay as $agentId => $commissionUserAgent) {
                    $commissionAmount = $commissionUserAgent * $percent / 100;
                    $remark = Constants::$reason_credit[Constants::reason_credit_commission_in] . ' แทงหวยหุ้น ' . $thaiSharedGame->title . '/' . date('d/m/Y', strtotime($thaiSharedGame->endDate)) . ' #' . $thaiSharedGameChit->getOrder();
                    Commission::commissionWalk(Constants::action_commission_top_up, $agentId, Yii::$app->user->id, Constants::reason_commission_top_up, $commissionAmount, $remark);
                }
            }
            foreach ($thaiSharedAnswerGames as $key => $thaiSharedAnswerGame) {
                /* @var $thaiSharedAnswerGame ThaiSharedAnswerGame */
                $thaiSharedAnswerGame->updatedBy = Yii::$app->user->id;
                $thaiSharedAnswerGame->updatedAt = date('Y-m-d H:i:s');
                if (!$thaiSharedAnswerGame->save()) {
                    throw new ServerErrorHttpException('Can not update thai shared answer game');
                }
                $thaiSharedGameChitDetails = ThaiSharedGameChitDetail::find()->where([
                    'number' => $thaiSharedAnswerGame->number,
                    'playTypeId' => $thaiSharedAnswerGame->playTypeId,
                    'thaiSharedGameChitId' => $thaiSharedGameChitIds
                ])->all();
                foreach ($thaiSharedGameChitDetails as $thaiSharedGameChitDetail) {
                    $thaiSharedGameChitDetail->flag_result = 1;
                    $jackpotPerUnit = $thaiSharedGameChitDetail->jackPotPerUnit;
                    $thaiSharedGameChitDetail->win_credit = $thaiSharedGameChitDetail->amount * $jackpotPerUnit;
                    $remark = Constants::$reason_credit[Constants::reason_credit_bet_win] . ' ' . $thaiSharedGame->title . '/ ' . date('d/m/Y', strtotime($thaiSharedGame->endDate)) . '/ ประเภทที่ถูกรางวัล: ' . $thaiSharedAnswerGame->playType->title . ' /#' . $thaiSharedGameChitDetail->thaiSharedGameChit->getOrder();
                    $creditWalk = Credit::creditWalk(Constants::action_credit_top_up, $thaiSharedGameChitDetail->userId, Yii::$app->user->id, Constants::reason_credit_bet_win, $thaiSharedGameChitDetail->win_credit, $remark);
                    if (!$creditWalk) {
                        throw new ServerErrorHttpException('Not save creditWalk');
                    }
                    if (!$thaiSharedGameChitDetail->save()) {
                        throw new ServerErrorHttpException('Can not save lottery game chit detail');
                    }
                }
            }
            ThaiSharedGameChitDetail::updateAll(['flag_result' => 0, 'win_credit' => 0],
                ['AND',
                    'flag_result = 0',
                    ['IN', 'thaiSharedGameChitId', $thaiSharedGameChitIds]
                ]);
            Queue::updateAll(['status' => Constants::status_inactive], ['gameId' => $thaiSharedGame->gameId, 'userId' => $userIds]);
            $transaction->commit();
            return ['message' => 'success'];
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    public function actionTrickerLotteryLaoSet($id)
    {
        $identity = Yii::$app->user->getIdentity();
        $modelAuthRoles = new AuthRoles();
        $arrRoles = $modelAuthRoles->_getRoles($identity->auth_roles_id);
        if (!in_array('thai-shared-game-answer', $arrRoles)) {
            return 'คุณไม่มีสิทธิ์ในการออกผลเฉลย';
        }
        $thaiSharedAnswerGames = ThaiSharedAnswerGame::find()->where(['thaiSharedGameId' => $id])->
        joinWith('playType')->orderBy(PlayType::tableName() . '.jackpot_per_unit DESC')->groupBy('playTypeId, number')->all();
        $thaiSharedGame = ThaiSharedGame::find()->where(['id' => $id])->one();
        if ($thaiSharedGame->startDate >= date('Y-m-d H:i:s') || $thaiSharedGame->endDate > date('Y-m-d H:i:s')) {
            return 'ไม่สามารถออกผลได้เนื่องจากยังไม่สิ้นสุดเวลาหรือยังไม่สิ้นสุดเวลา';
        }
        if ($thaiSharedGame->status !== 1) {
            return 'ไม่สามารถออกผลได้เนื่องจากเฉลยไปแล้ว';
        }
        if (!$thaiSharedAnswerGames) {
            return 'lotteryGameChitAnswers Not Found';
        }
        $thaiSharedGameChits = ThaiSharedGameChit::find()->where(['thaiSharedGameId' => $id, 'status' => Constants::status_playing])->all();
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $thaiSharedGame->status = Constants::status_finish_show_result;
            $thaiSharedGame->updatedBy = Yii::$app->user->id;
            $thaiSharedGame->updateAt = date('Y-m-d H:i:s');
            if (!$thaiSharedGame->save()) {
                throw new ServerErrorHttpException('Can not save Thai Shared Game');
            }
            $thaiSharedGameChitIds = [];
            $commissionUserAgentPlay = [];
            $userIds = [];
            foreach ($thaiSharedGameChits as $thaiSharedGameChit) {
                $thaiSharedGameChitIds[] = $thaiSharedGameChit->id;
                $thaiSharedGameChit->status = Constants::status_finish_show_result;
                if (!$thaiSharedGameChit->save()) {
                    throw new ServerErrorHttpException('Can not save Thai Shared Game Chit');
                }
                $userIds[] = $thaiSharedGameChit->userId;
                if (!empty($thaiSharedGameChit->user->agent)) {
                    if (!isset($commissionUserAgentPlay[$thaiSharedGameChit->user->agent])) {
                        $commissionUserAgentPlay[$thaiSharedGameChit->user->agent] = 0;
                    }
                    $commissionUserAgentPlay[$thaiSharedGameChit->user->agent] += $thaiSharedGameChit->totalDiscount > 0 ?
                        $thaiSharedGameChit->totalDiscount : $thaiSharedGameChit->totalAmount;
                }
            }
            if (count($commissionUserAgentPlay) > 0) {
                $settingCommissionCredit = SettingCommissionCredit::find()->where(['type' => Constants::setting_commission_credit_invite])->one();
                $percent = 0;
                if (!empty($settingCommissionCredit)) {
                    $percent = empty($settingCommissionCredit->value) ? 0 : $settingCommissionCredit->value;
                }
                foreach ($commissionUserAgentPlay as $agentId => $commissionUserAgent) {
                    $commissionAmount = $commissionUserAgent * ($percent / 100);
                    $remark = Constants::$reason_credit[Constants::reason_credit_commission_in] . ' แทงหวยหุ้น ' . $thaiSharedGame->title . '/' . date('d/m/Y', strtotime($thaiSharedGame->endDate)) . ' #' . $thaiSharedGameChit->getOrder();
                    Commission::commissionWalk(Constants::action_commission_top_up, $agentId, Yii::$app->user->id, Constants::reason_commission_top_up, $commissionAmount, $remark);
                }
            }
            foreach ($thaiSharedGameChitIds as $thaiSharedGameChitId) {
                foreach ($thaiSharedAnswerGames as $key => $thaiSharedAnswerGame) {
                    /* @var $thaiSharedAnswerGame ThaiSharedAnswerGame */
                    $thaiSharedAnswerGame->updatedBy = Yii::$app->user->id;
                    $thaiSharedAnswerGame->updatedAt = date('Y-m-d H:i:s');
                    if (!$thaiSharedAnswerGame->save()) {
                        throw new ServerErrorHttpException('Can not update thai shared answer game');
                    }
                    $thaiSharedGameChitDetails = ThaiSharedGameChitDetail::find()->where([
                        'number' => $thaiSharedAnswerGame->number,
                        'thaiSharedGameChitId' => $thaiSharedGameChitId,
                        'playTypeId' => $thaiSharedAnswerGame->playTypeId,
                    ])->all();
                    foreach ($thaiSharedGameChitDetails as $thaiSharedGameChitDetail) {
                        $isThaiSharedGameChitDetail = ThaiSharedGameChitDetail::find()->where([
                            'numberSetLottery' => $thaiSharedGameChitDetail->numberSetLottery,
                            'flag_result' => 1,
                            'thaiSharedGameChitId' => $thaiSharedGameChitId
                        ])->count();
                        if (!$isThaiSharedGameChitDetail) {
                            $thaiSharedGameChitDetail->flag_result = 1;
                            $thaiSharedGameChitDetail->win_credit = $thaiSharedGameChitDetail->setNumber * $thaiSharedGameChitDetail->playType->jackpot_per_unit;
                            $remark = Constants::$reason_credit[Constants::reason_credit_bet_win] . ' ' . $thaiSharedGame->title . '/ ' . date('d/m/Y', strtotime($thaiSharedGame->endDate)) . '/ ประเภทที่ถูกรางวัล: ' . $thaiSharedAnswerGame->playType->title . ' /#' . $thaiSharedGameChitDetail->thaiSharedGameChit->getOrder();
                            $creditWalk = Credit::creditWalk(Constants::action_credit_top_up, $thaiSharedGameChitDetail->userId, Yii::$app->user->id, Constants::reason_credit_bet_win, $thaiSharedGameChitDetail->win_credit, $remark);
                            if (!$creditWalk) {
                                throw new ServerErrorHttpException('Not save creditWalk');
                            }
                            if (!$thaiSharedGameChitDetail->save()) {
                                throw new ServerErrorHttpException('Can not save lottery game chit detail');
                            }
                        }
                    }
                }
            }
            ThaiSharedGameChitDetail::updateAll(['flag_result' => 0, 'win_credit' => 0],
                ['AND',
                    'flag_result = 0',
                    ['IN', 'thaiSharedGameChitId', $thaiSharedGameChitIds]
                ]);

            Queue::updateAll(['status' => Constants::status_inactive], ['gameId' => $thaiSharedGame->gameId, 'userId' => $userIds]);
            $transaction->commit();
            Yii::$app->getSession()->setFlash('alert', [
                'body' => 'เฉลย lottery เรียบร้อยแล้วครับ',
                'options' => ['class' => 'alert-success']
            ]);
            return $this->redirect(['thai-shared/index']);
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    public function actionBlock($id)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $identity = Yii::$app->user->getIdentity();
        $modelAuthRoles = new AuthRoles();
        $arrRoles = $modelAuthRoles->_getRoles($identity->auth_roles_id);
        if (!in_array('thai-shared-game-answer', $arrRoles)) {
            return ['message' => 'คุณไม่มีสิทธิ์ในการออกผลเฉลย'];
        }
        $thaiSharedGame = ThaiSharedGame::find()->where(['id' => $id])->one();
        if ($thaiSharedGame->startDate >= date('Y-m-d H:i:s') || $thaiSharedGame->endDate > date('Y-m-d H:i:s')) {
            return ['message' => 'ไม่สามารถออกผลได้เนื่องจากยังไม่สิ้นสุดเวลาหรือยังไม่สิ้นสุดเวลา'];
        }
        $thaiSharedGameChits = ThaiSharedGameChit::find()->where([
            'thaiSharedGameId' => $id,
            'status' => Constants::status_playing
        ])->all();
        foreach ($thaiSharedGameChits as $thaiSharedGameChit) {
            $queue = new Queue();
            $queue->gameId = $thaiSharedGame->gameId;
            $queue->userId = $thaiSharedGameChit->userId;
            $queue->status = Constants::status_active;
            $queue->save();
        }
        return ['message' => 'success'];
    }

    protected function findThreeTod($number)
    {
        $arr_num = str_split($number);
        $n = 0;
        $arr_swap_num = [];
        for ($i = 0; $i < count($arr_num); $i++) {
            $tmp = $arr_num[$i];
            for ($j = 0; $j < count($arr_num); $j++) {
                if ($i != $j) {
                    $tmp .= '' . $arr_num[$j];
                }
            }
            if (!in_array($tmp, $arr_swap_num)) {
                $arr_swap_num[$n++] = $tmp;
            }

            $tmp = $arr_num[$i];
            for ($j = (count($arr_num) - 1); $j >= 0; $j--) {
                if ($i != $j) {
                    $tmp .= '' . $arr_num[$j];
                }
            }
            if (!in_array($tmp, $arr_swap_num)) {
                $arr_swap_num[$n++] = $tmp;
            }
        }
        return $arr_swap_num;
    }

    public function actionPreviewAnswer($id)
    {
        $identity = Yii::$app->user->getIdentity();
        $modelAuthRoles = new AuthRoles();
        $arrRoles = $modelAuthRoles->_getRoles($identity->auth_roles_id);
        if (!in_array('answer-shared-game', $arrRoles)) {
            throw new ServerErrorHttpException('คุณไม่มีสิทธิ์ในการสร้างเฉลยหวยหุ้น');
        }
        $thaiSharedGame = ThaiSharedGame::findOne($id);
        if (!$thaiSharedGame) {
            throw new NotFoundHttpException('Not Found Thai Shared Game');
        }
        $searchModel = new ThaiSharedGameChitDetailSearch();
        $answers = [];
        if (Yii::$app->request->get('ThaiSharedAnswerGameForm')) {
            $thaiSharedAnswerGameForm = Yii::$app->request->get('ThaiSharedAnswerGameForm');
            if (isset($thaiSharedAnswerGameForm['four_dt'])) {
                $answers = [
                    'four_dt' => $this->findGenerateNumberByPlayType($thaiSharedAnswerGameForm['four_dt'], 'four_dt'),
                    'four_tod' => $this->findGenerateNumberByPlayType($thaiSharedAnswerGameForm['four_dt'], 'four_tod'),
                    'three_top' => $this->findGenerateNumberByPlayType($thaiSharedAnswerGameForm['four_dt'], 'three_top'),
                    'three_tod' => $this->findGenerateNumberByPlayType($thaiSharedAnswerGameForm['four_dt'], 'three_tod'),
                    'two_ft' => $this->findGenerateNumberByPlayType($thaiSharedAnswerGameForm['four_dt'], 'two_ft'),
                    'two_bk' => $this->findGenerateNumberByPlayType($thaiSharedAnswerGameForm['four_dt'], 'two_bk'),
                ];
            } else if (isset($thaiSharedAnswerGameForm['three_top']) && isset($thaiSharedAnswerGameForm['two_under'])) {
                $answers = [
                    'three_top' => $thaiSharedAnswerGameForm['three_top'],
                    'three_tod' => $this->findThreeTod($thaiSharedAnswerGameForm['three_top']),
                    'two_top' => $this->findTwoTop($thaiSharedAnswerGameForm['three_top']),
                    'two_under' => $thaiSharedAnswerGameForm['two_under'],
                    'run_top' => $this->findRunTop($thaiSharedAnswerGameForm['three_top']),
                    'run_under' => $this->findRunUnder($thaiSharedAnswerGameForm['two_under']),
                ];
                if (isset($thaiSharedAnswerGameForm['three_top2_1']) && isset($thaiSharedAnswerGameForm['three_under2_2'])) {
                    $answerLotteryGames = [
                        'three_top2' => [
                            $thaiSharedAnswerGameForm['three_top2_1'],
                            $thaiSharedAnswerGameForm['three_under2_2']
                        ],
                        'three_und2' => [
                            $thaiSharedAnswerGameForm['three_top2_1'],
                            $thaiSharedAnswerGameForm['three_under2_2']
                        ],
                    ];
                    $answers = array_merge($answers, $answerLotteryGames);
                }
            }
        }
        $searchModel->thaiSharedGameId = $thaiSharedGame->id;
        $searchModel->number = $answers;
        $dataProvider = $searchModel->previewAnswerSearch(Yii::$app->request->queryParams);
        return $this->render('preview-answer', [
            'dataProvider' => $dataProvider,
            'thaiSharedGame' => $thaiSharedGame,
        ]);
    }

    protected function findTwoTop($number)
    {
        return substr($number, 1, 2);
    }

    protected function findRunTop($number)
    {
        return str_split($number);
    }

    protected function findRunUnder($number)
    {
        return str_split($number);
    }

    protected function findGenerateNumberByPlayType($number, $playType)
    {
        if ($playType === 'four_tod') {
            return Constants::permute($number);
        } else if ($playType === 'three_ft') {
            return substr($number, 0, 3);
        }else if ($playType === 'three_top') {
            return substr($number, 1, 3);
        } else if ($playType === 'three_tod') {
            $number = substr($number, 1, 3);
            return Constants::permute($number);
        } else if ($playType === 'two_ft') {
            return substr($number, 0, 2);
        } else if ($playType === 'two_bk') {
            return substr($number, 2, 2);
        }
        return $number;
    }
}
