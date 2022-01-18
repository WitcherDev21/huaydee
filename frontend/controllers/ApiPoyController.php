<?php
namespace frontend\controllers;

use common\libs\Constants;
use common\models\Credit;
use common\models\DiscountGame;
use common\models\LimitAutoLotteryNumberGame;
use common\models\LimitLotteryNumberGame;
use common\models\PlayType;
use common\models\PostCreditTransection;
use common\models\Queue;
use common\models\SetupLevelPlaytype;
use common\models\ThaiSharedGame;
use common\models\ThaiSharedGameChit;
use common\models\ThaiSharedGameChitDetail;
use common\models\User;
use yii\filters\AccessControl;
use yii\rest\Controller;
use yii\filters\VerbFilter;
use Yii;
use yii\web\Response;
use yii\web\ServerErrorHttpException;
use common\models\Yeekee;
use common\models\YeekeeChit;
use common\models\YeekeeChitDetail;
use common\models\YeekeePost;
use common\models\YeekeeSearch;

class ApiPoyController extends Controller {

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
                    'pre-send-poy' => ['POST'],
                ],
            ],
        ];
    }

    public function actionPreSendPoy()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $userId = Yii::$app->user->id;
        if (Yii::$app->request->isPost) {
//            $checkQueueProcess = Queue::find()->where([
//                'userId' => $userId,
//                'status' => Constants::status_active
//            ])->count();
//            if ($checkQueueProcess) {
//                return [
//                    'result' => 'ERROR',
//                    'message' => 'ระบบกำลังประมวลผลอยู่ กรุณาแทงใหม่อีกครั้ง ภายใน 60 วินาที',
//                ];
//            }
            $poy = Yii::$app->request->post('poy');
            $poys = json_decode($poy, true);
            $thaiSharedGame = ThaiSharedGame::find()->andWhere('NOW() >= startDate AND endDate >= NOW()')->andWhere([
                'id' => $poys['bet_id'],
                'status' => 1
            ])->one();
            if (!$thaiSharedGame) {
                return ['result' => 'TIME_OUT'];
            }
            $total = 0;
            $isChange = false;
            $isNumberClose = false;
            $message = '';
            $isLimitNumber = false;
            $poyLists = [];
            $isLimitAuto = $thaiSharedGame->limitAuto === 1 ? true : false;
            foreach ($poys['poy_list'] as $key => $poy) {
                $code = PlayType::instance()->getReConvertPlayTypeCode($poy['option']);
                $playType = PlayType::find()->where(['game_id' => $thaiSharedGame->gameId, 'code' => $code])->one();
                if ($poy['price'] < $playType->minimum_play || $poy['price'] > $playType->maximum_play) {
                    return ['result' => 'MINMAX'];
                }
                $price = $poy['price'];
                $totalAmountNumber = ThaiSharedGameChitDetail::find()->joinWith('thaiSharedGameChit')->where([
                    'playTypeId' => $playType->id,
                    'thaiSharedGameId' => $thaiSharedGame->id,
                    'number' => $poy['number'],
                ])->sum('amount');
                if ($totalAmountNumber) {
                    $totalBuyNumber = $poy['price'] + $totalAmountNumber;
                }else{
                    $totalBuyNumber = $totalAmountNumber;
                    
                    // เช็คราคาเริ่มต้นซื้อมาเท่าไร
                    $whereLevelPlayType = SetupLevelPlaytype::find()->where([
                        'codePlayType' => $playType->code,
                    ])->andWhere([
                        'like',
                        'gameId',
                        $thaiSharedGame->gameId
                    ])->andWhere([
                        '<=',
                        'minimumPlay',
                        $poy['price']
                    ])->andWhere([
                        '>=',
                        'maximumPlay',
                        $poy['price']
                    ])->orderBy('maximumPlay DESC')->one();
                }

                // ราคาแทงเกิน maximum 6
                $maximumPaid = SetupLevelPlaytype::find()->where([
                    'codePlayType' => $playType->code,
                ])->andWhere(['like','gameId',$thaiSharedGame->gameId])
                ->andWhere(['<=','maximumPlay',$poy['price']
                ])->orderBy('maximumPlay DESC')->one();

                $setupLevelPlayType = SetupLevelPlaytype::find()->where([
                    'codePlayType' => $playType->code,
                ])->andWhere([
                    'like',
                    'gameId',
                    $thaiSharedGame->gameId
                ])->andWhere([
                    '<=',
                    'minimumPlay',
                    $totalBuyNumber
                ])->andWhere([
                    '>=',
                    'maximumPlay',
                    $totalBuyNumber
                ])->orderBy('maximumPlay DESC')->one();

                // ลดราคาจ่ายเอาตัวที่เกิน max
                $setupLevelPlayTypeMaxPaid = SetupLevelPlaytype::find()->where([
                    'codePlayType' => $playType->code,
                ])->andWhere(['like','gameId',$thaiSharedGame->gameId])
                ->andWhere(['<=','maximumPlay',$totalBuyNumber
                ])->orderBy('maximumPlay DESC')->one();

                $limitLotteryNumberGame = LimitLotteryNumberGame::find()->where([
                    'thaiSharedGameId' => $thaiSharedGame->id,
                    'number' => $poy['number'],
                    'playTypeId' => $playType->id
                ])->orderBy('createdAt DESC')->one();
                if (!$limitLotteryNumberGame && $isLimitAuto) {
                    $totalAmountNumber = ThaiSharedGameChitDetail::find()->joinWith('thaiSharedGameChit')->where([
                        'number' => $poy['number'],
                        'playTypeId' => $playType->id,
                        'thaiSharedGameId' => $thaiSharedGame->id,
                    ])->sum('amount');
                    if (!$totalAmountNumber) {
                        $totalBuyNumber = $poy['price'] + $totalAmountNumber;
                    }else{
                        $totalBuyNumber = $totalAmountNumber;
                    }
                    $limitLotteryNumberGame = LimitAutoLotteryNumberGame::find()->where([
                        'thaiSharedGameId' => $thaiSharedGame->id,
                        'playTypeId' => $playType->id,
                    ])->andWhere(['<', 'minimumRate', $totalBuyNumber])->andWhere(['>=', 'maximumRate', $totalBuyNumber])->orderBy('maximumRate DESC')->one();
                }
                if ($playType->limitByUser) {
                    $totalAmount = ThaiSharedGameChit::find()->joinWith('thaiSharedGameChitDetail')->where([
                        'thaiSharedGameId' => $thaiSharedGame->id,
                        'number' => $poy['number'],
                        'playTypeId' => $playType->id,
                        ThaiSharedGameChitDetail::tableName().'.userId' => $userId,
                    ])->sum('amount');
                    if (isset($poyLists[$playType->code][$poy['number']])) {
                        $totalBuy = $totalAmount + $poyLists[$playType->code][$poy['number']] + $price;
                    } else {
                        $totalBuy = $totalAmount + $price;
                        $poyLists[$playType->code][$poy['number']] = 0;
                    }
                    $poyLists[$playType->code][$poy['number']] = $poyLists[$playType->code][$poy['number']] + $price;
                    if ($playType->limitByUser < $totalBuy) {
                        $limitBuy = $playType->limitByUser - $totalAmount;
                        if ($limitBuy > 0) {
                            $message = 'ยอดแทงเกินกว่ายอดจำกัดจำนวนการแทงต่อสมาชิก';
                        } else {
                            $message .= $playType->title.' เลข '.$poy['number'].' ปิดรับแทง <br>';
                        }
                        $isLimitNumber = true;
                        $jackPotPerUnit = $playType->jackpot_per_unit;
                        $multiplyChanges[$key]['is_duplicate'] = $poy['is_duplicate'];
                        $multiplyChanges[$key]['last_add_num'] = $poy['last_add_num'];
                        $multiplyChanges[$key]['multiply'] = $jackPotPerUnit;
                        $multiplyChanges[$key]['number'] = $poy['number'];
                        $multiplyChanges[$key]['option'] = $poy['option'];
                        $multiplyChanges[$key]['price'] = $price;
                        $multiplyChanges[$key]['is_close'] = $isNumberClose;
                    }
                    if ($playType->limitByUser == 0) {
                        $isNumberClose = true;
                    }
                }
                if ($setupLevelPlayType) {
                    $jackPotPerUnit = $setupLevelPlayType->jackPotPerUnit;
                } 
                else if ($whereLevelPlayType) {
                    $jackPotPerUnit =  $whereLevelPlayType->jackPotPerUnit;
                } 
                else if ($setupLevelPlayTypeMaxPaid) {
                    $jackPotPerUnit = $setupLevelPlayTypeMaxPaid->jackPotPerUnit;
                }
                else if ($maximumPaid) {
                    $jackPotPerUnit =  $maximumPaid->jackPotPerUnit;
                } 
                 else if ($limitLotteryNumberGame) {
                    $jackPotPerUnit = $limitLotteryNumberGame->jackPotPerUnit;
                    if ((int)$jackPotPerUnit === 0) {
                        $isNumberClose = true;
                    }
                } else if ($limitLotteryNumberGame) {
                    $jackPotPerUnit = $limitLotteryNumberGame->jackPotPerUnit;
                    if ((int)$jackPotPerUnit === 0) {
                        $isNumberClose = true;
                    }
                } else {
                    $discountGame = DiscountGame::find()->where([
                        'playTypeId' => $playType->id,
                        'title' => $thaiSharedGame->title,
                        'status' => Constants::status_active
                    ])->one();
                    if ($discountGame) {
                        $price = $price * $discountGame->discount / 100;

                    }
                    $jackPotPerUnit = $playType->jackpot_per_unit;
                }
                if ($poy['multiply'] != $jackPotPerUnit) {
                    $multiplyChanges[$key]['is_duplicate'] = $poy['is_duplicate'];
                    $multiplyChanges[$key]['last_add_num'] = $poy['last_add_num'];
                    $multiplyChanges[$key]['multiply'] = $jackPotPerUnit;
                    $multiplyChanges[$key]['number'] = $poy['number'];
                    $multiplyChanges[$key]['option'] = $poy['option'];
                    $multiplyChanges[$key]['price'] = $price;
                    $multiplyChanges[$key]['is_close'] = (int)$jackPotPerUnit === 0 ? true : false;
                    $isChange = $isNumberClose ? false : true;
                }
                $total += $price;
            }
            if ($isLimitNumber) {
                return [
                    'result' => 'LIMIT_NUMBER',
                    'message' => $message,
                    'multiply_change' => $multiplyChanges,
                ];
            }
            if ($isNumberClose) {
                return ['result' => 'CLOSE_NUMBER', 'multiply_change' => $multiplyChanges];
            }
            if ($isChange) {
                return ['result' => 'CHANGE', 'multiply_change' => $multiplyChanges];
            }
            $user = User::findOne($userId);
            if ($total > $user->creditBalance) {
                return ['result' => 'BALANCE_NOT_ENOUGH'];
            }
            return ['result' => 'SUCCESS'];
        }
        return ['result' => 'NOT_METHOD_ALLOW'];
    }

    public function actionBuy()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $userId = Yii::$app->user->id;
        $checkQueueProcess = Queue::find()->where([
            'userId' => $userId,
            'status' => Constants::status_active
        ])->count();
        if ($checkQueueProcess) {
            return [
                'result' => 'ERROR',
                'message' => 'กรุณารอสักครู่ แล้วกดส่งโพยอีกครั้ง',
            ];
        }
        if (Yii::$app->request->isPost) {
            $poy = Yii::$app->request->post('poy');
            $poys = json_decode($poy, true);
            $thaiSharedGame = ThaiSharedGame::find()->andWhere('NOW() >= startDate AND endDate >= NOW()')->andWhere([
                'id' => $poys['bet_id'],
                'status' => 1
            ])->one();
            if (!$thaiSharedGame) {
                return ['result' => 'TIME_OUT'];
            }
            $total = 0;
            $isChange = false;
            $isNumberClose = false;
            $message = '';
            $isLimitNumber = false;
            $poyLists = [];
            $poyNumbers = [];
            $isLimitAuto = $thaiSharedGame->limitAuto === 1 ? true : false;
            foreach ($poys['poy_list'] as $key => $poy) {
                $code = PlayType::instance()->getReConvertPlayTypeCode($poy['option']);
                $playType = PlayType::find()->where(['game_id' => $thaiSharedGame->gameId, 'code' => $code])->one();
                if ($poy['price'] < $playType->minimum_play || $poy['price'] > $playType->maximum_play) {
                    return ['result' => 'MINMAX'];
                }
                $totalAmountNumber = ThaiSharedGameChitDetail::find()->joinWith('thaiSharedGameChit')->where([
                    'playTypeId' => $playType->id,
                    'thaiSharedGameId' => $thaiSharedGame->id,
                    'number' => $poy['number'],
                ])->sum('amount');
                if ($totalAmountNumber) {
                    $totalBuyNumber = $poy['price'] + $totalAmountNumber;
                }else{
                    $totalBuyNumber = $totalAmountNumber;

                    // เช็คราคาเริ่มต้นซื้อมาเท่าไร
                    $whereLevelPlayType = SetupLevelPlaytype::find()->where([
                        'codePlayType' => $playType->code,
                    ])->andWhere([
                        'like',
                        'gameId',
                        $thaiSharedGame->gameId
                    ])->andWhere([
                        '<=',
                        'minimumPlay',
                        $poy['price']
                    ])->andWhere([
                        '>=',
                        'maximumPlay',
                        $poy['price']
                    ])->orderBy('maximumPlay DESC')->one();
                }

                // ราคาแทงเกิน maximum 6
                $maximumPaid = SetupLevelPlaytype::find()->where([
                    'codePlayType' => $playType->code,
                ])->andWhere(['like','gameId',$thaiSharedGame->gameId])
                ->andWhere(['<=','maximumPlay',$poy['price']
                ])->orderBy('maximumPlay DESC')->one();


                $setupLevelPlayType = SetupLevelPlaytype::find()->where([
                    'codePlayType' => $playType->code,
                ])->andWhere([
                    'like',
                    'gameId',
                    $thaiSharedGame->gameId
                ])->andWhere([
                    '<=',
                    'minimumPlay',
                    $totalBuyNumber
                ])->andWhere([
                    '>=',
                    'maximumPlay',
                    $totalBuyNumber
                ])->orderBy('maximumPlay DESC')->one();

                // ลดราคาจ่ายเอาตัวที่เกิน max
                $setupLevelPlayTypeMaxPaid = SetupLevelPlaytype::find()->where([
                    'codePlayType' => $playType->code,
                ])->andWhere(['like','gameId',$thaiSharedGame->gameId])
                ->andWhere(['<=','maximumPlay',$totalBuyNumber
                ])->orderBy('maximumPlay DESC')->one();


                $limitLotteryNumberGame = LimitLotteryNumberGame::find()->where([
                    'thaiSharedGameId' => $thaiSharedGame->id,
                    'number' => $poy['number'],
                    'playTypeId' => $playType->id
                ])->orderBy('createdAt DESC')->one();
                if (!$limitLotteryNumberGame && $isLimitAuto) {
                    $totalAmountNumber = ThaiSharedGameChitDetail::find()->joinWith('thaiSharedGameChit')->where([
                        'number' => $poy['number'] ,
                        'playTypeId' => $playType->id,
                        'thaiSharedGameId' => $thaiSharedGame->id,
                    ])->sum('amount');
                    if (!$totalAmountNumber) {
                        $totalBuyNumber = $poy['price'] + $totalAmountNumber;
                    }else{
                        $totalBuyNumber = $totalAmountNumber;
                    }
                    $limitLotteryNumberGame = LimitAutoLotteryNumberGame::find()->where([
                        'thaiSharedGameId' => $thaiSharedGame->id,
                        'playTypeId' => $playType->id,
                    ])->andWhere(['<', 'minimumRate', $totalBuyNumber])->andWhere(['>=', 'maximumRate', $totalBuyNumber])->orderBy('maximumRate DESC')->one();
                }
                $price = $poy['price'];
                if ($playType->limitByUser) {
                    $totalAmount = ThaiSharedGameChit::find()->joinWith('thaiSharedGameChitDetail')->where([
                        'thaiSharedGameId' => $thaiSharedGame->id,
                        'number' => $poy['number'],
                        'playTypeId' => $playType->id,
                        ThaiSharedGameChitDetail::tableName().'.userId' => $userId,
                    ])->sum('amount');
                    if (isset($poyLists[$playType->code][$poy['number']])) {
                        $totalBuy = $totalAmount + $poyLists[$playType->code][$poy['number']] + $price;
                    } else {
                        $totalBuy = $totalAmount + $price;
                        $poyLists[$playType->code][$poy['number']] = 0;
                    }
                    $poyLists[$playType->code][$poy['number']] = $poyLists[$playType->code][$poy['number']] + $price;
                    if ($playType->limitByUser < $totalBuy) {
                        $limitBuy = $playType->limitByUser - $totalAmount;
                        if ($limitBuy > 0) {
                            $message = 'ยอดแทงเกินกว่ายอดจำกัดจำนวนการแทงต่อสมาชิก';
                        } else {
                            $message .= $playType->title.' เลข '.$poy['number'].' ปิดรับแทง <br>';
                        }
                        $isLimitNumber = true;
                    }
                    if ($playType->limitByUser == 0) {
                        $isNumberClose = true;
                    }
                }
                if ($setupLevelPlayType) {
                    $jackPotPerUnit = $setupLevelPlayType->jackPotPerUnit;
                } 
                else if ($whereLevelPlayType) {
                    $jackPotPerUnit =  $whereLevelPlayType->jackPotPerUnit;
                } 
                else if ($setupLevelPlayTypeMaxPaid) {
                    $jackPotPerUnit = $setupLevelPlayTypeMaxPaid->jackPotPerUnit;
                } 
                else if ($maximumPaid) {
                    $jackPotPerUnit =  $maximumPaid->jackPotPerUnit;
                } 
                else {
                    $discountGame = DiscountGame::find()->where([
                        'playTypeId' => $playType->id,
                        'title' => $thaiSharedGame->title,
                        'status' => Constants::status_active
                    ])->one();
                    if ($discountGame) {
                        $discountTotal = $price * $discountGame->discount / 100;
                        $price = $price - $discountTotal;
                    }
                    $jackPotPerUnit = $playType->jackpot_per_unit;
                }
                if ($poy['multiply'] != $jackPotPerUnit) {
                    $multiplyChanges[$key]['is_duplicate'] = $poy['is_duplicate'];
                    $multiplyChanges[$key]['last_add_num'] = $poy['last_add_num'];
                    $multiplyChanges[$key]['multiply'] = $jackPotPerUnit;
                    $multiplyChanges[$key]['number'] = $poy['number'];
                    $multiplyChanges[$key]['option'] = $poy['option'];
                    $multiplyChanges[$key]['price'] = $price;
                    $multiplyChanges[$key]['is_close'] = (int)$jackPotPerUnit === 0 ? true : false;
                    $isChange = $isNumberClose ? false : true;
                }
                $total += $price;
            }
            if ($isLimitNumber) {
                return [
                    'result' => 'LIMIT_NUMBER',
                    'message' => $message,
                ];
            }
            if ($isNumberClose) {
                return ['result' => 'CLOSE_NUMBER', 'multiply_change' => $multiplyChanges];
            }
            if ($isChange) {
                return ['result' => 'CHANGE', 'multiply_change' => $multiplyChanges];
            }
            $user = User::findOne($userId);
            $sumWaittingPostWithDraw = PostCreditTransection::find()
                ->where(['poster_id' => $userId, 'action_id' => Constants::action_credit_withdraw, 'status' => Constants::status_waitting])
                ->sum('amount');
            if ($total > ($user->creditBalance - $sumWaittingPostWithDraw)) {
                return ['result' => 'BALANCE_NOT_ENOUGH'];
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
                $totalAmount = 0;
                $totalDiscount = 0;
                foreach ($poys['poy_list'] as $poy) {
                    $price = $poy['price'];
                    $code = PlayType::instance()->getReConvertPlayTypeCode($poy['option']);
                    $playType = PlayType::find()->select(['id', 'title'])->where([
                        'code' => $code,
                        'game_id' => $thaiSharedGame->gameId
                    ])->one();
                    $discountGame = DiscountGame::find()->where([
                        'playTypeId' => $playType->id,
                        'title' => $thaiSharedGame->title,
                        'status' => Constants::status_active
                    ])->one();
                    if ($discountGame) {
                        $discountTotal = $price * $discountGame->discount / 100;
                        $discountPrice = $price - $discountTotal;
                    }

                    $model = new ThaiSharedGameChitDetail();
                    $model->thaiSharedGameChitId = $thaiSharedGameChit->id;
                    $model->number = $poy['number'];
                    $model->playTypeId = $playType->id;
                    $model->amount = $price;
                    $model->createdAt = date('Y-m-d H:i:s', time());
                    $model->createdBy = $userId;
                    $model->userId = $userId;
                    $model->jackPotPerUnit = $poy['multiply'];
                    if (isset($discountPrice)) {
                        $model->discount = $discountPrice;
                        $totalDiscount += $model->discount;
                    }

                    if (!$model->save()) {
                        throw new ServerErrorHttpException('Can Not Save ThaiSharedGameChitDetail');
                    }
                    $totalAmount += $model->amount;
                }
                $thaiSharedGameChit->totalAmount = $totalAmount;
                $thaiSharedGameChit->totalDiscount = $totalDiscount;
                $thaiSharedGameChit->updatedBy = $userId;
                $thaiSharedGameChit->updatedAt = date('Y-m-d H:i:s', time());
                if (!$thaiSharedGameChit->save()) {
                    throw new ServerErrorHttpException('Can Not Update ThaiSharedGameChit');
                }
                //create transection
                $reason = 'แทงพนัน ' . $thaiSharedGame->title . ' / ' . date('d/m/Y') . ' #' . $thaiSharedGame->gameId.$thaiSharedGameChit->id;
                if ($thaiSharedGameChit->totalDiscount > 0) {
                    $resutl = Credit::creditWalk(Constants::action_credit_withdraw, $userId, $userId, Constants::reason_credit_bet_play, $thaiSharedGameChit->totalDiscount, $reason);
                } else {
                    $resutl = Credit::creditWalk(Constants::action_credit_withdraw, $userId, $userId, Constants::reason_credit_bet_play, $thaiSharedGameChit->totalAmount, $reason);
                }
                $transaction->commit();
                return [
                    'result' => 'SUCCESS',
                    'thaiSharedGameChitId' => $thaiSharedGameChit->id,
                    'poy_id' => $thaiSharedGameChit->id,
                    'date' => date('d/m/Y', strtotime($thaiSharedGameChit->createdAt)),
                    'time' => date('H:i:s', strtotime($thaiSharedGameChit->createdAt))
                ];
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            }
        }
        return ['result' => 'NOT_METHOD_ALLOW'];
    }

    public function actionYeekeePresendPoy()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $userId = Yii::$app->user->id;
        if (Yii::$app->request->isPost) {
//            $checkQueueProcess = Queue::find()->where([
//                'userId' => $userId,
//                'status' => Constants::status_active
//            ])->count();
//            if ($checkQueueProcess) {
//                return [
//                    'result' => 'ERROR',
//                    'message' => 'ระบบกำลังประมวลผลอยู่ กรุณาแทงใหม่อีกครั้ง ภายใน 60 วินาที',
//                ];
//            }
            $poy = Yii::$app->request->post('poy');
            $poys = json_decode($poy, true);
            $yeekeeGame = Yeekee::find()->andWhere('NOW() >= start_at AND finish_at >= NOW()')->andWhere(['id' => $poys['bet_id'], 'status' => 1])->one();
            if (!$yeekeeGame) {
                return ['result' => 'TIME_OUT'];
            }
            $total = 0;
            $isChange = false;
            $isNumberClose = false;
            foreach ($poys['poy_list'] as $key => $poy) {
                $code = PlayType::instance()->getReConvertPlayTypeCode($poy['option']);
                $playType = PlayType::find()->where(['game_id' => Constants::YEEKEE, 'code' => $code])->one();
                if ($poy['price'] < $playType->minimum_play || $poy['price'] > $playType->maximum_play) {
                    return ['result' => 'MINMAX'];
                }
                $price = $poy['price'];
                $jackPotPerUnit = $playType->jackpot_per_unit;
                if ($poy['multiply'] != $jackPotPerUnit) {
                    $multiplyChanges[$key]['is_duplicate'] = $poy['is_duplicate'];
                    $multiplyChanges[$key]['last_add_num'] = $poy['last_add_num'];
                    $multiplyChanges[$key]['multiply'] = $jackPotPerUnit;
                    $multiplyChanges[$key]['number'] = null;
                    $multiplyChanges[$key]['option'] = $poy['option'];
                    $multiplyChanges[$key]['price'] = $price;
                    $multiplyChanges[$key]['is_close'] = $isNumberClose;
                    $isChange = true;
                }
                $total += $price;
            }
            if ($isNumberClose) {
                return ['result' => 'CLOSE_NUMBER', 'multiply_change' => $multiplyChanges];
            }
            if ($isChange) {
                return ['result' => 'CHANGE', 'multiply_change' => $multiplyChanges];
            }
            $user = User::findOne($userId);
            if ($total > $user->creditBalance) {
                return ['result' => 'BALANCE_NOT_ENOUGH'];
            }
            return ['result' => 'SUCCESS'];
        }
        return ['result' => 'NOT_METHOD_ALLOW'];
    }


    public function actionBuyYeekee()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $userId = Yii::$app->user->id;
        if (Yii::$app->request->isPost) {
            $checkQueueProcess = Queue::find()->where([
                'userId' => $userId,
                'status' => Constants::status_active
            ])->count();
            if ($checkQueueProcess) {
                return [
                    'result' => 'ERROR',
                    'message' => 'กรุณารอสักครู่ แล้วกดส่งโพยอีกครั้ง',
                ];
            }
            $poy = Yii::$app->request->post('poy');
            $poys = json_decode($poy, true);
            $yeekeeGame = YeekeeSearch::find()->andWhere('NOW() >= start_at AND finish_at >= NOW()')->andWhere(['id' => $poys['bet_id'], 'status' => 1])->one();
            if (!$yeekeeGame) {
                return ['result' => 'TIME_OUT'];
            }
            $total = 0;
            $isChange = false;
            foreach ($poys['poy_list'] as $key => $poy) {
                $code = PlayType::instance()->getReConvertPlayTypeCode($poy['option']);
                $playType = PlayType::find()->where(['game_id' => Constants::YEEKEE, 'code' => $code])->one();
                if ($poy['price'] < $playType->minimum_play || $poy['price'] > $playType->maximum_play) {
                    return ['result' => 'MINMAX'];
                }
                $price = $poy['price'];
                $jackPotPerUnit = $playType->jackpot_per_unit;
                if ($poy['multiply'] != $jackPotPerUnit) {
                    $multiplyChanges[$key]['is_duplicate'] = $poy['is_duplicate'];
                    $multiplyChanges[$key]['last_add_num'] = $poy['last_add_num'];
                    $multiplyChanges[$key]['multiply'] = $jackPotPerUnit;
                    $multiplyChanges[$key]['number'] = null;
                    $multiplyChanges[$key]['option'] = $poy['option'];
                    $multiplyChanges[$key]['price'] = $price;
                    $isChange = true;
                }
                $total += $price;
            }
            if ($isChange) {
                return ['result' => 'CHANGE', 'multiply_change' => $multiplyChanges];
            }
            $user = User::findOne($userId);
            $sumWaittingPostWithDraw = PostCreditTransection::find()
                ->where(['poster_id' => $userId, 'action_id' => Constants::action_credit_withdraw, 'status' => Constants::status_waitting])
                ->sum('amount');
            if ($total > ($user->creditBalance - $sumWaittingPostWithDraw)) {
                return ['result' => 'BALANCE_NOT_ENOUGH'];
            }
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $yeekeeChit = new YeekeeChit();
                $yeekeeChit->user_id = $user->id;
                $yeekeeChit->yeekee_id = $yeekeeGame->id;
                $yeekeeChit->create_at = date('Y-m-d H:i:s', time());
                $yeekeeChit->create_by = $user->id;;
                $yeekeeChit->status = Constants::status_playing;
                if (!$yeekeeChit->save()) {
                    throw new ServerErrorHttpException('Can Not Save Yeekee Chit');

                }
                foreach ($poys['poy_list'] as $poy) {
                    $code = PlayType::instance()->getReConvertPlayTypeCode($poy['option']);
                    $playType = PlayType::find()->select(['id', 'title', 'code'])->where([
                        'code' => $code,
                        'game_id' => Constants::YEEKEE
                    ])->one();
                    if (!$playType) {
                        return ['result' => 'NOT_METHOD_ALLOW'];
                    }
                    $model = new YeekeeChitDetail();
                    $model->yeekee_chit_id = $yeekeeChit->id;
                    $model->number = $poy['number'];
                    $model->play_type_code = $playType->code;
                    $model->amount = $poy['price'];
                    $model->create_at = date('Y-m-d H:i:s');
                    $model->create_by = $user->id;
                    if (!$model->save()) {
                        throw new ServerErrorHttpException('Can Not Save YeekeeChitDetail');
                    }
                    $yeekeeChit->total_amount += $model->amount;
                }
                $yeekeeChit->update_by = $user->id;
                $yeekeeChit->update_at = date('Y-m-d H:i:s', time());
                if (!$yeekeeChit->save()) {
                    throw new ServerErrorHttpException('Can Not Update YeekeeChit');
                }
                //create transection
                $reason = 'แทงพนัน จับยี่กี รอบที่ ' . $yeekeeGame->round . ' / ' . date('d/m/Y', strtotime($yeekeeGame->date_at)) . ' #' . $yeekeeChit->getOrder();
                $resutl = Credit::creditWalk(Constants::action_credit_withdraw, $user->id, $user->id, Constants::reason_credit_bet_play, $yeekeeChit->total_amount, $reason);
                $transaction->commit();
                return [
                    'result' => 'SUCCESS',
                    'yeekeeChitId' => $yeekeeChit->id,
                    'poy_id' => $yeekeeChit->id,
                    'date' => date('d/m/Y', strtotime($yeekeeChit->create_at)),
                    'time' => date('H:i:s', strtotime($yeekeeChit->create_at))
                ];
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            }
        }
        return ['result' => 'NOT_METHOD_ALLOW'];
    }

    public function actionYeekeeNumber()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $user = Yii::$app->user->identity;
        if (Yii::$app->request->isPost) {
            $ynum = Yii::$app->request->post('ynum');
            $yeekee = YeekeeSearch::find()->select(['id', 'status', 'finish_at'])->where(['id' => $ynum['id']])->one();
            if (empty($yeekee)) {
                return ['data' => 'TIME_OUT'];
            }

            //เพราะยิงต่อได้ 2 นาที
            $finishTime = strtotime("+1 minutes +60 seconds", strtotime($yeekee->finish_at));
            if (time() >= $finishTime) {
                return ['data' => 'TIME_OUT'];
            }

            $yeekeePost = YeekeePost::find()->where(['yeekee_id' => $ynum['id'], 'username' => Yii::$app->user->identity->username])->orderBy('create_at DESC')->one();
            $now = strtotime(date('Y-m-d H:i:s'));
            if (!isset($yeekeePost->create_at)){
                $savedTime = strtotime(date("Y-m-d H:i:s", strtotime("-15 second")));
            }else{
                $savedTime = strtotime($yeekeePost->create_at);
            }
            if (strtotime("-9 second") >  $savedTime) {
                $model = new YeekeePost();
                $model->yeekee_id = $ynum['id'];
                $model->post_num = $ynum['ying'];
                $model->create_at = date('Y-m-d H:i:s');
                $model->create_by = $user->id;
                $model->username = $user->username;
                $model->post_name = $user->username;
                $model->is_bot = 0;
                $model->order = time();
                if (!$model->save()) {
                    return [
                        'data' => 'ERROR'
                    ];
                }
                return [
                    'data' => 'SUCCESS'
                ];
            }else{
                return [
                    'data' => 'TIME_AWAIT'
                ];
            }
        }
        return [
            'data' => 'ERROR'
        ];
    }

    public function actionYeekeePost()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (Yii::$app->request->isPost) {
            $id = Yii::$app->request->post('id');
            $yeekeePosts = YeekeePost::find()->where(['yeekee_id' => $id])->orderBy('create_at DESC')->limit(20)->all();
            $ying = [];
            foreach ($yeekeePosts as $key => $yeekeePost) {
                $split_postname = str_split($yeekeePost->post_name);
                $username = '';
                for ($i =0; $i < count($split_postname); $i++) {
                    if($i === 2 || $i === 3 || $i === 4) {
                        $username .= '*';
                    }else{
                        $username .= $split_postname[$i];
                    }
                }
                $ying[$key]['username'] = $username;
                $ying[$key]['ying'] = str_pad($yeekeePost->post_num, 5, '0', STR_PAD_LEFT);
                $ying[$key]['dt'] = $yeekeePost->create_at;
            }
            $sumResult = $this->getSumYeekee($id);
            return [
                'lang_dltime' => 'เวลาที่ส่ง',
                'lang_memsend' => 'ชื่อสมาชิกผู้ส่งเลข',
                'lang_rank' => 'อันดับ',
                'total_ying' => $sumResult,
                'ying' => $ying
            ];
        }
        return [
            'data' => 'ERROR'
        ];
    }


    function getSumYeekee($yeekee_id = null)
    {
        $yeekeePost = YeekeePost::find()->select([
            'post_num'
        ])->where([
            'yeekee_id' => $yeekee_id
        ])->all();

        $sum = 0;
        foreach ($yeekeePost as $post) {
            $sum += $post ['post_num'];
        }
        return $sum;
    }
}