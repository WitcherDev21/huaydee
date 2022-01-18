<?php

namespace frontend\controllers;

use common\libs\Constants;
use common\models\ConditionLimitNumber;
use common\models\Credit;
use common\models\DiscountGame;
use common\models\NumberMemo;
use common\models\PlayType;
use common\models\ThaiSharedAnswerGame;
use common\models\ThaiSharedGame;
use common\models\ThaiSharedGameChit;
use common\models\ThaiSharedGameChitDetail;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

/**
 * Created by PhpStorm.
 * User: topte
 * Date: 9/29/2018
 * Time: 12:50 AM
 */
class ThaiSharedChitController extends Controller
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
        $governmentlotteryGames = ThaiSharedGame::find()->where([
            'gameId' => [Constants::LOTTERYGAME, Constants::LOTTERYGAMEDISCOUNT],
            'typeSharedGameId' => [4,5]
        ])->andWhere([
            '<>',
            'disabled',
            0
        ])->andWhere( 'DATE(startDate) <= CURDATE()')->
        andWhere('DATE(endDate) >= CURDATE()')->groupBy('title')->orderBy('endDate DESC')->all();
        $vietnamMalaySharedGames = ThaiSharedGame::find()->where([
            'gameId' => [
                Constants::THAISHARED,
                Constants::VIETNAMVIP,
                Constants::GSB_THAISHARD_GAME,
                Constants::BACC_THAISHARD_GAME,
                Constants::LAOS_CHAMPASAK_LOTTERY_GAME,
                Constants::VIETNAM4D_GAME,
                Constants::LOTTERYRESERVEGAME
            ],
            'typeSharedGameId' => [1, 2],
            'title' => [
                'เวียดนาม/ฮานอย (พิเศษ)',
                'เวียดนาม/ฮานอย',
                'หวยมาเลย์',
                'หวยลาว',
                'หวยฮานอย VIP',
                'หวยออมสิน',
                'หวย ธกส',
                'หวยลาว จำปาสัก',
                'หวยฮานอย 4D',
                'หวยลาวทดแทน'
            ]
        ])->andWhere([
            '<>',
            'disabled',
            0
        ])->andWhere( 'DATE(startDate) <= CURDATE()')->
        andWhere('DATE(endDate) >= CURDATE()')->groupBy('title')->orderBy('endDate DESC')->all();
        $lotteryGames = (object) array_merge((array) $governmentlotteryGames, (array) $vietnamMalaySharedGames);
        $thaiSharedGames = ThaiSharedGame::find()->where([
            'gameId' => [
                Constants::THAISHARED,
                Constants::GSB_THAISHARD_GAME,
                Constants::BACC_THAISHARD_GAME
            ],
            'typeSharedGameId' => [1, 2],
        ])->andWhere([
            '<>',
            'disabled',
            0
        ])->andWhere(['NOT IN', 'title', [
            'เวียดนาม/ฮานอย (พิเศษ)',
            'เวียดนาม/ฮานอย',
            'หวยมาเลย์',
            'หวยลาว',
            'หวยออมสิน',
            'หวย ธกส',
            'หวยฮานอย 4D',
            'หวยลาวทดแทน'
        ]])->
        andWhere( 'DATE(startDate) <= CURDATE()')->
        andWhere('DATE(endDate) >= CURDATE()')->orderBy('endDate DESC')->all();
        return $this->render('index', [
            'lotteryGames' => $lotteryGames,
            'thaiSharedGames' => $thaiSharedGames,
        ]);
    }

    public function actionPlay($id)
    {
        $this->layout = 'buy_lottery';
        $thaiSharedGame = ThaiSharedGame::find()->andWhere('NOW() >= startDate AND endDate >= NOW()')->andWhere(['id' => $id, 'status' => 1])->one();
        if (!$thaiSharedGame) {
            $thaiSharedGameObj = ThaiSharedGame::findOne($id);
            if (!$thaiSharedGameObj) {
                throw new NotFoundHttpException();
            }
            $threeTopAnswer = ThaiSharedAnswerGame::find()->select('number')->where([
                'thaiSharedGameId' => $thaiSharedGameObj->id,
                'code' => 'three_top'
            ])->innerJoinWith('playType')->one();
            $twoTopAnswer = ThaiSharedAnswerGame::find()->select('number')->where([
                'thaiSharedGameId' => $thaiSharedGameObj->id,
                'code' => 'two_top'
            ])->innerJoinWith('playType')->one();
            $twoUnderAnswer = ThaiSharedAnswerGame::find()->select(['number', 'title'])->where([
                'thaiSharedGameId' => $thaiSharedGameObj->id,
                'code' => 'two_under'
            ])->innerJoinWith('playType')->one();
            $lotteryAnswer = [
                'title' => $thaiSharedGameObj->title,
                'description' => $thaiSharedGameObj->description,
                'three_top' => [
                    'number' => isset($threeTopAnswer) ? $threeTopAnswer->number : 'xxx',
                    'title' => '3 ตัวบน',
                ],
                'two_top' => [
                    'number' => isset($twoTopAnswer) ? $twoTopAnswer->number : 'xxx',
                    'title' => '2 ตัวบน',
                ],
                'two_under' =>  [
                    'number' => isset($twoUnderAnswer) ? $twoUnderAnswer->number : 'xxx',
                    'title' => '2 ตัวล่าง',
                ],
            ];
            return $this->render('play-result', [
                'lotteryAnswer' => $lotteryAnswer,
            ]);
        }
        $playTypeObj = PlayType::find()->where(['game_id' => $thaiSharedGame->gameId])->orderBy('sort ASC')->all();
        foreach ($playTypeObj as $key => $playType) {
            $discountGame = DiscountGame::find()->where([
                'playTypeId' => $playType->id,
                'title' => $thaiSharedGame->title,
                'status' => Constants::status_active
            ])->one();
            $jackpotPerUnit = $playType->jackpot_per_unit;
            $playTypeCode = PlayType::instance()->getConvertPlayTypeCode($playType->code);
            $playTypes[$playTypeCode]['title'] = $playType->title;
            $playTypes[$playTypeCode]['code'] = $playType->code;
            $playTypes[$playTypeCode]['jackpot_per_unit'] = $jackpotPerUnit;
            $playTypes[$playTypeCode]['minimum_play'] = $playType->minimum_play;
            $playTypes[$playTypeCode]['maximum_play'] = $playType->maximum_play;
            $playTypes[$playTypeCode]['maximum_by_user'] = $playType->limitByUser;
            $playTypes[$playTypeCode]['discount'] = isset($discountGame->discount) ? $discountGame->discount : 0;
            $betMaxMin[$key]['bet_option'] = $playTypeCode;
            $betMaxMin[$key]['bet_min'] = $playType->minimum_play;
            $betMaxMin[$key]['bet_max'] = $playType->maximum_play;
        };
        $betListDetail = json_encode($this->betListDetail($thaiSharedGame, $playTypes, $betMaxMin), true);

        //ดึงเลขชุด
        $numberMemoList = NumberMemo::find()->where(['user_id' => \Yii::$app->user->id, 'gameId' => Constants::THAISHARED])->all();
        $poyList = json_encode(['bet_id' => $thaiSharedGame->id, 'poy_list' => []]);
        return $this->render('play', [
            'thaiSharedGame' => $thaiSharedGame,
            'numberMemoList' => $numberMemoList,
            'poyList' => $poyList,
            'playTypes' => $playTypes,
            'betListDetail' => $betListDetail,
        ]);
    }

    public function actionPrintPoy($id)
    {
        $userId = Yii::$app->user->id;
        $thaiSharedGameChit = ThaiSharedGameChit::find()->where(['userId' => $userId, 'id' => $id])->one();
        $thaiSharedGameChitDetail = ThaiSharedGameChitDetail::find()->select('playTypeId')->where([
            'thaiSharedGameChitId' => $thaiSharedGameChit->id
        ])->groupBy('playTypeId')->asArray()->all();
        $playTypes = PlayType::find()->where(['id' => ArrayHelper::getColumn($thaiSharedGameChitDetail,'playTypeId')])->all();
        return $this->render('modal_print_poy', [
            'thaiSharedGameChit' => $thaiSharedGameChit,
            'playTypes' => $playTypes,
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
            if ($thaiSharedGameChit->getCanReChit()) {
                $remark = 'คืนโพย '.$thaiSharedGameChit->thaiSharedGame->title . ' / ' . date('d/m/Y') . ' #' . $thaiSharedGameChit->getOrder();
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
                ThaiSharedGameChitDetail::updateAll(['amount' => 0, 'discount' => 0],['thaiSharedGameChitId' => $thaiSharedGameChit->id]);
            }
            $transaction->commit();
            return true;
        }   catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
        return false;
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

    protected function BetListDetail($thaiSharedGame, $playTypes, $betMinmMax)
    {
        $betListDetail = [
            'bet_id' => $thaiSharedGame->id,
            'bet_type' => 'STOCK',
            'bet_name' => $thaiSharedGame->title,
            'bet_round' => '',
            'open_dt' => $thaiSharedGame->startDate,
            'close_dt' => $thaiSharedGame->endDate,
            'set_result' => 0,
            'abort' => 0,
            'teng_bon_1' => isset($playTypes['teng_bon_1']['jackpot_per_unit']) ? $playTypes['teng_bon_1']['jackpot_per_unit'] : 0,
            'teng_lang_1' => isset($playTypes['teng_lang_1']['jackpot_per_unit']) ? $playTypes['teng_lang_1']['jackpot_per_unit'] : 0,
            'teng_bon_2' => isset($playTypes['teng_bon_2']['jackpot_per_unit']) ? $playTypes['teng_bon_2']['jackpot_per_unit'] : 0,
            'teng_lang_2' => isset($playTypes['teng_lang_2']['jackpot_per_unit']) ? $playTypes['teng_lang_2']['jackpot_per_unit'] : 0,
            'tode_3' => isset($playTypes['tode_3']['jackpot_per_unit']) ? $playTypes['tode_3']['jackpot_per_unit'] : 0,
            'teng_bon_3' => isset($playTypes['teng_bon_3']['jackpot_per_unit']) ? $playTypes['teng_bon_3']['jackpot_per_unit'] : 0,
            'teng_lang_3' => isset($playTypes['teng_lang_3']['jackpot_per_unit']) ? $playTypes['teng_lang_3']['jackpot_per_unit'] : 0,
            'teng_lang_nha_3' => isset($playTypes['teng_lang_nha_3']['jackpot_per_unit']) ? $playTypes['teng_lang_nha_3']['jackpot_per_unit'] : 0,
            'tode_4' => isset($playTypes['tode_4']['jackpot_per_unit']) ? $playTypes['tode_4']['jackpot_per_unit'] : 0,
            'teng_bon_4' => isset($playTypes['teng_bon_4']['jackpot_per_unit']) ? $playTypes['teng_bon_4']['jackpot_per_unit'] : 0,
            'limit' => [],
            'bet_min_max' => $betMinmMax,
            'bet_result' => [],
            'discount' => [
                'teng_bon_1' => isset($playTypes['teng_bon_1']['discount']) ? $playTypes['teng_bon_1']['discount'] : 0,
                'teng_lang_1' => isset($playTypes['teng_lang_1']['discount']) ? $playTypes['teng_lang_1']['discount'] : 0,
                'teng_bon_2' => isset($playTypes['teng_bon_2']['discount']) ? $playTypes['teng_bon_2']['discount'] : 0,
                'teng_lang_2' => isset($playTypes['teng_lang_2']['discount']) ? $playTypes['teng_lang_2']['discount'] : 0,
                'tode_3' => isset($playTypes['tode_3']['discount']) ? $playTypes['tode_3']['discount'] : 0,
                'teng_bon_3' => isset($playTypes['teng_bon_3']['discount']) ? $playTypes['teng_bon_3']['discount'] : 0,
                'teng_lang_3' => isset($playTypes['teng_lang_3']['discount']) ? $playTypes['teng_lang_3']['discount'] : 0,
                'teng_lang_nha_3' => isset($playTypes['teng_lang_nha_3']['discount']) ? $playTypes['teng_lang_nha_3']['discount'] : 0,
                'tode_4' => isset($playTypes['tode_4']['discount']) ? $playTypes['tode_4']['discount'] : 0,
                'teng_bon_4' => isset($playTypes['teng_bon_4']['discount']) ? $playTypes['teng_bon_4']['discount'] : 0,
            ],
        ];
        return $betListDetail;
    }
}
