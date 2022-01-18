<?php

namespace frontend\controllers;

use common\models\PlayType;
use common\models\ThaiSharedGameChit;
use common\models\ThaiSharedGameChitDetail;
use yii\filters\AccessControl;
use yii\rest\Controller;
use common\libs\Constants;
use common\models\NumberMemo;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class ApiNumberMemoController extends Controller {

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
        ];
    }

    public function actionIndex()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $userId = Yii::$app->user->id;
        $numberMemos = NumberMemo::find()->where([
            'user_id' => $userId,
            'gameId' => [
                Constants::THAISHARED,
                Constants::LAOS_CHAMPASAK_LOTTERY_GAME,
                Constants::GSB_THAISHARD_GAME,
                Constants::BACC_THAISHARD_GAME,
                Constants::LOTTERYGAME,
                Constants::LOTTERYGAMEDISCOUNT,
                Constants::VIETNAMVIP,
                Constants::VIETNAM4D_GAME,
                Constants::LOTTERYRESERVEGAME
            ],
        ])->all();
        $setNumberList = [];
        foreach ($numberMemos as $index => $numberMemo) {
            $numberPlayTypes = json_decode($numberMemo->json_value, true);
            $key = 0;
            foreach ($numberPlayTypes as $playType => $numberPlayType) {
                $numbers = json_decode($numberPlayType);
                $playTypeCode = PlayType::instance()->getConvertPlayTypeCode($playType);
                foreach ($numbers as $number => $value) {
                    $setNumber[$key]['bet_option'] = $playTypeCode;
                    $setNumber[$key]['number'] = $number;
                    $key++;
                }
            }
            $setNumberList[$index]['id'] = $numberMemo->id;
            $setNumberList[$index]['set_name'] = $numberMemo->title;
            $setNumberList[$index]['set_number'] = $setNumber;
            $setNumberList[$index]['dt'] = $numberMemo->create_at;
        }
        $numberMemoSets = [
            'result' => 'success',
            'set_number' => $setNumberList,
        ];
        return $numberMemoSets;
    }

    public function actionMyPoy()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $userId = Yii::$app->user->id;
        $thaiSharedGameLists = ThaiSharedGameChit::find()->joinWith('thaiSharedGame')
            ->where([
                'userId' => $userId,
                'gameId' => [
                    Constants::THAISHARED,
                    Constants::LAOS_CHAMPASAK_LOTTERY_GAME,
                    Constants::GSB_THAISHARD_GAME,
                    Constants::BACC_THAISHARD_GAME,
                    Constants::LOTTERYGAME,
                    Constants::LOTTERYGAMEDISCOUNT,
                    Constants::VIETNAMVIP,
                    Constants::VIETNAM4D_GAME,
                    Constants::LOTTERYRESERVEGAME
                ],
            ])
            ->orderBy(['createdAt' => SORT_DESC])
            ->limit(50)
            ->all();
        $setNumberList = [];
        foreach ($thaiSharedGameLists as $index => $thaiSharedGameList) {
            $setNumberList[$index]['poy_id'] = $thaiSharedGameList->id;
            $setNumberList[$index]['bet_name'] = $thaiSharedGameList->thaiSharedGame->title;
            $thaiSharedGameDetails = $thaiSharedGameList->thaiSharedGameChitDetails;
            foreach ($thaiSharedGameDetails as $key => $thaiSharedGameDetail) {
                $setNumber[$key]['option'] = $thaiSharedGameDetail->playType->code;
                $setNumber[$key]['number'] = $thaiSharedGameDetail->number;
                $setNumber[$key]['price'] = $thaiSharedGameDetail->amount;
                $setNumber[$key]['multiply'] = $thaiSharedGameDetail->playType->jackpot_per_unit;
                $key++;
            }
            $setNumberList[$index]['poy_list'] = $setNumber;
            $setNumberList[$index]['dt'] = $thaiSharedGameList->createdAt;
        }
        $numberMemoSets = [
            'result' => 'success',
            'poy' => $setNumberList,
        ];
        return $numberMemoSets;
    }

    public function actionDetail($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $userId = Yii::$app->user->id;
        $numberMemo = NumberMemo::find()->where(['id' => $id, 'user_id' => $userId])->one();
        if (!$numberMemo) {
            throw new NotFoundHttpException();
        }
        $numberPlayTypes = json_decode($numberMemo->json_value, true);
        $key = 0;
        foreach ($numberPlayTypes as $playType => $numberPlayType) {
            $numbers = json_decode($numberPlayType);
            $playTypeCode = PlayType::instance()->getConvertPlayTypeCode($playType);
            foreach ($numbers as $number => $value) {
                $setNumber[$key]['bet_option'] = $playTypeCode;
                $setNumber[$key]['number'] = $number;
                $key++;
            }
        }
        $setNumberList['id'] = $numberMemo->id;
        $setNumberList['set_name'] = $numberMemo->title;
        $setNumberList['set_number'] = $setNumber;
        $setNumberList['dt'] = $numberMemo->create_at;
        $numberMemoSet = [
            'result' => 'success',
            'mysetnumber' => $setNumberList,
        ];
        return $numberMemoSet;
    }

    public function actionMyPoyDetail($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $userId = Yii::$app->user->id;
        $thaiSharedGameList = ThaiSharedGameChit::find()->joinWith('thaiSharedGame')->where([
            'userId' => $userId,
            ThaiSharedGameChit::tableName().'.id' => $id,
        ])->one();
        if (!$thaiSharedGameList) {
            throw new NotFoundHttpException();
        }
        $key = 0;
        $setNumberList['poy_id'] = $thaiSharedGameList->id;
        $setNumberList['bet_name'] = $thaiSharedGameList->thaiSharedGame->title;
        $setNumberList['bet_type'] = 'STOCK';
        $setNumberList['win'] = $thaiSharedGameList->totalWinCredit;
        $setNumberList['total_price'] = $thaiSharedGameList->totalAmount;

        $thaiSharedGameDetails = $thaiSharedGameList->thaiSharedGameChitDetails;
        foreach ($thaiSharedGameDetails as $key => $thaiSharedGameDetail) {
            $playTypeCode = PlayType::instance()->getConvertPlayTypeCode($thaiSharedGameDetail->playType->code);
            $setNumber[$key]['option'] = $playTypeCode;
            $setNumber[$key]['number'] = $thaiSharedGameDetail->number;
            $setNumber[$key]['price'] = $thaiSharedGameDetail->amount;
            $setNumber[$key]['multiply'] = $thaiSharedGameDetail->playType->jackpot_per_unit;
            $poyList[$key]['bet_option'] = $playTypeCode;
            $poyList[$key]['multiply'] = $thaiSharedGameDetail->playType->jackpot_per_unit;
            $poyList[$key]['number'] = $thaiSharedGameDetail->number;
            $poyList[$key]['poy_id'] = $thaiSharedGameList->id;
            $poyList[$key]['price'] = $thaiSharedGameDetail->amount;
            $poyList[$key]['win'] = $thaiSharedGameDetail->win_credit;
            $poyList[$key]['lose'] = $thaiSharedGameDetail->win_credit;
            $key++;
        }
        $setNumberList['poy_list'] = $setNumber;
        $setNumberList['dt'] = $thaiSharedGameList->createdAt;
        $setNumberList['close_dt'] = $thaiSharedGameList->thaiSharedGame->endDate;

        $numberMemoSets = [
            'result' => 'success',
            'poy' => $setNumberList,
            'poy_list' => $poyList,
        ];
        return $numberMemoSets;
    }
}