<?php

namespace frontend\controllers\user;

use common\libs\Constants;
use common\models\LotteryShowNumberResultWin;
use common\models\News;
use common\models\Running;
use common\models\Session;
use common\models\ThaiSharedAnswerGame;
use common\models\ThaiSharedGame;
use common\models\YeekeeSearch;
use dektrium\user\controllers\SecurityController as BaseSecurityController;
use yii\filters\AccessControl;
use dektrium\user\filters\AccessRule;
use dektrium\user\models\LoginForm;
use common\models\UserAccessSearch;
use yii\helpers\ArrayHelper;

class SecurityController extends BaseSecurityController
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => AccessRule::className(),
                ],
                'rules' => [
                    [
                        'actions' => ['login', 'login2'],
                        'allow' => true,
                        'roles' => ['?', '@', 'admin'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@', 'admin'],
                    ],
                ],
            ],
        ];
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            $this->goHome();
        }

        /** @var LoginForm $model */
        $model = \Yii::createObject(LoginForm::className());
        $event = $this->getFormEvent($model);

        $this->performAjaxValidation($model);

        $this->trigger(self::EVENT_BEFORE_LOGIN, $event);
        if ($model->load(\Yii::$app->getRequest()->post()) && $model->login()) {
            Session::deleteAll('user_id = :user_id', [':user_id' => \Yii::$app->user->id]);
            if (\Yii::$app->user->identity->user_status == \common\libs\Constants::user_status_active) {
                $modelUserAccess = new UserAccessSearch();
                $modelUserAccess->save_access();

                $this->trigger(self::EVENT_AFTER_LOGIN, $event);
                return $this->redirect(['/site/home']);
            } else {
                return $this->redirect(['/user/logout']);
            }
        }
        $model->login = '';
        $model->password = '';
        $news = News::find()->where(['title' => 'ประกาศ'])->one();
        $thaiLotterys = [];
        $date = date('Y-m-d');
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
        $thaiLotteryObjs = ThaiSharedGame::find()->where([
            'gameId' => [Constants::THAISHARED, Constants::VIETNAMVIP, Constants::VIETNAM4D_GAME],
            'typeSharedGameId' => 1,
            'disabled' => 1
        ])->andWhere('DATE(startDate) <= CURDATE()')->andWhere('DATE(endDate) >= CURDATE()')->all();
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
        $gsbObj = ThaiSharedGame::find()->where([
            'gameId' => Constants::GSB_THAISHARD_GAME,
            'typeSharedGameId' => 1,
            'disabled' => 1,
            'title' => 'หวยออมสิน'
        ])->orderBy('endDate DESC')->limit(1)->one();
        if ($gsbObj) {
            $threeTopGsbAnswer = ThaiSharedAnswerGame::find()->select('number')->where([
                'thaiSharedGameId' => $gsbObj->id,
                'code' => 'three_top'
            ])->innerJoinWith('playType')->one();
            $twoUnderGsbAnswer = ThaiSharedAnswerGame::find()->select(['number', 'title'])->where([
                'thaiSharedGameId' => $gsbObj->id,
                'code' => 'two_under'
            ])->innerJoinWith('playType')->one();
        }
        $gsb = [
            'date' => isset($gsbObj->endDate) ? date('d-M-Y', strtotime($gsbObj->endDate)) : '',
            'result' => isset($gsbObj->result) ? $gsbObj->result : 'xxxxxx',
            'three_top' => [
                'number' => isset($threeTopGsbAnswer) ? $threeTopGsbAnswer->number : 'xxx',
                'title' => '3 ตัวบน',
            ],
            'two_under' =>  [
                'number' => isset($twoUnderGsbAnswer) ? $twoUnderGsbAnswer->number : 'xxx',
                'title' => '2 ตัวล่าง',
            ],
        ];
        $baccObj = ThaiSharedGame::find()->where([
            'gameId' => Constants::BACC_THAISHARD_GAME,
            'typeSharedGameId' => 1,
            'disabled' => 1,
            'title' => 'หวย ธกส'
        ])->orderBy('endDate DESC')->limit(1)->one();
        if ($baccObj) {
            $threeTopBaccAnswer = ThaiSharedAnswerGame::find()->select('number')->where([
                'thaiSharedGameId' => $baccObj->id,
                'code' => 'three_top'
            ])->innerJoinWith('playType')->one();
            $twoUnderBaccAnswer = ThaiSharedAnswerGame::find()->select(['number', 'title'])->where([
                'thaiSharedGameId' => $baccObj->id,
                'code' => 'two_under'
            ])->innerJoinWith('playType')->one();
        }
        $bacc = [
            'date' => isset($baccObj->endDate) ? date('d-M-Y', strtotime($baccObj->endDate)) : '',
            'result' => isset($baccObj->result) ? $baccObj->result : 'xxxxx',
            'three_top' => [
                'number' => isset($threeTopBaccAnswer) ? $threeTopBaccAnswer->number : 'xxx',
                'title' => '3 ตัวบน',
            ],
            'two_under' =>  [
                'number' => isset($twoUnderBaccAnswer) ? $twoUnderBaccAnswer->number : 'xxx',
                'title' => '2 ตัวล่าง',
            ],
        ];
        $loasChampasakObj = ThaiSharedGame::find()->where([
            'gameId' => Constants::LAOS_CHAMPASAK_LOTTERY_GAME,
            'typeSharedGameId' => 2,
            'disabled' => 1,
            'title' => 'หวยลาว จำปาสัก'
        ])->orderBy('endDate DESC')->limit(1)->one();
        if ($loasChampasakObj) {
            $threeTopLoasChampasakAnswer = ThaiSharedAnswerGame::find()->select('number')->where([
                'thaiSharedGameId' => $loasChampasakObj->id,
                'code' => 'three_top'
            ])->innerJoinWith('playType')->one();
            $twoUnderLoasChampasakAnswer = ThaiSharedAnswerGame::find()->select(['number', 'title'])->where([
                'thaiSharedGameId' => $loasChampasakObj->id,
                'code' => 'two_under'
            ])->innerJoinWith('playType')->one();
        }
        $loasChampasak = [
            'date' => isset($loasChampasakObj->endDate) ? date('d-M-Y', strtotime($loasChampasakObj->endDate)) : '',
            'result' => isset($loasChampasakObj->result) ? $loasChampasakObj->result : 'xxxxxx',
            'three_top' => [
                'number' => isset($threeTopLoasChampasakAnswer) ? $threeTopLoasChampasakAnswer->number : 'xxx',
                'title' => '3 ตัวบน',
            ],
            'two_under' =>  [
                'number' => isset($twoUnderLoasChampasakAnswer) ? $twoUnderLoasChampasakAnswer->number : 'xxx',
                'title' => '2 ตัวล่าง',
            ],
        ];
        $lotteryReserveObj = ThaiSharedGame::find()->where([
            'gameId' => Constants::LOTTERYRESERVEGAME,
            'typeSharedGameId' => 2,
            'disabled' => 1,
            'title' => 'หวยลาวทดแทน'
        ])->orderBy('endDate DESC')->limit(1)->one();
        if ($lotteryReserveObj) {
            $threeTopLotteryReserveAnswer = ThaiSharedAnswerGame::find()->select('number')->where([
                'thaiSharedGameId' => $lotteryReserveObj->id,
                'code' => 'three_top'
            ])->innerJoinWith('playType')->one();
            $twoUnderLotteryReserveAnswer = ThaiSharedAnswerGame::find()->select(['number', 'title'])->where([
                'thaiSharedGameId' => $lotteryReserveObj->id,
                'code' => 'two_under'
            ])->innerJoinWith('playType')->one();
        }
        $lotteryReserve = [
            'date' => isset($lotteryReserveObj->endDate) ? date('d-M-Y', strtotime($lotteryReserveObj->endDate)) : '',
            'result' => isset($lotteryReserveObj->result) ? $lotteryReserveObj->result : 'xxxxxx',
            'three_top' => [
                'number' => isset($threeTopLotteryReserveAnswer) ? $threeTopLotteryReserveAnswer->number : 'xxx',
                'title' => '3 ตัวบน',
            ],
            'two_under' =>  [
                'number' => isset($twoUnderLotteryReserveAnswer) ? $twoUnderLotteryReserveAnswer->number : 'xxx',
                'title' => '2 ตัวล่าง',
            ],
        ];
        $foreignObjs = ThaiSharedGame::find()->where([
            'gameId' => [Constants::THAISHARED, Constants::VIETNAMVIP, Constants::VIETNAM4D_GAME],
            'typeSharedGameId' => 2,
            'disabled' => 1,
        ])->andWhere( 'DATE(startDate) <= CURDATE()')->andWhere('DATE(endDate) >= CURDATE()')->andWhere([
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
        ])->orderBy('endDate DESC')->limit(1)->one();
        if ($lotteryGameThaiShared) {
            $threeFrontAnswer = ThaiSharedAnswerGame::find()->select('number')->where([
                'thaiSharedGameId' => $lotteryGameThaiShared->id,
                'code' => 'three_top2'
            ])->innerJoinWith('playType')->all();
            $threeFront = implode(',', ArrayHelper::getColumn($threeFrontAnswer, 'number'));
            $threeBackAnswer = ThaiSharedAnswerGame::find()->select('number')->where([
                'thaiSharedGameId' => $lotteryGameThaiShared->id,
                'code' => 'three_und2'
            ])->innerJoinWith('playType')->all();
            $threeBack = implode(',', ArrayHelper::getColumn($threeBackAnswer, 'number'));
            $twoUnderAnswer = ThaiSharedAnswerGame::find()->select('number')->where([
                'thaiSharedGameId' => $lotteryGameThaiShared->id,
                'code' => 'two_under'
            ])->innerJoinWith('playType')->one();
            $lotteryShowNumberResultWin = LotteryShowNumberResultWin::find()->where(['thaiSharedGameId' => $lotteryGameThaiShared->id])->one();
        }
        $gameLotterys = [
            'title' => 'หวยรัฐบาลไทย',
            'date' => isset($lotteryGameThaiShared->endDate) ? date('d-M-Y', strtotime($lotteryGameThaiShared->endDate)) : date('d-M-Y'),
            'firstResult' => isset($lotteryShowNumberResultWin) ? $lotteryShowNumberResultWin->number : 'xxxxxx',
            'three_front' => [
                'title' => '3ตัวหน้า',
                'number' => !empty($threeFront) ? $threeFront : 'xxx,xxx',
            ],
            'three_back' => [
                'title' => '3ตัวล่าง',
                'number' => !empty($threeBack) ? $threeBack : 'xxx,xxx',
            ],
            'two_under' => [
                'title' => '2ตัวล่าง',
                'number' => isset($twoUnderAnswer) ? $twoUnderAnswer->number : 'xx',
            ],
        ];
        $now = date('d-M-Y');
        return $this->render('@frontend/views/login/index', [
            'model' => $model,
            'module' => $this->module,
            'news' => $news,
            'foreignLotterys' => $foreignLotterys,
            'thaiLotterys' => $thaiLotterys,
            'now' => $now,
            'gameLotterys' => $gameLotterys,
            'yeekees' => $yeekees,
            'yeekeeLastActive' => $yeekeeLastActive,
            'gsb' => $gsb,
            'bacc' => $bacc,
            'loasChampasak' => $loasChampasak,
            'lotteryReserve' => $lotteryReserve,
        ]);
    }
}