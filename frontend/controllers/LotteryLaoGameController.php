<?php

namespace frontend\controllers;

use common\libs\Constants;
use common\models\ConditionLimitNumber;
use common\models\Credit;
use common\models\LimitLotteryNumberGame;
use common\models\PlayType;
use common\models\SettingLotteryLaoSet;
use common\models\ThaiSharedAnswerGame;
use common\models\ThaiSharedGame;
use common\models\ThaiSharedGameChit;
use common\models\ThaiSharedGameChitDetail;
use common\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\ServerErrorHttpException;
use common\models\PostCreditTransection;

/**
 * Created by PhpStorm.
 * User: topte
 * Date: 9/29/2018
 * Time: 12:50 AM
 */
class LotteryLaoGameController extends Controller
{
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
                'class' => \yii\filters\VerbFilter::className(),
                'actions' => [
                    'buy' => ['POST'],
                    'play' => ['GET'],
                    'cancel' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * @param $id
     * @return string
     */
    public function actionIndex()
    {
        $laoLists = ThaiSharedGame::find()->where([
            'gameId' => [Constants::LOTTERYLAOGAME, Constants::LOTTERYLAODISCOUNTGAME, Constants::LOTTERY_VIETNAM_SET],
            'typeSharedGameId' => 2
        ])->andWhere([
            '<>',
            'disabled',
            0
        ])->andWhere( 'DATE(startDate) <= CURDATE()')->
        andWhere('DATE(endDate) >= CURDATE()')->all();
        return $this->render('index', [
            'laoLists' => $laoLists,
        ]);
    }

    public function actionPlay($id)
    {
        $thaiSharedGame = ThaiSharedGame::find()->where(['id' => $id])->one();
        if (!$thaiSharedGame) {
            throw new NotFoundHttpException();
        }
        $lists = [];
        $playTypes = PlayType::find()->where(['game_id' => $thaiSharedGame->gameId])->all();
        foreach ($playTypes as $playType) {
            $thaiSharedGameAnswer = ThaiSharedAnswerGame::find()->where([
                'thaiSharedGameId' => $thaiSharedGame->id,
                'playTypeId' => $playType->id
            ])->one();
            $lists[$playType->code]['title'] = $playType->title;
            $lists[$playType->code]['jackpot_per_unit'] = $playType->jackpot_per_unit;
            if ($thaiSharedGameAnswer) {
                if (is_array($thaiSharedGameAnswer->number)) {
                    $answerGameNumber = implode(',', $thaiSharedGameAnswer->number);
                }else{
                    $answerGameNumber = $thaiSharedGameAnswer->number;
                }
            }
            $lists[$playType->code]['number'] = isset($answerGameNumber) ? $answerGameNumber : 'xxxx';
        }
        return $this->render('play', [
            'thaiSharedGame' => $thaiSharedGame,
            'lists' => $lists,
        ]);
    }

    public function actionTang($id)
    {
        $thaiSharedGame = ThaiSharedGame::find()->where(['id' => $id])->one();
        $userId = \Yii::$app->user->id;
        if (!$thaiSharedGame) {
            throw new NotFoundHttpException();
        }
        $lists = [];
        $settingLotteryLaoSet = SettingLotteryLaoSet::find()->where(['gameId' => $thaiSharedGame->gameId])->one();
        $playTypes = PlayType::find()->where(['game_id' => $thaiSharedGame->gameId])->all();

        foreach ($playTypes as $playType) {
            $thaiSharedGameAnswer = ThaiSharedAnswerGame::find()->where([
                'thaiSharedGameId' => $thaiSharedGame->id,
                'playTypeId' => $playType->id
            ])->one();
            $lists[$playType->code]['title'] = $playType->title;
            $lists[$playType->code]['jackpot_per_unit'] = $playType->jackpot_per_unit;
            if ($thaiSharedGameAnswer) {
                if (is_array($thaiSharedGameAnswer->number)) {
                    $answerGameNumber = implode(',', $thaiSharedGameAnswer->number);
                }else{
                    $answerGameNumber = $thaiSharedGameAnswer->number;
                }
            }
            $lists[$playType->code]['number'] = isset($answerGameNumber) ? $answerGameNumber : 'xxxx';
        }
        $user = User::find()->where(['id' => $userId])->one();
        $lotteryConfig['price'] = number_format($settingLotteryLaoSet->value,2);
        $lotteryConfig['max_set'] = $settingLotteryLaoSet->amountSet;
        $lotteryConfig['teng_4'] = ArrayHelper::getValue(PlayType::find()->where(['code' => 'four_dt'])->asArray()->one(), 'jackpot_per_unit');
        $lotteryConfig['teng_3'] = ArrayHelper::getValue(PlayType::find()->where(['code' => 'three_top'])->asArray()->one(), 'jackpot_per_unit');
        $lotteryConfig['tode_4'] = ArrayHelper::getValue(PlayType::find()->where(['code' => 'four_tod'])->asArray()->one(), 'jackpot_per_unit');
        $lotteryConfig['tode_3'] = ArrayHelper::getValue(PlayType::find()->where(['code' => 'three_tod'])->asArray()->one(), 'jackpot_per_unit');
        $lotteryConfig['teng_nha_2'] = ArrayHelper::getValue(PlayType::find()->where(['code' => 'two_ft'])->asArray()->one(), 'jackpot_per_unit');
        $lotteryConfig['teng_lung_2'] = ArrayHelper::getValue(PlayType::find()->where(['code' => 'two_bk'])->asArray()->one(), 'jackpot_per_unit');
        $lotteryConfig['teng_nha_3'] = ArrayHelper::getValue(PlayType::find()->where(['code' => 'three_ft'])->asArray()->one(), 'jackpot_per_unit');
        $lotteryConfig['bet_id'] = $thaiSharedGame->id;
        $lotteryConfig['bet_type'] = 'LOTTERY';
        $lotteryConfig['bet_name'] = $thaiSharedGame->title;
        $lotteryConfig['bet_round'] = '';
        $lotteryConfig['open_dt'] = $thaiSharedGame->startDate;
        $lotteryConfig['close_dt'] = $thaiSharedGame->endDate;
        $lotteryConfig['result_dt'] = '';
        $lotteryConfig['set_result'] = 0;
        $lotteryConfig['abort'] = 0;
        return $this->render('tang', [
            'thaiSharedGame' => $thaiSharedGame,
            'lists' => $lists,
            'user' => $user,
            'settingLotteryLaoSet' => $settingLotteryLaoSet,
            'lotteryConfig' => json_encode($lotteryConfig),
        ]);
    }

    public function actionBuy()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $data = Yii::$app->request->post();
        $userId = Yii::$app->user->id;
        $thaiSharedGame = ThaiSharedGame::findOne(['id' => $data['thaiSharedGameId']]);
        if (!$thaiSharedGame) {
            return ['result' => 'ERROR', 'msg' => 'เกิดข้อผิดพลาด'];
        }
        if (!empty($data)) {
            //check credit balance
            $poy = Yii::$app->request->post('lottery');
            $settingLotteryLaoSet = SettingLotteryLaoSet::find()->where(['gameId' => $thaiSharedGame->gameId])->one();
            $poys = json_decode($poy, true);
            $totalAmount = 0;
            if (count($poys) > $settingLotteryLaoSet->amountNumber) {
                return ['result' => 'ERROR', 'msg' => 'ซื้อ'.$thaiSharedGame->game->title.'เกินลิมิต'];
            }
            $playType = PlayType::find()->where(['game_id' => $thaiSharedGame->gameId, 'code' => 'four_dt'])->one();
            foreach ($poys as $poy) {
                $amount = $poy['amount'] * $settingLotteryLaoSet->value;
                $totalAmount += $amount;
                $limitLotteryNumberGame = LimitLotteryNumberGame::find()->where([
                    'number' => $poy['number'],
                    'thaiSharedGameId' => $thaiSharedGame->id
                ])->one();
                $limitLotteryNumberGameAll = LimitLotteryNumberGame::find()->where([
                    'thaiSharedGameId' => $thaiSharedGame->id,
                    'number' => 'all'
                ])->one();
                $totalPoyNumber = ThaiSharedGameChit::find()->where([
                    'thaiSharedGameId' => $thaiSharedGame->id,
                    'number' => $poy['number'],
                    'playTypeId' => $playType->id
                ])->joinWith('thaiSharedGameChitDetail')->sum('setNumber');
                if ($limitLotteryNumberGame) {
                    $limitLotteryNumber = intval($limitLotteryNumberGame->totalSetNumber) - intval($totalPoyNumber);
                    if ($poy['amount'] > $limitLotteryNumber || $poy['amount'] < 1) {
                        $msg[] = $limitLotteryNumber > 0 ? $thaiSharedGame->game->title.' '. $poy['number']. ' สามารถแทงได้คือจำนวน '. $limitLotteryNumber. ' ชุด กรุณาระบุจำนวนให้ถูกต้อง' :
                            'เลข'. $poy['number'] .' '.$thaiSharedGame->game->title.'นี้ปิดรับแทง กรุณาแทงเลขใหม่<br>';
                    }
                } elseif ($limitLotteryNumberGameAll) {
                    $limitLotteryNumber = $limitLotteryNumberGameAll->totalSetNumber - $totalPoyNumber;
                    if ($poy['amount'] > $limitLotteryNumber || $poy['amount'] < 1) {
                        $msg[] = $limitLotteryNumber > 0 ? $thaiSharedGame->game->title.' ' . $poy['number'] . ' สามารถแทงได้คือจำนวน ' . $limitLotteryNumber . ' ชุด กรุณาระบุจำนวนให้ถูกต้อง' :
                            'เลข' . $poy['number'] . ' '.$thaiSharedGame->game->title.'นี้ปิดรับแทง กรุณาแทงเลขใหม่<br>';
                    }
                } else {
                    if ($poy['amount'] > $settingLotteryLaoSet->amountSet || $poy['amount'] < 1) {
                        return ['result' => 'ERROR', 'msg' => 'จำนวนชุดเกินลิมิตหรือน้อยกว่าที่กำหนดไว้'];
                    }
                }
            }
            if (isset($msg)) {
                return [
                    'result' => 'ERROR',
                    'msg' => $msg
                ];
            }
            $finishTime = strtotime($thaiSharedGame->endDate);
            $startTime = strtotime($thaiSharedGame->startDate);
            if ($startTime > time() || $thaiSharedGame->status <> Constants::status_active) {
                return ['result' => 'ERROR', 'msg' => 'NOTOPEN'];
            } else if (time() > $finishTime || $thaiSharedGame->status <> Constants::status_active) {
                return ['result' => 'ERROR', 'msg' => 'NOTOPEN'];
            }
            $user = User::findOne($userId);
            $sumWaittingPostWithDraw = PostCreditTransection::find()
                ->where(['poster_id' => $userId, 'action_id' => Constants::action_credit_withdraw, 'status' => Constants::status_waitting])
                ->sum('amount');
            if ($totalAmount > ($user->creditBalance - $sumWaittingPostWithDraw)) {
                return ['result' => 'ERROR', 'msg' => 'NOMONEY'];
            }
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $thaiSharedGameChit = new ThaiSharedGameChit();
                $thaiSharedGameChit->userId = $userId;
                $thaiSharedGameChit->thaiSharedGameId = $thaiSharedGame->id;
                $thaiSharedGameChit->createdAt = date('Y-m-d H:i:s');
                $thaiSharedGameChit->createdBy = $userId;
                $thaiSharedGameChit->status = Constants::status_playing;
                if (!$thaiSharedGameChit->save()) {
                    throw new ServerErrorHttpException('Can Not Save ThaiSharedGameChit');
                }
                $thaiSharedGameChitDetailTotalAmount = 0;
                foreach ($poys as $poy) {
                    $number = $poy['number'];
                    $amount = $poy['amount'];
                        $answers = [
                            'four_dt' => $this->findGenerateNumberByPlayType($number, 'four_dt'),
                            'four_tod' => $this->findGenerateNumberByPlayType($number, 'four_tod'),
                            'three_top' => $this->findGenerateNumberByPlayType($number, 'three_top'),
                            'three_tod' => $this->findGenerateNumberByPlayType($number, 'three_tod'),
                            'two_ft' => $this->findGenerateNumberByPlayType($number, 'two_ft'),
                            'two_bk' => $this->findGenerateNumberByPlayType($number, 'two_bk'),
                            'three_ft' => $this->findGenerateNumberByPlayType($number, 'three_ft'),
                        ];
                        if ($amount > $settingLotteryLaoSet->amountSet) {
                            $amount =  $settingLotteryLaoSet->amountSet;
                        } elseif ($amount < 1) {
                            $amount = 1;
                        }
                        $totalAmount = $amount * $settingLotteryLaoSet->value;
                        foreach ($answers as $key => $value) {
                            $playType = PlayType::find()->select(['id', 'title', 'code'])->where([
                                'game_id' => $thaiSharedGame->gameId,
                                'code' => $key
                            ])->one();
                            if (!$playType) {
                                throw new ServerErrorHttpException('Can Not Found PlayType');
                            }
                            if (is_array($value)) {
                                foreach ($value as $number) {
                                    $model = new ThaiSharedGameChitDetail();
                                    $model->thaiSharedGameChitId = $thaiSharedGameChit->id;
                                    $model->number = $number;
                                    $model->playTypeId = $playType->id;
                                    $model->setNumber = $amount;
                                    $model->amount = $totalAmount;
                                    $model->createdAt = date('Y-m-d H:i:s', time());
                                    $model->createdBy = $userId;
                                    $model->userId = $userId;
                                    $model->numberSetLottery = $answers['four_dt'];

                                    if (!$model->save()) {
                                        throw new ServerErrorHttpException('Can Not Save ThaiSharedGameChitDetail');
                                    }
                                }
                            } else {
                                $model = new ThaiSharedGameChitDetail();
                                $model->thaiSharedGameChitId = $thaiSharedGameChit->id;
                                $model->number = $value;
                                $model->playTypeId = $playType->id;
                                $model->setNumber = $amount;
                                $model->amount = $totalAmount;
                                $model->createdAt = date('Y-m-d H:i:s', time());
                                $model->createdBy = $userId;
                                $model->userId = $userId;
                                $model->numberSetLottery = $answers['four_dt'];

                                if (!$model->save()) {
                                    throw new ServerErrorHttpException('Can Not Save ThaiSharedGameChitDetail');
                                }
                            }
                        }
                        $thaiSharedGameChitDetailTotalAmount += $totalAmount;
                }
                $thaiSharedGameChit->totalAmount = $thaiSharedGameChitDetailTotalAmount;
                $thaiSharedGameChit->updatedBy = $userId;
                $thaiSharedGameChit->updatedAt = date('Y-m-d H:i:s', time());
                if (!$thaiSharedGameChit->save()) {
                    throw new ServerErrorHttpException('Can Not Update ThaiSharedGameChit');
                }
                //create transection
                $reason = 'แทงพนัน ' . $thaiSharedGame->title . ' / ' . date('d/m/Y') . ' #' . $thaiSharedGame->gameId.$thaiSharedGameChit->id;
                $resutl = Credit::creditWalk(Constants::action_credit_withdraw, $userId, $userId, Constants::reason_credit_bet_play, $thaiSharedGameChit->totalAmount, $reason);
                $transaction->commit();
                return ['result' => 'SUCCESS', 'thaiSharedGameChitId' => $thaiSharedGameChit->id];
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            }
        }
        return $this->render('play-result', [
            'lotteryGameId' => $thaiSharedGame->id,
            'pass' => true,
            'reason' => 3,
            'text' => 'ทำรายการสำเร็จ',
            'result' => 'ทำรายการสำเร็จ'
        ]);
    }

    public function actionPoy($id)
    {
        $userId = Yii::$app->user->id;
        $thaiSharedGame = ThaiSharedGame::find()->where(['id' => $id])->one();
        $thaiSharedGames = ThaiSharedGame::find()->select('id')->andWhere([
            'title' => $thaiSharedGame->title
        ])->all();
        $playType = PlayType::find()->where(['game_id' => $thaiSharedGame->gameId, 'code' => 'four_dt'])->one();
        $thaiSharedGameChitDetails = ThaiSharedGameChitDetail::find()->joinWith(['thaiSharedGameChit'])->where([
            ThaiSharedGameChitDetail::tableName().'.userId' => $userId,
            'playTypeId' => $playType->id,
            'thaiSharedGameId' => ArrayHelper::getColumn($thaiSharedGames, 'id')
        ])->orderBy(ThaiSharedGameChit::tableName().'.id DESC')->all();
        $settingLotteryLaoSet = SettingLotteryLaoSet::find()->where(['gameId' => $thaiSharedGame->gameId])->one();
        return $this->render('poy', [
            'thaiSharedGame' => $thaiSharedGame,
            'thaiSharedGameChitDetails' => $thaiSharedGameChitDetails,
            'settingLotteryLaoSet' => $settingLotteryLaoSet,
        ]);
    }

    public function actionCancel($id)
    {
        $userId = Yii::$app->user->id;

        $thaiSharedGameChit = ThaiSharedGameChit::findOne(['id' => $id, 'userId' => $userId]);
        if (!$thaiSharedGameChit) {
            throw new ServerErrorHttpException('Not Found Thai Shared Game Chit');
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            if (($thaiSharedGameChit->thaiSharedGame->status == Constants::status_active) && ($thaiSharedGameChit->status == Constants::status_playing)) {
                $remark = 'คืนโพย ' . $thaiSharedGameChit->thaiSharedGame->title . ' / ' . date('d/m/Y') . ' #' . $thaiSharedGameChit->getOrder();
                if ($thaiSharedGameChit->totalDiscount > 0) {
                    $totalAmount = $thaiSharedGameChit->totalDiscount;
                } else {
                    $totalAmount = $thaiSharedGameChit->totalAmount;
                }
                $resutl = Credit::creditWalk(Constants::action_credit_top_up, $thaiSharedGameChit->userId, $userId, Constants::reason_credit_return_chit, $totalAmount, $remark);
                $thaiSharedGameChit->status = Constants::status_cancel;
                $thaiSharedGameChit->updatedAt = date('Y-m-d H:i:s');
                $thaiSharedGameChit->updatedBy = $userId;
                $thaiSharedGameChit->totalAmount = 0;
                $thaiSharedGameChit->totalDiscount = 0;
                if (!$thaiSharedGameChit->save()) {
                    throw new ServerErrorHttpException('Can not save Thai Shared Game Chit');
                }
                ThaiSharedGameChitDetail::updateAll(['amount' => 0, 'discount' => 0], ['thaiSharedGameChitId' => $thaiSharedGameChit->id]);
            }
            $transaction->commit();
            return true;
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
        return false;
    }

    public function actionPrint()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return ['img' => 'https://storage.googleapis.com/pattana/1564043726.2999.jpg', 'token' => 'b9f1dd5b7f0f9c8382056bb4202b24e7'];
    }

    protected function findThaiSharedGameChitDetail($playTypeId, $number)
    {
        $thaiSharedGameChitDetailTotalByNumber = ThaiSharedGameChitDetail::find()->joinWith('thaiSharedGameChit')->where([
            'number' => $number,
            'playTypeId' => $playTypeId
        ])->sum('amount');
        return $thaiSharedGameChitDetailTotalByNumber ? $thaiSharedGameChitDetailTotalByNumber : 0;
    }

    protected function findConditionByNumber($playTypeId)
    {
        return $conditionLimitNumber = ConditionLimitNumber::find()->where([
            'playTypeId' => $playTypeId,
            'gameId' => Constants::THAISHARED
        ])->one();
    }

    protected function findThaiSharedGameChitByNumberTotal($playTypeId, $number, $thaiSharedGameId)
    {
        return $thaiSharedGameChitByNumberTotal = ThaiSharedGameChitDetail::find()->joinWith('thaiSharedGameChit')->where([
            'playTypeId' => $playTypeId,
            'number' => $number,
            ThaiSharedGameChit::tableName() . '.thaiSharedGameId' => $thaiSharedGameId,
            ThaiSharedGameChit::tableName() . '.userId' => Yii::$app->user->id
        ])->sum('amount');
    }

    protected function findGenerateNumberByPlayType($number, $playType)
    {
        if ($playType === 'four_tod') {
            return Constants::permute($number);
        } else if ($playType === 'three_top') {
            return substr($number, 1, 3);
        } else if ($playType === 'three_tod') {
            $number = substr($number, 1, 3);
            return Constants::permute($number);
        } else if ($playType === 'two_ft') {
            return substr($number, 0, 2);
        } else if ($playType === 'two_bk') {
            return substr($number, 2, 2);
        } else if ($playType === 'three_ft') {
            return substr($number, 0, 3);
        }
        return $number;
    }

    protected function findTwoTop($number)
    {
        return substr($number, 1, 2);
    }
}
