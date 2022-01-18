<?php
namespace frontend\controllers;

use common\libs\Constants;
use common\models\ConditionLimitNumber;
use common\models\Credit;
use common\models\DiscountGame;
use common\models\LimitLotteryByGamePlayType;
use common\models\LimitLotteryByGamePlayTypeSet;
use common\models\NumberMemo;
use common\models\PlayType;
use common\models\Queue;
use common\models\ThaiSharedGame;
use common\models\ThaiSharedGameChit;
use common\models\ThaiSharedGameChitDetail;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\web\ServerErrorHttpException;

/**
 * Created by PhpStorm.
 * User: topte
 * Date: 2/21/2019
 * Time: 8:15 PM
 */

class LotteryGameController extends Controller
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

    public function actionPlay($id)
    {
        $thaiSharedGame = ThaiSharedGame::find()->andWhere('NOW() >= startDate AND endDate >= NOW()')->andWhere([
            'id' => $id,
            'status' => 1,
            'gameId' => [Constants::LOTTERYGAME, Constants::LOTTERYGAMEDISCOUNT],
        ])->one();
        if (!$thaiSharedGame) {
            return $this->render('play-result', ['reason' => 1, 'result' => 'ปิดรับการแทง', 'pass' => '', 'title' => '', 'id' => $id]);
        }
        $modelGroup = PlayType::find()->select(['group_id'])->where([
            'game_id' => $thaiSharedGame->gameId
        ])->groupBy(['group_id'])->all();
        $allPlayType = [];
        foreach ($modelGroup as $group) {
            $playType = [];
            $modelsPlayType = PlayType::find()->where([
                'game_id' => $thaiSharedGame->gameId,
                'group_id' => $group->group_id
            ])->all();
            foreach ($modelsPlayType as $model) {
                $discountObj = DiscountGame::find()->where(['playTypeId' => $model->id, 'status' => 1, 'title' => $thaiSharedGame->title])->orderBy('id DESC')->one();
                $playType [] = [
                    'playTypeId' => $model->id,
                    'title' => $model->title,
                    'code' => $model->code,
                    'jackpot' => $model->jackpot_per_unit,
                    'min' => $model->minimum_play,
                    'max' => $model->maximum_play,
                    'discount' => isset($discountObj) ? $discountObj->discount : 0,
                    'discountId' => isset($discountObj) ? $discountObj->id : 0,
                ];
                $allPlayType[] = [
                    'playTypeId' => $model->id,
                    'title' => $model->title,
                    'code' => $model->code,
                    'jackpot' => $model->jackpot_per_unit,
                    'min' => $model->minimum_play,
                    'max' => $model->maximum_play,
                    'discount' => isset($discountObj) ? $discountObj->discount : 0,
                    'discountId' => isset($discountObj) ? $discountObj->id : 0,
                ];
            }
            $groupPlayType [] = [
                'group_id' => $group->group->id,
                'group_title' => $group->group->title,
                'number_length' => $group->group->number_length,
                'number_range' => $group->group->number_range,
                'play_type_list' => $playType,
            ];
        }

        //spacial 19 ประตู รูดหน้า รูดหลัง
        $setNum = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];
        $arrSpecialPlay = [
            'door19' => [
                'title' => '19 ประตู',
                'code' => 'door19',
                'set_num' => $setNum
            ],
            'rood_front' => [
                'title' => 'รูดหน้า',
                'code' => 'rood_front',
                'set_num' => $setNum
            ],
            'rood_back' => [
                'title' => 'รูดหลัง',
                'code' => 'rood_back',
                'set_num' => $setNum
            ]
        ];
        $thaiSharedGameLists = ThaiSharedGameChit::find()
            ->where(['userId' => \Yii::$app->user->id])
            ->orderBy(['createdAt' => SORT_DESC])
            ->limit(100)
            ->all();

        $resultChitList = [];
        foreach ($thaiSharedGameLists as $thaiSharedGameList) {
            $results = [];
            $details = $thaiSharedGameList->thaiSharedGameChitDetails;
            $tmp = [];
            foreach ($details as $detail) {
                if (!isset($results[$detail->playType->code])) {
                    $results[$detail->playType->code] = [];
                }
                $results[$detail->playType->code][$detail->number] = $detail->amount;
            }
            $tmpResult = [];
            foreach ($results as $type => $result) {
                $tmpResult[$type] = json_encode($result, JSON_FORCE_OBJECT);
            }
            $resultChitList[] = [
                'model' => $thaiSharedGameList,
                'map_val' => json_encode($tmpResult)
            ];
        }

        //ดึงเลขชุด
        $numberMemoList = NumberMemo::find()->where(['user_id' => \Yii::$app->user->id, 'gameId' => [Constants::LOTTERYGAME, Constants::LOTTERYGAMEDISCOUNT]])->all();

        return $this->render('play', [
            'groupPlayType' => $groupPlayType,
            'arrSpecialPlay' => $arrSpecialPlay,
            'allPlayType' => $allPlayType,
            'thaiSharedGame' => $thaiSharedGame,
            'resultChitList' => $resultChitList,
            'numberMemoList' => $numberMemoList,
        ]);
    }

    public function actionBuy()
    {
        $data = Yii::$app->request->post();
        $userId = Yii::$app->user->id;
        $thaiSharedGame = ThaiSharedGame::findOne(['id' => $data['thaiSharedGameId']]);
        if (!$thaiSharedGame && $thaiSharedGame->gameId !== Constants::LOTTERYGAME && $thaiSharedGame->gameId !== Constants::LOTTERYGAMEDISCOUNT) {
            throw new ServerErrorHttpException('เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง !!');
        }
        if (!empty($data)) {
            //check credit balance
            $playData = json_decode($data['play_data']);
            $canPay = ThaiSharedGameChit::getCreditCanPay($playData, $userId, 0, $thaiSharedGame->gameId);
            $finishTime = strtotime($thaiSharedGame->endDate);
            $startTime = strtotime($thaiSharedGame->startDate);
            if ($startTime > time() || $thaiSharedGame->status <> Constants::status_active) {
                return $this->render('play-result', [
                    'result' => 'หุ้นไทย',
                    'pass' => false,
                    'reason' => 1,
                    'text' => 'ยังไม่ถึงเวลาเการเล่นเกมหุ้นไทย'
                ]);
            } else if (time() > $finishTime || $thaiSharedGame->status <> Constants::status_active) {
                return $this->render('play-result', [
                    'result' => 'หุ้นไทย',
                    'pass' => false,
                    'reason' => 1,
                    'text' => 'หมดเวลาเการเล่นเกมหุ้นไทย'
                ]);
            }
            if (!$canPay) {
                $canWithdraw = [
                    'credit' => true,
                    'reason' => 'ยอดเงินเครดิตของท่านไม่เพียงพอ'
                ];
                return $this->render('/info/index', ['canWithdraw' => $canWithdraw]);
            }
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $queue = new Queue();
                $queue->userId = $userId;
                $queue->gameId = $thaiSharedGame->gameId;
                if (!$queue->save()) {
                    throw new ServerErrorHttpException('Can not save queue');
                }
                $thaiSharedGameChit = new ThaiSharedGameChit();
                $thaiSharedGameChit->userId = $userId;
                $thaiSharedGameChit->thaiSharedGameId = $thaiSharedGame->id;
                $thaiSharedGameChit->createdAt = date('Y-m-d H:i:s');
                $thaiSharedGameChit->createdBy = $userId;
                $thaiSharedGameChit->status = Constants::status_playing;
                if (!$thaiSharedGameChit->save()) {
                    throw new ServerErrorHttpException('Can Not Save ThaiSharedGameChit');
                }
                foreach ($playData as $type => $data2) {
                    $playType = PlayType::find()->select(['id', 'title', 'minimum_play', 'maximum_play'])->where([
                        'code' => $type,
                        'game_id' => $thaiSharedGame->gameId
                    ])->one();
                    if (!$playType) {
                        throw new ServerErrorHttpException('Can Not Found Play Type');
                    }
                    $maximumPlay = $playType->maximum_play;
                    $minimumPlay = $playType->minimum_play;
                    $numSet = json_decode($data2);
                    foreach ($numSet as $number => $amount) {
                        $amount = intval($amount);
                        if ($amount > $maximumPlay) {
                            $amount = $maximumPlay;
                        } elseif ($amount < $minimumPlay) {
                            $amount = $minimumPlay;
                        }
                        $thaiSharedGameChitByNumberTotal = $this->findThaiSharedGameChitByNumberTotal($playType->id, $number, $thaiSharedGame->id);
                        $conditionLimitNumber = $this->findConditionByNumber($playType->id);
                        $totalBuyNumber = $thaiSharedGameChitByNumberTotal + $amount;
                        if ($conditionLimitNumber) {
                            $totalBuy = $conditionLimitNumber->limit - $amount;
                            if ($totalBuyNumber > $conditionLimitNumber->limit) {
                                $transaction->rollBack();
                                return $this->render('play-result', [
                                    'result' => $thaiSharedGame->title,
                                    'pass' => false,
                                    'reason' => 4,
                                    'text' => 'คุณซื้อตัวเลข ' . $number . ' ประเภท ' . $playType->title . ' ได้สูงสุดไม่เกิน ' . $conditionLimitNumber->limit . ' บาท',
                                ]);
                            }
                        }
                        $model = new ThaiSharedGameChitDetail();
                        $discountGame = DiscountGame::find()->where(['playTypeId' => $playType->id, 'status' => 1, 'title' => $thaiSharedGame->title])->orderBy('id DESC')->one();
                        $model->discountGameId = 0;

                        $totalDiscount = 0;
                        if ($discountGame) {
                            $discount = $amount * $discountGame->discount / 100;
                            $totalDiscount = $amount - $discount;
                            $model->discountGameId = $discountGame->id;
                            $model->amount = $amount;
                        }else{
                            $model->amount = $amount;
                        }
                        $model->thaiSharedGameChitId = $thaiSharedGameChit->id;
                        $model->number = $number;
                        $model->playTypeId = $playType->id;
                        $model->discount = $totalDiscount;
                        $model->createdAt = date('Y-m-d H:i:s', time());
                        $model->createdBy = $userId;
                        $model->userId = $userId;
                        if (!$model->save()) {
                            throw new ServerErrorHttpException('Can Not Save ThaiSharedGameChitDetail');
                        }
                        $thaiSharedGameChit->totalAmount += $amount;
                        $thaiSharedGameChit->totalDiscount += $model->discount;
                    }
                }
                $thaiSharedGameChit->updatedBy = $userId;
                $thaiSharedGameChit->updatedAt = date('Y-m-d H:i:s', time());
                if (!$thaiSharedGameChit->save()) {
                    throw new ServerErrorHttpException('Can Not Update ThaiSharedGameChit');
                }
                //create transection
                $reason = 'แทงพนัน ' . $thaiSharedGame->title . ' / ' . date('d/m/Y') . ' #' . $thaiSharedGameChit->getOrder();
                if ($thaiSharedGameChit->totalDiscount > 0) {
                    $totalAmount = $thaiSharedGameChit->totalDiscount;
                } else {
                    $totalAmount = $thaiSharedGameChit->totalAmount;
                }
                $resutl = Credit::creditWalk(Constants::action_credit_withdraw, $userId, $userId, Constants::reason_credit_bet_play, $totalAmount, $reason);
                if (!Queue::deleteAll(['id' => $queue->id])) {
                    throw new ServerErrorHttpException('Can not delete queue');
                }
                $transaction->commit();
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
            ThaiSharedGameChit::tableName().'.thaiSharedGameId' => $thaiSharedGameId,
            ThaiSharedGameChit::tableName().'.userId' => Yii::$app->user->id
        ])->sum('amount');
    }
}