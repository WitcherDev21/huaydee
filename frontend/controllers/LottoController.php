<?php

namespace frontend\controllers;

use common\models\LotteryShowNumberResultWin;
use common\models\ThaiSharedAnswerGame;
use common\models\ThaiSharedGame;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\filters\AccessControl;
use common\libs\Constants;
use common\models\Running;
use common\models\YeekeeSearch;


/**
 * Site controller
 */
class LottoController extends Controller
{

    /**
     * {@inheritdoc}
     */
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


    /**
     * Displays homepage.
     *
     * @return mixed
     */

    public function actionReport()
    {
        $thaiLotterys = [];
        if (\Yii::$app->request->get('date')) {
            $date = date('Y-m-d', strtotime(\Yii::$app->request->get('date')));
            $now = date('d-M-Y', strtotime(\Yii::$app->request->get('date')));
            $yeekees = YeekeeSearch::find()->where([
                'date_at' => $date
            ])->groupBy('round')->all();
            $yeekeeLastActive = YeekeeSearch::find()->where(['date_at' => $date])->andWhere([
                '<>',
                'status',
                Constants::status_active
            ])->orderBy('update_at DESC')->limit(1)->one();
            if (!$yeekeeLastActive) {
                $yeekeeLastActive = YeekeeSearch::find()->where(['date_at' => $date])->andWhere([
                    '=',
                    'status',
                    Constants::status_active
                ])->orderBy('round ASC')->limit(1)->one();
            }
        } else {
            $date = date('Y-m-d');
            $now = date('d-M-Y');
            $yeekeeRunning = Running::find()->where([
                'game_id' => Constants::YEEKEE
            ])->orderBy([
                'running' => SORT_DESC
            ])->one();
            $yeekees = YeekeeSearch::find()->where([
                'group' => $yeekeeRunning->running
            ])->groupBy('round')->all();
            $yeekeeLastActive = YeekeeSearch::find()->where(['group' => $yeekeeRunning->running])->andWhere([
                '<>',
                'status',
                Constants::status_active
            ])->orderBy('update_at DESC')->limit(1)->one();
            if (!$yeekeeLastActive) {
                $yeekeeLastActive = YeekeeSearch::find()->where(['group' => $yeekeeRunning->running])->andWhere([
                    '=',
                    'status',
                    Constants::status_active
                ])->orderBy('round ASC')->limit(1)->one();
            }
        }
        $thaiLotteryObjs = ThaiSharedGame::find()->where([
            'gameId' => [Constants::THAISHARED, Constants::VIETNAMVIP, Constants::VIETNAM4D_GAME],
            'typeSharedGameId' => 1,
            'disabled' => 1
        ])->andWhere(['<=', 'DATE(startDate)', $date])->andWhere(['>=', 'DATE(endDate)' , $date])->groupBy('title')->all();
        foreach ($thaiLotteryObjs as $thaiLotteryObj) {
            $threeTopAnswer = ThaiSharedAnswerGame::find()->select('number')->where([
                'thaiSharedGameId' => $thaiLotteryObj->id,
                'code' => 'three_top'
            ])->innerJoinWith('playType')->one();
            $twoUnderAnswer = ThaiSharedAnswerGame::find()->select(['number', 'title'])->where([
                'thaiSharedGameId' => $thaiLotteryObj->id,
                'code' => 'two_under'
            ])->innerJoinWith('playType')->one();
            $thaiLotterys[] = [
                'title' => $thaiLotteryObj->title,
                'three_top' => [
                    'number' => isset($threeTopAnswer) ? $threeTopAnswer->number : 'xxx',
                    'title' => '3 ตัวบน',
                ],
                'two_under' =>  [
                    'number' => isset($twoUnderAnswer) ? $twoUnderAnswer->number : 'xxx',
                    'title' => '2 ตัวล่าง',
                ],
            ];
        }
        $foreignObjs = ThaiSharedGame::find()->where([
            'gameId' => [Constants::THAISHARED, Constants::VIETNAMVIP, Constants::VIETNAM4D_GAME],
            'typeSharedGameId' => 2,
            'disabled' => 1,
        ])->andWhere(['<=', 'DATE(startDate)', $date])->andWhere(['>=', 'DATE(endDate)', $date])->andWhere([
            'NOT IN', 'title', ['หวยออมสิน', 'หวย ธกส']
        ])->groupBy('title')->all();
        $foreignLotterys = [];
        foreach ($foreignObjs as $foreignObj) {
            $threeTopAnswer = ThaiSharedAnswerGame::find()->select('number')->where([
                'thaiSharedGameId' => $foreignObj->id,
                'code' => 'three_top'
            ])->innerJoinWith('playType')->one();
            $twoUnderAnswer = ThaiSharedAnswerGame::find()->select(['number', 'title'])->where([
                'thaiSharedGameId' => $foreignObj->id,
                'code' => 'two_under'
            ])->innerJoinWith('playType')->one();
            $foreignLotterys[] = [
                'title' => $foreignObj->title,
                'three_top' => [
                    'number' => isset($threeTopAnswer) ? $threeTopAnswer->number : 'xxx',
                    'title' => '3 ตัวบน',
                ],
                'two_under' =>  [
                    'number' => isset($twoUnderAnswer) ? $twoUnderAnswer->number : 'xxx',
                    'title' => '2 ตัวล่าง',
                ],
            ];
        }
        $lotteryGameThaiShared = ThaiSharedGame::find()->where([
            'gameId' =>  Constants::LOTTERYGAME,
            'status' => [Constants::status_finish_show_result, Constants::status_active],
            'typeSharedGameId' => Constants::TYPELOTTERYID,
        ]);
        if (\Yii::$app->request->get('date')) {
            $lotteryGameThaiShared->andWhere(['=', 'DATE(endDate)', $date]);
        }
        $lotteryGameThaiSharedObj = $lotteryGameThaiShared->orderBy('endDate DESC')->limit(1)->one();

        if ($lotteryGameThaiSharedObj) {
            $threeFrontAnswer = ThaiSharedAnswerGame::find()->select('number')->where([
                'thaiSharedGameId' => $lotteryGameThaiSharedObj->id,
                'code' => 'three_top2'
            ])->innerJoinWith('playType')->all();
            $threeFront = implode(',', ArrayHelper::getColumn($threeFrontAnswer, 'number'));
            $threeBackAnswer = ThaiSharedAnswerGame::find()->select('number')->where([
                'thaiSharedGameId' => $lotteryGameThaiSharedObj->id,
                'code' => 'three_und2'
            ])->innerJoinWith('playType')->all();
            $threeBack = implode(',', ArrayHelper::getColumn($threeBackAnswer, 'number'));
            $twoUnderAnswer = ThaiSharedAnswerGame::find()->select('number')->where([
                'thaiSharedGameId' => $lotteryGameThaiSharedObj->id,
                'code' => 'two_under'
            ])->innerJoinWith('playType')->one();
            $lotteryShowNumberResultWin = LotteryShowNumberResultWin::find()->where(['thaiSharedGameId' => $lotteryGameThaiSharedObj->id])->one();
            $gameLotterys = [
                'title' => 'หวยรัฐบาลไทย',
                'date' => date('d-M-Y', strtotime($lotteryGameThaiSharedObj->endDate)),
                'firstResult' => isset($lotteryShowNumberResultWin) ? $lotteryShowNumberResultWin->number : 'xxxxxx',
                'three_front' => [
                    'title' => '3ตัวหน้า',
                    'number' => $threeFront ? $threeFront : 'xxx,xxx',
                ],
                'three_back' => [
                    'title' => '3ตัวล่าง',
                    'number' => $threeBack ? $threeBack : 'xxx,xxx',
                ],
                'two_under' => [
                    'title' => '2ตัวล่าง',
                    'number' => isset($twoUnderAnswer) ? $twoUnderAnswer->number : 'xx',
                ],
            ];
        } else {
            $gameLotterys = [
                'title' => 'หวยรัฐบาลไทย',
                'date' => date('d-M-Y', strtotime($date)),
            ];
        }
        $gsbGameObj = ThaiSharedGame::find()->where([
            'gameId' => Constants::GSB_THAISHARD_GAME,
            'typeSharedGameId' => 1,
            'disabled' => 1,
            'title' => 'หวยออมสิน'
        ]);
        if (\Yii::$app->request->get('date')) {
            $gsbGameObj->andWhere(['=', 'DATE(endDate)', $date]);
        }
        $gsbObj = $gsbGameObj->orderBy('endDate DESC')->limit(1)->one();

        if ($gsbObj) {
            $threeTopGsbAnswer = ThaiSharedAnswerGame::find()->select('number')->where([
                'thaiSharedGameId' => $gsbObj->id,
                'code' => 'three_top'
            ])->innerJoinWith('playType')->one();
            $twoUnderGsbAnswer = ThaiSharedAnswerGame::find()->select(['number', 'title'])->where([
                'thaiSharedGameId' => $gsbObj->id,
                'code' => 'two_under'
            ])->innerJoinWith('playType')->one();
            $gsb = [
                'date' => isset($gsbObj->endDate) ? date('d-M-Y', strtotime($gsbObj->endDate)) : '',
                'result' => $gsbObj->result,
                'three_top' => [
                    'number' => isset($threeTopGsbAnswer) ? $threeTopGsbAnswer->number : 'xxx',
                    'title' => '3 ตัวบน',
                ],
                'two_under' =>  [
                    'number' => isset($twoUnderGsbAnswer) ? $twoUnderGsbAnswer->number : 'xxx',
                    'title' => '2 ตัวล่าง',
                ],
            ];
        } else {
            $gsb = [
                'date' => date('d-M-Y', strtotime($date)),
            ];
        }
        $baccGameObj = ThaiSharedGame::find()->where([
            'gameId' => Constants::BACC_THAISHARD_GAME,
            'typeSharedGameId' => 1,
            'disabled' => 1,
            'title' => 'หวย ธกส'
        ]);
        if (\Yii::$app->request->get('date')) {
            $baccGameObj->andWhere(['=', 'DATE(endDate)', $date]);
        }
        $baccObj = $baccGameObj->orderBy('endDate DESC')->limit(1)->one();

        if ($baccObj) {
            $threeTopBaccAnswer = ThaiSharedAnswerGame::find()->select('number')->where([
                'thaiSharedGameId' => $baccObj->id,
                'code' => 'three_top'
            ])->innerJoinWith('playType')->one();
            $twoUnderBaccAnswer = ThaiSharedAnswerGame::find()->select(['number', 'title'])->where([
                'thaiSharedGameId' => $baccObj->id,
                'code' => 'two_under'
            ])->innerJoinWith('playType')->one();
            $bacc = [
                'date' => isset($baccObj->endDate) ? date('d-M-Y', strtotime($baccObj->endDate)) : '',
                'result' => $baccObj->result,
                'three_top' => [
                    'number' => isset($threeTopBaccAnswer) ? $threeTopBaccAnswer->number : 'xxx',
                    'title' => '3 ตัวบน',
                ],
                'two_under' =>  [
                    'number' => isset($twoUnderBaccAnswer) ? $twoUnderBaccAnswer->number : 'xxx',
                    'title' => '2 ตัวล่าง',
                ],
            ];
        } else {
            $bacc = [
                'date' => date('d-M-Y', strtotime($date)),
            ];
        }

        $laosChampasakGameObj = ThaiSharedGame::find()->where([
            'gameId' => Constants::LAOS_CHAMPASAK_LOTTERY_GAME,
            'typeSharedGameId' => 2,
            'disabled' => 1,
            'title' => 'หวยลาว จำปาสัก'
        ]);
        if (\Yii::$app->request->get('date')) {
            $laosChampasakGameObj->andWhere(['=', 'DATE(endDate)', $date]);
        }
        $laosChampasakObj = $laosChampasakGameObj->orderBy('endDate DESC')->limit(1)->one();

        if ($laosChampasakObj) {
            $threeTopLaosChampasakAnswer = ThaiSharedAnswerGame::find()->select('number')->where([
                'thaiSharedGameId' => $laosChampasakObj->id,
                'code' => 'three_top'
            ])->innerJoinWith('playType')->one();
            $twoUnderLaosChampasakAnswer = ThaiSharedAnswerGame::find()->select(['number', 'title'])->where([
                'thaiSharedGameId' => $laosChampasakObj->id,
                'code' => 'two_under'
            ])->innerJoinWith('playType')->one();
            $laosChampasak = [
                'date' => isset($laosChampasakObj->endDate) ? date('d-M-Y', strtotime($laosChampasakObj->endDate)) : '',
                'result' => $laosChampasakObj->result,
                'three_top' => [
                    'number' => isset($threeTopLaosChampasakAnswer) ? $threeTopLaosChampasakAnswer->number : 'xxx',
                    'title' => '3 ตัวบน',
                ],
                'two_under' =>  [
                    'number' => isset($twoUnderLaosChampasakAnswer) ? $twoUnderLaosChampasakAnswer->number : 'xxx',
                    'title' => '2 ตัวล่าง',
                ],
            ];
        } else {
            $laosChampasak = [
                'date' => date('d-M-Y', strtotime($date)),
            ];
        }
        $lotteryReserveObj = ThaiSharedGame::find()->where([
            'gameId' => Constants::LOTTERYRESERVEGAME,
            'typeSharedGameId' => 2,
            'disabled' => 1,
            'title' => 'หวยลาวทดแทน'
        ]);
        if (\Yii::$app->request->get('date')) {
            $lotteryReserveObj->andWhere(['=', 'DATE(endDate)', $date]);
        }
        $lotteryReserveObj = $lotteryReserveObj->orderBy('endDate DESC')->limit(1)->one();

        if ($lotteryReserveObj) {
            $threeTopLotteryReserveAnswer = ThaiSharedAnswerGame::find()->select('number')->where([
                'thaiSharedGameId' => $lotteryReserveObj->id,
                'code' => 'three_top'
            ])->innerJoinWith('playType')->one();
            $twoUnderLotteryReserveAnswer = ThaiSharedAnswerGame::find()->select(['number', 'title'])->where([
                'thaiSharedGameId' => $lotteryReserveObj->id,
                'code' => 'two_under'
            ])->innerJoinWith('playType')->one();
            $lotteryReserve = [
                'date' => isset($lotteryReserveObj->endDate) ? date('d-M-Y', strtotime($lotteryReserveObj->endDate)) : '',
                'result' => $lotteryReserveObj->result,
                'three_top' => [
                    'number' => isset($threeTopLotteryReserveAnswer) ? $threeTopLotteryReserveAnswer->number : 'xxx',
                    'title' => '3 ตัวบน',
                ],
                'two_under' =>  [
                    'number' => isset($twoUnderLotteryReserveAnswer) ? $twoUnderLotteryReserveAnswer->number : 'xxx',
                    'title' => '2 ตัวล่าง',
                ],
            ];
        } else {
            $lotteryReserve = [
                'date' => date('d-M-Y', strtotime($date)),
            ];
        }
        return $this->render('report', [
            'foreignLotterys' => $foreignLotterys,
            'thaiLotterys' => $thaiLotterys,
            'now' => $now,
            'date' => $date,
            'gameLotterys' => $gameLotterys,
            'yeekees' => $yeekees,
            'yeekeeLastActive' => $yeekeeLastActive,
            'bacc' => $bacc,
            'gsb' => $gsb,
            'laosChampasak' => $laosChampasak,
            'lotteryReserve' => $lotteryReserve,
        ]);
    }
}
