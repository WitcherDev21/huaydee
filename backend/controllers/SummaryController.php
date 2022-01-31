<?php

namespace backend\controllers;

use common\models\BlackRed;
use common\models\BlackredChit;
use common\models\ConfigGenerateGame;
use common\models\CreditTransection;
use common\models\PlayType;
use common\models\ThaiSharedGame;
use common\models\ThaiSharedGameChit;
use common\models\ThaiSharedGameChitDetail;
use common\models\ThaiSharedAnswerGame;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use common\libs\Constants;
use common\models\Bank;
use common\models\YeekeeSearch;
use common\models\AuthRoles;

/**
 * Site controller
 */
class SummaryController extends Controller
{
    public $defaultAction = 'daily';

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function behaviors()
    {
        $identity = \Yii::$app->user->getIdentity();
        if (empty($identity)) {
            $this->layout = false;
            $this->redirect(Yii::$app->urlManager->createUrl(['user/login']));
        } else {
            $modelAuthRoles = new AuthRoles();
            $arrRoles = $modelAuthRoles->_getRoles($identity->auth_roles_id);
            if (!in_array('summary', $arrRoles)) {
                $this->layout = false;
                $this->redirect(Yii::$app->urlManager->createUrl(['user/logout']));
            }
        }
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
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
     * @return string
     */

    public function actionDaily()
    {
        $param = Yii::$app->request->post();
        $searchModel = new YeekeeSearch();
        $date = '';
        $round = '';
        if (!empty($param)) {
            $date = $param['YeekeeSearch']['date_at_search'];
            $searchModel->load($param);
            $round = (!empty($param['YeekeeSearch']['round']) ? ' AND round="' . $param['YeekeeSearch']['round'] . '"' : '');
            $tab = $param['tab'];
        } else {
            $date = date('Y-m-d');
        }
        $totalAmountYeekee = 0;
        $totalWinCreditYeekee = 0;
        $totalAmountBlackRed = 0;
        $totalWinCreditBlackRed = 0;
        $totalAmountThaiShared = 0;
        $totalAmountLottery = 0;
        $totalDiscountLottery = 0;
        $totalWinCreditDiscountLottery = 0;
        $totalWinCreditThaiShared = 0;
        $totalWinCreditLottery = 0;
        $totalAmountAllLotteryLaoSet = 0;
        $totalWinCreditAllLotteryLaoSet = 0;
        $dataYeekee = Yii::$app->db->createCommand
        ("
            SELECT * FROM yeekee WHERE date_at='" . $date . "' $round ORDER BY round ASC
        ")->queryAll();

        $data = array();
        if (!empty($dataYeekee)) {
            foreach ($dataYeekee as $val) {
                $dataYeekeeChit = Yii::$app->db->createCommand
                ("
                    SELECT yd.play_type_code, SUM(yd.amount) AS amount, SUM(yd.win_credit) AS win_credit, yc.status FROM yeekee_chit yc
                    INNER JOIN yeekee_chit_detail yd ON yd.yeekee_chit_id=yc.id
                    WHERE yc.yeekee_id='" . $val['id'] . "' and yc.status != 5 GROUP BY yd.play_type_code
                ")->queryAll();

                $type = array();
                if (!empty($dataYeekeeChit)) {
                    foreach ($dataYeekeeChit as $item) {
                        $totalAmountYeekee += $item['amount'];
                        $totalWinCreditYeekee += $item['win_credit'];
                        $type[$item['play_type_code']] = [
                            'amount' => $item['amount'],
                            'win_credit' => $item['win_credit']
                        ];
                    }
                }
                $data[$val['round']] = [
                    'create_at' => $val['finish_at'],
                    'three_top' => (!empty($type) && !empty($type['three_top']) ? $type['three_top'] : ''),
                    'three_tod' => (!empty($type) && !empty($type['three_tod']) ? $type['three_tod'] : ''),
                    'two_top' => (!empty($type) && !empty($type['two_top']) ? $type['two_top'] : ''),
                    'two_under' => (!empty($type) && !empty($type['two_under']) ? $type['two_under'] : ''),
                    'run_top' => (!empty($type) && !empty($type['run_top']) ? $type['run_top'] : ''),
                    'run_under' => (!empty($type) && !empty($type['run_under']) ? $type['run_under'] : ''),
                ];
            }
        }

        $configYeekee = ConfigGenerateGame::find()->where(['game_id' => 1])->one();
        $configBlackred = ConfigGenerateGame::find()->where(['game_id' => 2])->one();
        $searchModel->round = isset($param['YeekeeSearch']['round']) ? $param['YeekeeSearch']['round'] : '';
        $dataBlackreds = BlackRed::find()->where(['date_at' => $date])->orderBy('round ASC');
        if ($searchModel->round) {
            $dataBlackreds->andWhere(['round' => $param['YeekeeSearch']['round']]);
        }
        $dataBlackreds = $dataBlackreds->all();
        $blackreds = [];
        if ($dataBlackreds) {
            foreach ($dataBlackreds as $blackred) {
                $sumTotalBlack = BlackredChit::find()->where(['blackred_id' => $blackred->id, 'play_type_code' => 1])->sum('total_amount');
                $sumTotalWinBlack = BlackredChit::find()->where(['blackred_id' => $blackred->id, 'play_type_code' => 1, 'flag_result' => 1])->sum('total_amount');
                $sumTotalRed = BlackredChit::find()->where(['blackred_id' => $blackred->id, 'play_type_code' => 2])->sum('total_amount');
                $sumTotalWinRed = BlackredChit::find()->where(['blackred_id' => $blackred->id, 'play_type_code' => 2, 'flag_result' => 1])->sum('total_amount');
                $totalAmountBlackRed += $sumTotalBlack;
                $totalWinCreditBlackRed += $sumTotalWinBlack;
                $blackreds[$blackred->round] = [
                    [
                        'round' => 'ดำ',
                        'date' => $blackred->create_at,
                        'amount' => $sumTotalBlack,
                        'win_credit' => $sumTotalWinBlack
                    ],
                    [
                        'round' => 'แดง',
                        'date' => $blackred->finish_at,
                        'amount' => $sumTotalRed,
                        'win_credit' => $sumTotalWinRed
                    ]
                ];
            }
        }
        //หวยไทย-ต่างประเทศ
        $dataThaiSharedChit = Yii::$app->db->createCommand
        ("
              SELECT pl.code, SUM(yd.amount) AS amount, SUM(yd.discount) AS discount, SUM(yd.win_credit) AS win_credit, 
              yc.thaiSharedGameId, yc.status, ts.gameId, ts.title FROM " . ThaiSharedGameChit::tableName() . " yc
              INNER JOIN " . ThaiSharedGameChitDetail::tableName() . " yd ON yd.thaiSharedGameChitId=yc.id
              INNER JOIN " . PlayType::tableName() . " pl ON pl.id = yd.playTypeId
              INNER JOIN " . ThaiSharedGame::tableName() . " ts ON yc.thaiSharedGameId = ts.id
              WHERE yc.status != 5 AND ts.gameId 
              NOT IN (" . Constants::LOTTERYLAOGAME . ", " . Constants::LOTTERYLAODISCOUNTGAME . ", " . Constants::LOTTERYGAME . ", "
            . Constants::LOTTERYGAMEDISCOUNT . ",".Constants::LOTTERY_VIETNAM_SET.",".Constants::GSB_THAISHARD_GAME.","
            .Constants::BACC_THAISHARD_GAME.",".Constants::LAOS_CHAMPASAK_LOTTERY_GAME.",".Constants::VIETNAM4D_GAME.",".Constants::LOTTERYRESERVEGAME.") 
            AND ts.title != 'หวยหุ้นดาวน์โจน' AND DATE(yd.createdAt) = '" . $date . "'  GROUP BY yc.thaiSharedGameId, yd.playTypeId
        ")->queryAll();
        $dataThaiSharedGames = array();
        $dataShared = array();
        foreach ($dataThaiSharedChit as $dataThaiShared) {
            if ($dataThaiShared['discount']) {
                $totalAmountThaiShared += $dataThaiShared['discount'];
            } else {
                $totalAmountThaiShared += $dataThaiShared['amount'];
            }
            $totalWinCreditThaiShared += $dataThaiShared['win_credit'];
            $dataThaiSharedGames[$dataThaiShared['title']][$dataThaiShared['code']] = [
                'amount' => $dataThaiShared['amount'],
                'discount' => $dataThaiShared['discount'],
                'win_credit' => $dataThaiShared['win_credit']
            ];
        }
        foreach ($dataThaiSharedGames as $titile => $dataThaiSharedGame) {
            $dataShared[$titile] = [
                'create_at' => $date,
                'three_top' => (!empty($dataThaiSharedGame) && !empty($dataThaiSharedGame['three_top']) ? $dataThaiSharedGame['three_top'] : ''),
                'three_tod' => (!empty($dataThaiSharedGame) && !empty($dataThaiSharedGame['three_tod']) ? $dataThaiSharedGame['three_tod'] : ''),
                'two_top' => (!empty($dataThaiSharedGame) && !empty($dataThaiSharedGame['two_top']) ? $dataThaiSharedGame['two_top'] : ''),
                'two_under' => (!empty($dataThaiSharedGame) && !empty($dataThaiSharedGame['two_under']) ? $dataThaiSharedGame['two_under'] : ''),
                'run_top' => (!empty($dataThaiSharedGame) && !empty($dataThaiSharedGame['run_top']) ? $dataThaiSharedGame['run_top'] : ''),
                'run_under' => (!empty($dataThaiSharedGame) && !empty($dataThaiSharedGame['run_under']) ? $dataThaiSharedGame['run_under'] : ''),
            ];
        }
        //หวยรัฐบาล
        $dataLotterys = array();
        $dataThaiSharedChit = Yii::$app->db->createCommand
        ("
              SELECT pl.code, SUM(yd.amount) AS amount, SUM(yd.discount) AS discount, SUM(yd.win_credit) AS win_credit, 
              yc.thaiSharedGameId, yc.status, ts.gameId, ts.title FROM " . ThaiSharedGameChit::tableName() . " yc
              INNER JOIN " . ThaiSharedGameChitDetail::tableName() . " yd ON yd.thaiSharedGameChitId=yc.id
              INNER JOIN " . PlayType::tableName() . " pl ON pl.id = yd.playTypeId
              INNER JOIN " . ThaiSharedGame::tableName() . " ts ON yc.thaiSharedGameId = ts.id
              WHERE DATE(yd.createdAt) = '" . $date . "' and yc.status != 5 
              AND gameId  IN (" . Constants::LOTTERYGAME . ", " . Constants::LOTTERYGAMEDISCOUNT . ") 
              GROUP BY yc.thaiSharedGameId, yd.playTypeId
        ")->queryAll();
        $dataLotteryGames = array();
        if (!empty($dataThaiSharedChit)) {
            foreach ($dataThaiSharedChit as $dataThaiShared) {
                if ($dataThaiShared['gameId'] == Constants::LOTTERYGAME) {
                    $totalAmountLottery += $dataThaiShared['amount'];
                    $totalWinCreditLottery += $dataThaiShared['win_credit'];
                } else if ($dataThaiShared['gameId'] == Constants::LOTTERYGAMEDISCOUNT) {
                    $totalAmountLottery += $dataThaiShared['discount'];
                    $totalWinCreditDiscountLottery += $dataThaiShared['win_credit'];
                }
                $dataLotteryGames[$dataThaiShared['title']][$dataThaiShared['code']] = [
                    'amount' => $dataThaiShared['amount'],
                    'discount' => $dataThaiShared['discount'],
                    'win_credit' => $dataThaiShared['win_credit']
                ];
            }
        }
        foreach ($dataLotteryGames as $title => $dataLotteryGame) {
            $dataLotterys[$title] = [
                'create_at' => $date,
                'three_top' => (!empty($dataLotteryGame) && !empty($dataLotteryGame['three_top']) ? $dataLotteryGame['three_top'] : ''),
                'three_tod' => (!empty($dataLotteryGame) && !empty($dataLotteryGame['three_tod']) ? $dataLotteryGame['three_tod'] : ''),
                'two_top' => (!empty($dataLotteryGame) && !empty($dataLotteryGame['two_top']) ? $dataLotteryGame['two_top'] : ''),
                'two_under' => (!empty($dataLotteryGame) && !empty($dataLotteryGame['two_under']) ? $dataLotteryGame['two_under'] : ''),
                'run_top' => (!empty($dataLotteryGame) && !empty($dataLotteryGame['run_top']) ? $dataLotteryGame['run_top'] : ''),
                'run_under' => (!empty($dataLotteryGame) && !empty($dataLotteryGame['run_under']) ? $dataLotteryGame['run_under'] : ''),
                'three_top2' => (!empty($dataLotteryGame) && !empty($dataLotteryGame['three_top2']) ? $dataLotteryGame['three_top2'] : ''),
                'three_und2' => (!empty($dataLotteryGame) && !empty($dataLotteryGamepe['three_und2']) ? $dataLotteryGame['three_und2'] : ''),
            ];
        }
        //หวยออมสิน
        $dataGsbThaiSharedChit = Yii::$app->db->createCommand
        ("
              SELECT pl.code, SUM(yd.amount) AS amount, SUM(yd.discount) AS discount, SUM(yd.win_credit) AS win_credit, 
              yc.thaiSharedGameId, yc.status, ts.gameId, ts.title FROM " . ThaiSharedGameChit::tableName() . " yc
              INNER JOIN " . ThaiSharedGameChitDetail::tableName() . " yd ON yd.thaiSharedGameChitId=yc.id
              INNER JOIN " . PlayType::tableName() . " pl ON pl.id = yd.playTypeId
              INNER JOIN " . ThaiSharedGame::tableName() . " ts ON yc.thaiSharedGameId = ts.id
              WHERE yc.status != 5 AND ts.gameId = " . Constants::GSB_THAISHARD_GAME . " AND DATE(yd.createdAt) = '" . $date . "' GROUP BY yc.thaiSharedGameId, yd.playTypeId
        ")->queryAll();
        $dataGsbThaiSharedGames = array();
        $dataGsbShared = array();
        foreach ($dataGsbThaiSharedChit as $dataGsb) {
            if ($dataGsb['discount']) {
                $totalAmountThaiShared += $dataGsb['discount'];
            } else {
                $totalAmountThaiShared += $dataGsb['amount'];
            }
            $totalWinCreditThaiShared += $dataGsb['win_credit'];
            $dataGsbThaiSharedGames[$dataGsb['title']][$dataGsb['code']] = [
                'amount' => $dataGsb['amount'],
                'discount' => $dataGsb['discount'],
                'win_credit' => $dataGsb['win_credit']
            ];
        }
        foreach ($dataGsbThaiSharedGames as $titile => $dataGsbThaiSharedGame) {
            $dataGsbShared[$titile] = [
                'create_at' => $date,
                'three_top' => (!empty($dataGsbThaiSharedGame) && !empty($dataGsbThaiSharedGame['three_top']) ? $dataGsbThaiSharedGame['three_top'] : ''),
                'three_tod' => (!empty($dataGsbThaiSharedGame) && !empty($dataGsbThaiSharedGame['three_tod']) ? $dataGsbThaiSharedGame['three_tod'] : ''),
                'two_top' => (!empty($dataGsbThaiSharedGame) && !empty($dataGsbThaiSharedGame['two_top']) ? $dataGsbThaiSharedGame['two_top'] : ''),
                'two_under' => (!empty($dataGsbThaiSharedGame) && !empty($dataGsbThaiSharedGame['two_under']) ? $dataGsbThaiSharedGame['two_under'] : ''),
                'run_top' => (!empty($dataGsbThaiSharedGame) && !empty($dataGsbThaiSharedGame['run_top']) ? $dataGsbThaiSharedGame['run_top'] : ''),
                'run_under' => (!empty($dataGsbThaiSharedGame) && !empty($dataGsbThaiSharedGame['run_under']) ? $dataGsbThaiSharedGame['run_under'] : ''),
            ];
        }

        //หวยธกส
        $dataBaccThaiSharedChit = Yii::$app->db->createCommand
        ("
              SELECT pl.code, SUM(yd.amount) AS amount, SUM(yd.discount) AS discount, SUM(yd.win_credit) AS win_credit, 
              yc.thaiSharedGameId, yc.status, ts.gameId, ts.title FROM " . ThaiSharedGameChit::tableName() . " yc
              INNER JOIN " . ThaiSharedGameChitDetail::tableName() . " yd ON yd.thaiSharedGameChitId=yc.id
              INNER JOIN " . PlayType::tableName() . " pl ON pl.id = yd.playTypeId
              INNER JOIN " . ThaiSharedGame::tableName() . " ts ON yc.thaiSharedGameId = ts.id
              WHERE yc.status != 5 AND ts.gameId = " . Constants::BACC_THAISHARD_GAME . " AND DATE(yd.createdAt) = '" . $date . "' GROUP BY yc.thaiSharedGameId, yd.playTypeId
        ")->queryAll();
        $dataBaccThaiSharedGames = array();
        $dataBaccShared = array();
        foreach ($dataBaccThaiSharedChit as $dataBacc) {
            if ($dataBacc['discount']) {
                $totalAmountThaiShared += $dataBacc['discount'];
            } else {
                $totalAmountThaiShared += $dataBacc['amount'];
            }
            $totalWinCreditThaiShared += $dataBacc['win_credit'];
            $dataBaccThaiSharedGames[$dataBacc['title']][$dataBacc['code']] = [
                'amount' => $dataBacc['amount'],
                'discount' => $dataBacc['discount'],
                'win_credit' => $dataBacc['win_credit']
            ];
        }
        foreach ($dataBaccThaiSharedGames as $titile => $dataBaccThaiSharedGame) {
            $dataBaccShared[$titile] = [
                'create_at' => $date,
                'three_top' => (!empty($dataBaccThaiSharedGame) && !empty($dataBaccThaiSharedGame['three_top']) ? $dataBaccThaiSharedGame['three_top'] : ''),
                'three_tod' => (!empty($dataBaccThaiSharedGame) && !empty($dataBaccThaiSharedGame['three_tod']) ? $dataBaccThaiSharedGame['three_tod'] : ''),
                'two_top' => (!empty($dataBaccThaiSharedGame) && !empty($dataBaccThaiSharedGame['two_top']) ? $dataBaccThaiSharedGame['two_top'] : ''),
                'two_under' => (!empty($dataBaccThaiSharedGame) && !empty($dataBaccThaiSharedGame['two_under']) ? $dataBaccThaiSharedGame['two_under'] : ''),
                'run_top' => (!empty($dataBaccThaiSharedGame) && !empty($dataBaccThaiSharedGame['run_top']) ? $dataBaccThaiSharedGame['run_top'] : ''),
                'run_under' => (!empty($dataBaccThaiSharedGame) && !empty($dataBaccThaiSharedGame['run_under']) ? $dataBaccThaiSharedGame['run_under'] : ''),
            ];
        }

        //หวยลาว จำปาสัก
        $dataLaosChampasakThaiSharedChit = Yii::$app->db->createCommand
        ("
              SELECT pl.code, SUM(yd.amount) AS amount, SUM(yd.discount) AS discount, SUM(yd.win_credit) AS win_credit, 
              yc.thaiSharedGameId, yc.status, ts.gameId, ts.title FROM " . ThaiSharedGameChit::tableName() . " yc
              INNER JOIN " . ThaiSharedGameChitDetail::tableName() . " yd ON yd.thaiSharedGameChitId=yc.id
              INNER JOIN " . PlayType::tableName() . " pl ON pl.id = yd.playTypeId
              INNER JOIN " . ThaiSharedGame::tableName() . " ts ON yc.thaiSharedGameId = ts.id
              WHERE yc.status != 5 AND ts.gameId = " . Constants::LAOS_CHAMPASAK_LOTTERY_GAME . " AND DATE(yd.createdAt) = '" . $date . "' GROUP BY yc.thaiSharedGameId, yd.playTypeId
        ")->queryAll();
        $dataLaosChampasakThaiSharedGames = array();
        $dataLaosChampasakShared = array();
        foreach ($dataLaosChampasakThaiSharedChit as $dataLaosChampasak) {
            if ($dataLaosChampasak['discount']) {
                $totalAmountThaiShared += $dataLaosChampasak['discount'];
            } else {
                $totalAmountThaiShared += $dataLaosChampasak['amount'];
            }
            $totalWinCreditThaiShared += $dataLaosChampasak['win_credit'];
            $dataLaosChampasakThaiSharedGames[$dataLaosChampasak['title']][$dataLaosChampasak['code']] = [
                'amount' => $dataLaosChampasak['amount'],
                'discount' => $dataLaosChampasak['discount'],
                'win_credit' => $dataLaosChampasak['win_credit']
            ];
        }
        foreach ($dataLaosChampasakThaiSharedGames as $titile => $dataLaosChampasakThaiSharedGame) {
            $dataLaosChampasakShared[$titile] = [
                'create_at' => $date,
                'three_top' => (!empty($dataLaosChampasakThaiSharedGame) && !empty($dataLaosChampasakThaiSharedGame['three_top']) ? $dataLaosChampasakThaiSharedGame['three_top'] : ''),
                'three_tod' => (!empty($dataLaosChampasakThaiSharedGame) && !empty($dataLaosChampasakThaiSharedGame['three_tod']) ? $dataLaosChampasakThaiSharedGame['three_tod'] : ''),
                'two_top' => (!empty($dataLaosChampasakThaiSharedGame) && !empty($dataLaosChampasakThaiSharedGame['two_top']) ? $dataLaosChampasakThaiSharedGame['two_top'] : ''),
                'two_under' => (!empty($dataLaosChampasakThaiSharedGame) && !empty($dataLaosChampasakThaiSharedGame['two_under']) ? $dataLaosChampasakThaiSharedGame['two_under'] : ''),
                'run_top' => (!empty($dataLaosChampasakThaiSharedGame) && !empty($dataLaosChampasakThaiSharedGame['run_top']) ? $dataLaosChampasakThaiSharedGame['run_top'] : ''),
                'run_under' => (!empty($dataLaosChampasakThaiSharedGame) && !empty($dataLaosChampasakThaiSharedGame['run_under']) ? $dataLaosChampasakThaiSharedGame['run_under'] : ''),
            ];
        }

        //หวยดาวน์โจน
        $dataDownJoans = array();
        $dataThaiSharedChit = Yii::$app->db->createCommand
        ("
              SELECT pl.code, SUM(yd.amount) AS amount, SUM(yd.discount) AS discount, SUM(yd.win_credit) AS win_credit, 
              yc.thaiSharedGameId, yc.status, ts.title, ts.gameId FROM " . ThaiSharedGameChit::tableName() . " yc
              INNER JOIN " . ThaiSharedGameChitDetail::tableName() . " yd ON yd.thaiSharedGameChitId=yc.id
              INNER JOIN " . PlayType::tableName() . " pl ON pl.id = yd.playTypeId
              INNER JOIN " . ThaiSharedGame::tableName() . " ts ON yc.thaiSharedGameId = ts.id
              WHERE DATE(yd.createdAt) = '" . $date . "' and yc.status != 5 and gameId NOT IN 
              (" . Constants::LOTTERYLAOGAME . ", " . Constants::LOTTERYLAODISCOUNTGAME . ", " . Constants::LOTTERYGAME . ", "
            . Constants::LOTTERYGAMEDISCOUNT . ") AND ts.title = 'หวยหุ้นดาวน์โจน' GROUP BY yd.playTypeId
                ")->queryAll();
        $downJhonesThaiShareds = array();
        if (!empty($dataThaiSharedChit)) {
            foreach ($dataThaiSharedChit as $dataThaiShared) {
                if ($dataThaiShared['discount']) {
                    $totalAmountThaiShared += $dataThaiShared['discount'];
                } else {
                    $totalAmountThaiShared += $dataThaiShared['amount'];
                }
                $totalWinCreditThaiShared += $dataThaiShared['win_credit'];
                $downJhonesThaiShareds[$dataThaiShared['title']][$dataThaiShared['code']] = [
                    'amount' => $dataThaiShared['amount'],
                    'discount' => $dataThaiShared['discount'],
                    'win_credit' => $dataThaiShared['win_credit']
                ];
            }
        }
        foreach ($downJhonesThaiShareds as $title => $downJhonesThaiShared) {
            $dataDownJoans[$title] = [
                'create_at' => $date,
                'three_top' => (!empty($downJhonesThaiShared) && !empty($downJhonesThaiShared['three_top']) ? $downJhonesThaiShared['three_top'] : ''),
                'three_tod' => (!empty($downJhonesThaiShared) && !empty($downJhonesThaiShared['three_tod']) ? $downJhonesThaiShared['three_tod'] : ''),
                'two_top' => (!empty($downJhonesThaiShared) && !empty($downJhonesThaiShared['two_top']) ? $downJhonesThaiShared['two_top'] : ''),
                'two_under' => (!empty($downJhonesThaiShared) && !empty($downJhonesThaiShared['two_under']) ? $downJhonesThaiShared['two_under'] : ''),
                'run_top' => (!empty($downJhonesThaiShared) && !empty($downJhonesThaiShared['run_top']) ? $downJhonesThaiShared['run_top'] : ''),
                'run_under' => (!empty($downJhonesThaiShared) && !empty($downJhonesThaiShared['run_under']) ? $downJhonesThaiShared['run_under'] : ''),
            ];
        }
        //หวยลาวชุด
        $dataLotteryLaoSetGame = Yii::$app->db->createCommand
        ("
            SELECT * FROM " . ThaiSharedGame::tableName() . " WHERE (DATE(startDate) BETWEEN '" . $date . "' AND '" . $date . "' 
            AND gameId IN (" . Constants::LOTTERYLAOGAME . "," . Constants::LOTTERYLAODISCOUNTGAME . ")) OR 
            (DATE(endDate) BETWEEN '" . $date . "' AND '" . $date . "' AND gameId IN 
            (" . Constants::LOTTERYLAOGAME . "," . Constants::LOTTERYLAODISCOUNTGAME . ")) AND status IN (1, 9)
            ORDER BY endDate ASC
        ")->queryAll();
        $dataLotteryLaoSets = array();
        if (!empty($dataLotteryLaoSetGame)) {
            foreach ($dataLotteryLaoSetGame as $val) {
                $totalSetNumberLotteryLaoSet = 0;
                $totalAmountLotteryLaoSet = 0;
                $dataLotteryLaoSetChits = Yii::$app->db->createCommand
                ("
                    SELECT pl.code, amount, SUM(yd.win_credit) AS win_credit, yc.thaiSharedGameId, yd.flag_result, setNumber, yc.id, yc.status, yd.createdAt FROM " . ThaiSharedGameChit::tableName() . " yc
                    INNER JOIN " . ThaiSharedGameChitDetail::tableName() . " yd ON yd.thaiSharedGameChitId=yc.id
                    INNER JOIN " . PlayType::tableName() . " pl ON pl.id = yd.playTypeId
                    WHERE DATE(yd.createdAt) = '" . $date . "' AND yc.thaiSharedGameId='" . $val['id'] . "' AND yd.flag_result = 1 AND yc.status != 5 GROUP BY yc.thaiSharedGameId, pl.code
                ")->queryAll();
                $type = array();
                if (!empty($dataLotteryLaoSetChits)) {
                    $totalWinCreditLotteryLaoSet = 0;
                    foreach ($dataLotteryLaoSetChits as $dataLotteryLaoSetChit) {
                        $totalWinCreditLotteryLaoSet += $dataLotteryLaoSetChit['win_credit'];
                        $type[$dataLotteryLaoSetChit['code']] = [
                            'amount' => 0,
                            'win_credit' => $dataLotteryLaoSetChit['win_credit']
                        ];
                    }
                }
                $dataLotteryLaoDiscountSetChits = Yii::$app->db->createCommand
                ("
                    SELECT yc.thaiSharedGameId,yc.totalAmount, yd.flag_result, yd.numberSetLottery, yd.amount, yd.setNumber, yc.id, yc.status, yd.win_credit, yd.createdAt FROM " . ThaiSharedGameChit::tableName() . " yc
                    INNER JOIN " . ThaiSharedGameChitDetail::tableName() . " yd ON yd.thaiSharedGameChitId=yc.id
                    WHERE DATE(yd.createdAt) = '" . $date . "' AND yc.thaiSharedGameId='" . $val['id'] . "' AND yd.flag_result = 0 AND yc.status != 5 GROUP BY yc.thaiSharedGameId, yc.id, yd.numberSetLottery
                ")->queryAll();
                if (!empty($dataLotteryLaoDiscountSetChits)) {
                    $totalWinCreditLotteryLaoSet = 0;
                    foreach ($dataLotteryLaoDiscountSetChits as $dataLotteryLaoDiscountSetChit) {
                        $totalAmountLotteryLaoSet += $dataLotteryLaoDiscountSetChit['amount'];
                        $totalSetNumberLotteryLaoSet += $dataLotteryLaoDiscountSetChit['setNumber'];
                        $totalWinCreditLotteryLaoSet += $dataLotteryLaoDiscountSetChit['win_credit'];
                        $type['amount'] = [
                            'amount' => $totalAmountLotteryLaoSet,
                            'win_credit' => $totalWinCreditLotteryLaoSet,
                            'totalSet' => $totalSetNumberLotteryLaoSet,
                        ];
                    }
                }
                $dataLotteryLaoSets[$val['title']] = [
                    'create_at' => $val['endDate'],
                    'four_dt' => (!empty($type) && !empty($type['four_dt']) ? $type['four_dt'] : ''),
                    'four_tod' => (!empty($type) && !empty($type['four_tod']) ? $type['four_tod'] : ''),
                    'three_top' => (!empty($type) && !empty($type['three_top']) ? $type['three_top'] : ''),
                    'three_tod' => (!empty($type) && !empty($type['three_tod']) ? $type['three_tod'] : ''),
                    'two_ft' => (!empty($type) && !empty($type['two_ft']) ? $type['two_ft'] : ''),
                    'two_bk' => (!empty($type) && !empty($type['two_bk']) ? $type['two_bk'] : ''),
                    'amount' => (!empty($type) && !empty($type['amount']) ? $type['amount'] : ''),
                ];
            }
        }
        //หวยเวียดนามชุด
        $dataLotteryVietnamSetGame = Yii::$app->db->createCommand
        ("
            SELECT * FROM " . ThaiSharedGame::tableName() . " WHERE (DATE(startDate) BETWEEN '" . $date . "' AND '" . $date . "' 
            AND gameId = " . Constants::LOTTERY_VIETNAM_SET . ") OR 
            (DATE(endDate) BETWEEN '" . $date . "' AND '" . $date . "' AND gameId = 
            " . Constants::LOTTERY_VIETNAM_SET . ") AND status IN (1, 9)
            ORDER BY endDate ASC
        ")->queryAll();
        $dataLotteryVietnamSets = array();
        if (!empty($dataLotteryVietnamSetGame)) {
            foreach ($dataLotteryVietnamSetGame as $val) {
                $totalSetNumberLotteryVietnamSet = 0;
                $totalAmountLotteryVietnamSet = 0;
                $dataLotteryVietnamSetChits = Yii::$app->db->createCommand
                ("
                    SELECT pl.code, amount, SUM(yd.win_credit) AS win_credit, yc.thaiSharedGameId, yd.flag_result, setNumber, yc.id, yc.status, yd.createdAt FROM " . ThaiSharedGameChit::tableName() . " yc
                    INNER JOIN " . ThaiSharedGameChitDetail::tableName() . " yd ON yd.thaiSharedGameChitId=yc.id
                    INNER JOIN " . PlayType::tableName() . " pl ON pl.id = yd.playTypeId
                    WHERE DATE(yd.createdAt) = '" . $date . "' AND yc.thaiSharedGameId='" . $val['id'] . "' AND yd.flag_result = 1 AND yc.status != 5 GROUP BY yc.thaiSharedGameId, pl.code
                ")->queryAll();
                $type = array();
                if (!empty($dataLotteryVietnamSetChits)) {
                    $totalWinCreditLotteryVietnamSet = 0;
                    foreach ($dataLotteryVietnamSetChits as $dataLotteryVietnamSetChit) {
                        $totalWinCreditLotteryVietnamSet += $dataLotteryVietnamSetChit['win_credit'];
                        $type[$dataLotteryVietnamSetChit['code']] = [
                            'amount' => 0,
                            'win_credit' => $dataLotteryVietnamSetChit['win_credit']
                        ];
                    }
                }
                $dataLotteryVietnamDiscountSetChits = Yii::$app->db->createCommand
                ("
                    SELECT yc.thaiSharedGameId,yc.totalAmount, yd.flag_result, yd.numberSetLottery, yd.amount, yd.setNumber, yc.id, yc.status, yd.win_credit, yd.createdAt FROM " . ThaiSharedGameChit::tableName() . " yc
                    INNER JOIN " . ThaiSharedGameChitDetail::tableName() . " yd ON yd.thaiSharedGameChitId=yc.id
                    WHERE DATE(yd.createdAt) = '" . $date . "' AND yc.thaiSharedGameId='" . $val['id'] . "' AND yd.flag_result = 0 AND yc.status != 5 GROUP BY yc.thaiSharedGameId, yc.id, yd.numberSetLottery
                ")->queryAll();
                if (!empty($dataLotteryVietnamDiscountSetChits)) {
                    $totalWinCreditLotteryVietnamSet = 0;
                    foreach ($dataLotteryVietnamDiscountSetChits as $dataLotteryVietnamDiscountSetChit) {
                        $totalAmountLotteryVietnamSet += $dataLotteryVietnamDiscountSetChit['amount'];
                        $totalSetNumberLotteryVietnamSet += $dataLotteryVietnamDiscountSetChit['setNumber'];
                        $totalWinCreditLotteryVietnamSet += $dataLotteryVietnamDiscountSetChit['win_credit'];
                        $type['amount'] = [
                            'amount' => $totalAmountLotteryVietnamSet,
                            'win_credit' => $totalWinCreditLotteryVietnamSet,
                            'totalSet' => $totalSetNumberLotteryVietnamSet,
                        ];
                    }
                }
                $dataLotteryVietnamSets[$val['title']] = [
                    'create_at' => $val['endDate'],
                    'four_dt' => (!empty($type) && !empty($type['four_dt']) ? $type['four_dt'] : ''),
                    'four_tod' => (!empty($type) && !empty($type['four_tod']) ? $type['four_tod'] : ''),
                    'three_top' => (!empty($type) && !empty($type['three_top']) ? $type['three_top'] : ''),
                    'three_tod' => (!empty($type) && !empty($type['three_tod']) ? $type['three_tod'] : ''),
                    'two_ft' => (!empty($type) && !empty($type['two_ft']) ? $type['two_ft'] : ''),
                    'two_bk' => (!empty($type) && !empty($type['two_bk']) ? $type['two_bk'] : ''),
                    'amount' => (!empty($type) && !empty($type['amount']) ? $type['amount'] : ''),
                ];
            }
        }

        //หวยฮานอย 4D
        $dataVietnam4DThaiSharedChit = Yii::$app->db->createCommand
        ("
              SELECT pl.code, SUM(yd.amount) AS amount, SUM(yd.discount) AS discount, SUM(yd.win_credit) AS win_credit, 
              yc.thaiSharedGameId, yc.status, ts.gameId, ts.title FROM " . ThaiSharedGameChit::tableName() . " yc
              INNER JOIN " . ThaiSharedGameChitDetail::tableName() . " yd ON yd.thaiSharedGameChitId=yc.id
              INNER JOIN " . PlayType::tableName() . " pl ON pl.id = yd.playTypeId
              INNER JOIN " . ThaiSharedGame::tableName() . " ts ON yc.thaiSharedGameId = ts.id
              WHERE yc.status != 5 AND ts.gameId = " . Constants::VIETNAM4D_GAME . " AND DATE(yd.createdAt) = '" . $date . "' GROUP BY yc.thaiSharedGameId, yd.playTypeId
        ")->queryAll();
        $dataVietnam4DThaiSharedGames = array();
        $dataVietnam4DShared = array();
        foreach ($dataVietnam4DThaiSharedChit as $dataVietnam4D) {
            if ($dataVietnam4D['discount']) {
                $totalAmountThaiShared += $dataVietnam4D['discount'];
            } else {
                $totalAmountThaiShared += $dataVietnam4D['amount'];
            }
            $totalWinCreditThaiShared += $dataVietnam4D['win_credit'];
            $dataVietnam4DThaiSharedGames[$dataVietnam4D['title']][$dataVietnam4D['code']] = [
                'amount' => $dataVietnam4D['amount'],
                'discount' => $dataVietnam4D['discount'],
                'win_credit' => $dataVietnam4D['win_credit']
            ];
        }
        foreach ($dataVietnam4DThaiSharedGames as $titile => $dataVietnam4DThaiSharedGame) {
            $dataVietnam4DShared[$titile] = [
                'create_at' => $date,
                'three_top' => (!empty($dataVietnam4DThaiSharedGame) && !empty($dataVietnam4DThaiSharedGame['three_top']) ? $dataVietnam4DThaiSharedGame['three_top'] : ''),
                'three_tod' => (!empty($dataVietnam4DThaiSharedGame) && !empty($dataVietnam4DThaiSharedGame['three_tod']) ? $dataVietnam4DThaiSharedGame['three_tod'] : ''),
                'two_top' => (!empty($dataVietnam4DThaiSharedGame) && !empty($dataVietnam4DThaiSharedGame['two_top']) ? $dataVietnam4DThaiSharedGame['two_top'] : ''),
                'two_under' => (!empty($dataVietnam4DThaiSharedGame) && !empty($dataVietnam4DThaiSharedGame['two_under']) ? $dataVietnam4DThaiSharedGame['two_under'] : ''),
                'run_top' => (!empty($dataVietnam4DThaiSharedGame) && !empty($dataVietnam4DThaiSharedGame['run_top']) ? $dataVietnam4DThaiSharedGame['run_top'] : ''),
                'run_under' => (!empty($dataVietnam4DThaiSharedGame) && !empty($dataVietnam4DThaiSharedGame['run_under']) ? $dataVietnam4DThaiSharedGame['run_under'] : ''),
            ];
        }

        //หวยลาวทดแทน
        $dataLotteryReserveThaiSharedChit = Yii::$app->db->createCommand
        ("
              SELECT pl.code, SUM(yd.amount) AS amount, SUM(yd.discount) AS discount, SUM(yd.win_credit) AS win_credit, 
              yc.thaiSharedGameId, yc.status, ts.gameId, ts.title FROM " . ThaiSharedGameChit::tableName() . " yc
              INNER JOIN " . ThaiSharedGameChitDetail::tableName() . " yd ON yd.thaiSharedGameChitId=yc.id
              INNER JOIN " . PlayType::tableName() . " pl ON pl.id = yd.playTypeId
              INNER JOIN " . ThaiSharedGame::tableName() . " ts ON yc.thaiSharedGameId = ts.id
              WHERE yc.status != 5 AND ts.gameId = " . Constants::LOTTERYRESERVEGAME . " AND DATE(yd.createdAt) = '" . $date . "' GROUP BY yc.thaiSharedGameId, yd.playTypeId
        ")->queryAll();
        $dataLotteryReserveThaiSharedGames = array();
        $dataLotteryReserveShared = array();
        foreach ($dataLotteryReserveThaiSharedChit as $dataLotteryReserve) {
            if ($dataLotteryReserve['discount']) {
                $totalAmountThaiShared += $dataLotteryReserve['discount'];
            } else {
                $totalAmountThaiShared += $dataLotteryReserve['amount'];
            }
            $totalWinCreditThaiShared += $dataLotteryReserve['win_credit'];
            $dataLotteryReserveThaiSharedGames[$dataLotteryReserve['title']][$dataLotteryReserve['code']] = [
                'amount' => $dataLotteryReserve['amount'],
                'discount' => $dataLotteryReserve['discount'],
                'win_credit' => $dataLotteryReserve['win_credit']
            ];
        }
        foreach ($dataLotteryReserveThaiSharedGames as $titile => $dataLotteryReserveThaiSharedGame) {
            $dataLotteryReserveShared[$titile] = [
                'create_at' => $date,
                'three_top' => (!empty($dataLotteryReserveThaiSharedGame) && !empty($dataLotteryReserveThaiSharedGame['three_top']) ? $dataLotteryReserveThaiSharedGame['three_top'] : ''),
                'three_tod' => (!empty($dataLotteryReserveThaiSharedGame) && !empty($dataLotteryReserveThaiSharedGame['three_tod']) ? $dataLotteryReserveThaiSharedGame['three_tod'] : ''),
                'two_top' => (!empty($dataLotteryReserveThaiSharedGame) && !empty($dataLotteryReserveThaiSharedGame['two_top']) ? $dataLotteryReserveThaiSharedGame['two_top'] : ''),
                'two_under' => (!empty($dataLotteryReserveThaiSharedGame) && !empty($dataLotteryReserveThaiSharedGame['two_under']) ? $dataLotteryReserveThaiSharedGame['two_under'] : ''),
                'run_top' => (!empty($dataLotteryReserveThaiSharedGame) && !empty($dataLotteryReserveThaiSharedGame['run_top']) ? $dataLotteryReserveThaiSharedGame['run_top'] : ''),
                'run_under' => (!empty($dataLotteryReserveThaiSharedGame) && !empty($dataLotteryReserveThaiSharedGame['run_under']) ? $dataLotteryReserveThaiSharedGame['run_under'] : ''),
            ];
        }

        return $this->render('daily', [
            'searchModel' => $searchModel,
            'data' => $data,
            'date' => $date,
            'blackreds' => $blackreds,
            'configYeekee' => $configYeekee,
            'configBlackred' => $configBlackred,
            'round' => $round,
            'tab' => isset($tab) ? $tab : 'tab1',
            'dataShared' => $dataShared,
            'dataLotteryLaoSets' => $dataLotteryLaoSets,
            'dataLotterys' => $dataLotterys,
            'dataDownJoans' => $dataDownJoans,
            'dataLotteryVietnamSets' => $dataLotteryVietnamSets,
            'dataGsbShared' => $dataGsbShared,
            'dataBaccShared' => $dataBaccShared,
            'dataLaosChampasakShared' => $dataLaosChampasakShared,
            'dataVietnam4DShared' => $dataVietnam4DShared,
            'dataLotteryReserveShared' => $dataLotteryReserveShared,
        ]);
    }

    public function actionGame()
    {
        $param = Yii::$app->request->post();
        $searchModel = new YeekeeSearch();
        $date = '';
        $round = '';
        if (!empty($param)) {
            $date = $param['YeekeeSearch']['date_at_search'];
            $searchModel->load($param);
            $round = (!empty($param['YeekeeSearch']['round']) ? ' AND round="' . $param['YeekeeSearch']['round'] . '"' : '');
            $tab = $param['tab'];
        } else {
            $date = date('Y-m-d');
        }
        $totalAmountYeekee = 0;
        $totalWinCreditYeekee = 0;
        $totalAmountBlackRed = 0;
        $totalWinCreditBlackRed = 0;
        $totalAmountThaiShared = 0;
        $totalAmountLottery = 0;
        $totalDiscountLottery = 0;
        $totalWinCreditDiscountLottery = 0;
        $totalWinCreditThaiShared = 0;
        $totalWinCreditLottery = 0;
        $totalAmountAllLotteryLaoSet = 0;
        $totalWinCreditAllLotteryLaoSet = 0;
        $dataYeekee = Yii::$app->db->createCommand
        ("
            SELECT * FROM yeekee WHERE date_at='" . $date . "' $round ORDER BY round ASC
        ")->queryAll();

        $data = array();
        if (!empty($dataYeekee)) {
            foreach ($dataYeekee as $val) {
                $dataYeekeeChit = Yii::$app->db->createCommand
                ("
                    SELECT yd.play_type_code, SUM(yd.amount) AS amount, SUM(yd.win_credit) AS win_credit, yc.status FROM yeekee_chit yc
                    INNER JOIN yeekee_chit_detail yd ON yd.yeekee_chit_id=yc.id
                    WHERE yc.yeekee_id='" . $val['id'] . "' and yc.status != 5 GROUP BY yd.play_type_code
                ")->queryAll();

                $type = array();
                if (!empty($dataYeekeeChit)) {
                    foreach ($dataYeekeeChit as $item) {
                        $totalAmountYeekee += $item['amount'];
                        $totalWinCreditYeekee += $item['win_credit'];
                        $type[$item['play_type_code']] = [
                            'amount' => $item['amount'],
                            'win_credit' => $item['win_credit']
                        ];
                    }
                }
                $data[$val['round']] = [
                    'create_at' => $val['finish_at'],
                    'three_top' => (!empty($type) && !empty($type['three_top']) ? $type['three_top'] : ''),
                    'three_tod' => (!empty($type) && !empty($type['three_tod']) ? $type['three_tod'] : ''),
                    'two_top' => (!empty($type) && !empty($type['two_top']) ? $type['two_top'] : ''),
                    'two_under' => (!empty($type) && !empty($type['two_under']) ? $type['two_under'] : ''),
                    'run_top' => (!empty($type) && !empty($type['run_top']) ? $type['run_top'] : ''),
                    'run_under' => (!empty($type) && !empty($type['run_under']) ? $type['run_under'] : ''),
                ];
            }
        }

        $configYeekee = ConfigGenerateGame::find()->where(['game_id' => 1])->one();
        $configBlackred = ConfigGenerateGame::find()->where(['game_id' => 2])->one();
        $searchModel->round = isset($param['YeekeeSearch']['round']) ? $param['YeekeeSearch']['round'] : '';
        $dataBlackreds = BlackRed::find()->where(['date_at' => $date])->orderBy('round ASC');
        if ($searchModel->round) {
            $dataBlackreds->andWhere(['round' => $param['YeekeeSearch']['round']]);
        }
        $dataBlackreds = $dataBlackreds->all();
        $blackreds = [];
        if ($dataBlackreds) {
            foreach ($dataBlackreds as $blackred) {
                $sumTotalBlack = BlackredChit::find()->where(['blackred_id' => $blackred->id, 'play_type_code' => 1])->sum('total_amount');
                $sumTotalWinBlack = BlackredChit::find()->where(['blackred_id' => $blackred->id, 'play_type_code' => 1, 'flag_result' => 1])->sum('total_amount');
                $sumTotalRed = BlackredChit::find()->where(['blackred_id' => $blackred->id, 'play_type_code' => 2])->sum('total_amount');
                $sumTotalWinRed = BlackredChit::find()->where(['blackred_id' => $blackred->id, 'play_type_code' => 2, 'flag_result' => 1])->sum('total_amount');
                $totalAmountBlackRed += $sumTotalBlack;
                $totalWinCreditBlackRed += $sumTotalWinBlack;
                $blackreds[$blackred->round] = [
                    [
                        'round' => 'ดำ',
                        'date' => $blackred->create_at,
                        'amount' => $sumTotalBlack,
                        'win_credit' => $sumTotalWinBlack
                    ],
                    [
                        'round' => 'แดง',
                        'date' => $blackred->finish_at,
                        'amount' => $sumTotalRed,
                        'win_credit' => $sumTotalWinRed
                    ]
                ];
            }
        }

        $dataThaiShared = Yii::$app->db->createCommand
        ("
            SELECT * FROM " . ThaiSharedGame::tableName() . " WHERE ((DATE(startDate) BETWEEN '" . $date . "' AND '" . $date . "' 
            AND gameId NOT IN (" . Constants::LOTTERYLAOGAME . ", " . Constants::LOTTERYLAODISCOUNTGAME . ", " . Constants::LOTTERYGAME . ", "
            . Constants::LOTTERYLAODISCOUNTGAME . ",".Constants::LOTTERY_VIETNAM_SET.",".Constants::GSB_THAISHARD_GAME.","
            .Constants::BACC_THAISHARD_GAME.",".Constants::LAOS_CHAMPASAK_LOTTERY_GAME.",".Constants::VIETNAM4D_GAME.",".Constants::LOTTERYRESERVEGAME.") 
            AND title != 'หวยหุ้นดาวน์โจน') OR (DATE(endDate) BETWEEN '" . $date . "' AND '" . $date . "' 
            AND gameId NOT IN (" . Constants::LOTTERYLAOGAME . ", " . Constants::LOTTERYLAODISCOUNTGAME . ", "
            . Constants::LOTTERYGAME . ", " . Constants::LOTTERYGAMEDISCOUNT . ",". Constants::LOTTERY_VIETNAM_SET.","
            .Constants::GSB_THAISHARD_GAME.",".Constants::BACC_THAISHARD_GAME.",".Constants::LAOS_CHAMPASAK_LOTTERY_GAME.","
            .Constants::VIETNAM4D_GAME.",".Constants::LOTTERYRESERVEGAME.") AND title != 'หวยหุ้นดาวน์โจน')) AND status IN (1, 9)
            ORDER BY endDate ASC
        ")->queryAll();
        $dataShared = array();
        if (!empty($dataThaiShared)) {
            foreach ($dataThaiShared as $val) {
                $dataThaiSharedChit = Yii::$app->db->createCommand
                ("
                    SELECT pl.code, SUM(yd.amount) AS amount, SUM(yd.discount) AS discount, SUM(yd.win_credit) AS win_credit, yc.thaiSharedGameId, yc.status FROM " . ThaiSharedGameChit::tableName() . " yc
                    INNER JOIN " . ThaiSharedGameChitDetail::tableName() . " yd ON yd.thaiSharedGameChitId=yc.id
                    INNER JOIN " . PlayType::tableName() . " pl ON pl.id = yd.playTypeId
                    WHERE yc.thaiSharedGameId='" . $val['id'] . "' and yc.status != 5 GROUP BY yc.thaiSharedGameId, yd.playTypeId
                ")->queryAll();
                $type = array();
                if (!empty($dataThaiSharedChit)) {
                    foreach ($dataThaiSharedChit as $dataThaiShared) {
                        if ($dataThaiShared['discount']) {
                            $totalAmountThaiShared += $dataThaiShared['discount'];
                        } else {
                            $totalAmountThaiShared += $dataThaiShared['amount'];
                        }
                        $totalWinCreditThaiShared += $dataThaiShared['win_credit'];
                        $type[$dataThaiShared['code']] = [
                            'amount' => $dataThaiShared['amount'],
                            'discount' => $dataThaiShared['discount'],
                            'win_credit' => $dataThaiShared['win_credit']
                        ];
                    }
                }
                $dataShared[$val['title']] = [
                    'create_at' => $val['endDate'],
                    'three_top' => (!empty($type) && !empty($type['three_top']) ? $type['three_top'] : ''),
                    'three_tod' => (!empty($type) && !empty($type['three_tod']) ? $type['three_tod'] : ''),
                    'two_top' => (!empty($type) && !empty($type['two_top']) ? $type['two_top'] : ''),
                    'two_under' => (!empty($type) && !empty($type['two_under']) ? $type['two_under'] : ''),
                    'run_top' => (!empty($type) && !empty($type['run_top']) ? $type['run_top'] : ''),
                    'run_under' => (!empty($type) && !empty($type['run_under']) ? $type['run_under'] : ''),
                ];
            }
        }

        $dataLotteryGame = Yii::$app->db->createCommand
        ("
            SELECT * FROM " . ThaiSharedGame::tableName() . " WHERE (DATE(startDate) BETWEEN '" . $date . "' AND '" . $date . "' 
            AND gameId  IN (" . Constants::LOTTERYGAME . ", " . Constants::LOTTERYGAMEDISCOUNT . ")) OR (DATE(endDate) 
            BETWEEN '" . $date . "' AND '" . $date . "' AND gameId IN (" . Constants::LOTTERYGAME . ", " . Constants::LOTTERYGAMEDISCOUNT . ")) 
            AND status IN (1, 9) ORDER BY endDate ASC
        ")->queryAll();
        $dataLotterys = array();

        if (!empty($dataLotteryGame)) {
            foreach ($dataLotteryGame as $val) {   
                $ThaiSharedAnswerGames = ThaiSharedAnswerGame::find()->where(['thaiSharedGameId' => $val['id']])->joinWith('playType')->orderBy(PlayType::tableName() . '.jackpot_per_unit DESC')->groupBy('playTypeId, number')->all();
                $cktyp = array();
                if(!empty($ThaiSharedAnswerGames)){
                    foreach ($ThaiSharedAnswerGames as $key => $valueTSAG) {                    
                        $cktyp[$valueTSAG['playTypeId']][] =$valueTSAG['number']       ;
                    }                    
                }
                $win_model_c = array();
                $win_model_b = 0;
                if(!empty($cktyp)){
                    foreach ($cktyp as $keycktyp => $valuecktyp) {
                        $ThaiSharedGameChit = ThaiSharedGameChit::find()->where(['thaiSharedGameId' => $val['id']])->all();
                        if(!empty($ThaiSharedGameChit)){
                            foreach ($ThaiSharedGameChit as $key => $value) {  
                                $ThaiSharedGameChitDetail = ThaiSharedGameChitDetail::find()->where(['thaiSharedGameChitId' => $value['id']])->andwhere(['playTypeId' => $keycktyp])->andwhere(['in', 'number', $valuecktyp])->all();
                                if(!empty($ThaiSharedGameChitDetail)){
                                    
                                    foreach ($ThaiSharedGameChitDetail as $key => $valueTSGCD) {
                                        $jackPotPerUnit = $valueTSGCD->jackPotPerUnit;
                                        (string)$win_model_a = ((string)$valueTSGCD->amount * (string)$jackPotPerUnit);
                                        (string)$win_model_b += (string)$win_model_a;
                                        $win_model_c['playTypeId'][$keycktyp] = (string)$win_model_b;
                                    }   
                                    //echo "<pre>",var_dump($win_model_b),"</pre>";
                                    //exit();                        
                                }                        
                            }     
                            //echo "<pre>",var_dump($win_model_b),"</pre>";     
                        }                    
                    }                    
                }
                $dataThaiSharedChit = Yii::$app->db->createCommand
                ("
                    SELECT pl.code, SUM(yd.amount) AS amount, SUM(yd.discount) AS discount, SUM(yd.win_credit) AS win_credit, yc.thaiSharedGameId, yc.status FROM " . ThaiSharedGameChit::tableName() . " yc
                    INNER JOIN " . ThaiSharedGameChitDetail::tableName() . " yd ON yd.thaiSharedGameChitId=yc.id
                    INNER JOIN " . PlayType::tableName() . " pl ON pl.id = yd.playTypeId
                    WHERE yc.thaiSharedGameId='" . $val['id'] . "' and yc.status != 5 GROUP BY yc.thaiSharedGameId, yd.playTypeId
                ")->queryAll();
                $type = array();
                
                if (!empty($dataThaiSharedChit)) {
                    foreach ($dataThaiSharedChit as $key => $dataThaiShared) {
                        //echo "<pre>",var_dump($dataThaiShared),"</pre>";
                        //exit();
                        $win_model = 1+$key;
                        if ($val['gameId'] == Constants::LOTTERYGAME) {
                            $totalAmountLottery += $dataThaiShared['amount'];
                            $totalWinCreditLottery += $dataThaiShared['win_credit'];
                        } else if ($val['gameId'] == Constants::LOTTERYGAMEDISCOUNT) {
                            $totalAmountLottery += $dataThaiShared['discount'];
                            $totalWinCreditDiscountLottery += $dataThaiShared['win_credit'];
                        }
                        $type[$dataThaiShared['code']] = [
                            'amount' => $dataThaiShared['amount'],
                            'discount' => $dataThaiShared['discount'],
                            'win_model' => $win_model,
                            'win_credit' => $dataThaiShared['win_credit']
                        ];
                    }
                }
                $dataLotterys[$val['title']] = [
                    'create_at' => $val['endDate'],
                    'three_top' => (!empty($type) && !empty($type['three_top']) ? $type['three_top'] : ''),
                    'three_tod' => (!empty($type) && !empty($type['three_tod']) ? $type['three_tod'] : ''),
                    'two_top' => (!empty($type) && !empty($type['two_top']) ? $type['two_top'] : ''),
                    'two_under' => (!empty($type) && !empty($type['two_under']) ? $type['two_under'] : ''),
                    'run_top' => (!empty($type) && !empty($type['run_top']) ? $type['run_top'] : ''),
                    'run_under' => (!empty($type) && !empty($type['run_under']) ? $type['run_under'] : ''),
                    'three_top2' => (!empty($type) && !empty($type['three_top2']) ? $type['three_top2'] : ''),
                    'three_und2' => (!empty($type) && !empty($type['three_und2']) ? $type['three_und2'] : ''),
                    'win_model_c' => (!empty($win_model_c) && !empty($win_model_c['playTypeId']) ? $win_model_c['playTypeId'] : '')
                ];
            }
        }
        //หวยออมสิน
        $dataGsbThaiShared = Yii::$app->db->createCommand
        ("
            SELECT * FROM " . ThaiSharedGame::tableName() . " WHERE ((DATE(startDate) BETWEEN '" . $date . "' AND '" . $date . "' 
            AND gameId = ".Constants::GSB_THAISHARD_GAME.") OR (DATE(endDate) BETWEEN '" . $date . "' AND '" . $date . "' 
            AND gameId = ".Constants::GSB_THAISHARD_GAME.")) AND status IN (1, 9)
            ORDER BY endDate ASC
        ")->queryAll();
        $dataGsbShared = array();
        if (!empty($dataGsbThaiShared)) {
            foreach ($dataGsbThaiShared as $val) {
                $dataGsbThaiSharedChit = Yii::$app->db->createCommand
                ("
                    SELECT pl.code, SUM(yd.amount) AS amount, SUM(yd.discount) AS discount, SUM(yd.win_credit) AS win_credit, yc.thaiSharedGameId, yc.status FROM " . ThaiSharedGameChit::tableName() . " yc
                    INNER JOIN " . ThaiSharedGameChitDetail::tableName() . " yd ON yd.thaiSharedGameChitId=yc.id
                    INNER JOIN " . PlayType::tableName() . " pl ON pl.id = yd.playTypeId
                    WHERE yc.thaiSharedGameId='" . $val['id'] . "' and yc.status != 5 GROUP BY yc.thaiSharedGameId, yd.playTypeId
                ")->queryAll();
                $type = array();
                if (!empty($dataGsbThaiSharedChit)) {
                    foreach ($dataGsbThaiSharedChit as $dataThaiShared) {
                        if ($dataThaiShared['discount']) {
                            $totalAmountThaiShared += $dataThaiShared['discount'];
                        } else {
                            $totalAmountThaiShared += $dataThaiShared['amount'];
                        }
                        $totalWinCreditThaiShared += $dataThaiShared['win_credit'];
                        $type[$dataThaiShared['code']] = [
                            'amount' => $dataThaiShared['amount'],
                            'discount' => $dataThaiShared['discount'],
                            'win_credit' => $dataThaiShared['win_credit']
                        ];
                    }
                }
                $dataGsbShared[$val['title']] = [
                    'create_at' => $val['endDate'],
                    'three_top' => (!empty($type) && !empty($type['three_top']) ? $type['three_top'] : ''),
                    'three_tod' => (!empty($type) && !empty($type['three_tod']) ? $type['three_tod'] : ''),
                    'two_top' => (!empty($type) && !empty($type['two_top']) ? $type['two_top'] : ''),
                    'two_under' => (!empty($type) && !empty($type['two_under']) ? $type['two_under'] : ''),
                    'run_top' => (!empty($type) && !empty($type['run_top']) ? $type['run_top'] : ''),
                    'run_under' => (!empty($type) && !empty($type['run_under']) ? $type['run_under'] : ''),
                ];
            }
        }

        //หวยธกส
        $dataBaccThaiShared = Yii::$app->db->createCommand
        ("
            SELECT * FROM " . ThaiSharedGame::tableName() . " WHERE ((DATE(startDate) BETWEEN '" . $date . "' AND '" . $date . "' 
            AND gameId = ".Constants::BACC_THAISHARD_GAME.") OR (DATE(endDate) BETWEEN '" . $date . "' AND '" . $date . "' 
            AND gameId = ".Constants::BACC_THAISHARD_GAME.")) AND status IN (1, 9)
            ORDER BY endDate ASC
        ")->queryAll();
        $dataBaccShared = array();
        if (!empty($dataBaccThaiShared)) {
            foreach ($dataBaccThaiShared as $val) {
                $dataBaccThaiSharedChit = Yii::$app->db->createCommand
                ("
                    SELECT pl.code, SUM(yd.amount) AS amount, SUM(yd.discount) AS discount, SUM(yd.win_credit) AS win_credit, yc.thaiSharedGameId, yc.status FROM " . ThaiSharedGameChit::tableName() . " yc
                    INNER JOIN " . ThaiSharedGameChitDetail::tableName() . " yd ON yd.thaiSharedGameChitId=yc.id
                    INNER JOIN " . PlayType::tableName() . " pl ON pl.id = yd.playTypeId
                    WHERE yc.thaiSharedGameId='" . $val['id'] . "' and yc.status != 5 GROUP BY yc.thaiSharedGameId, yd.playTypeId
                ")->queryAll();
                $type = array();
                if (!empty($dataBaccThaiSharedChit)) {
                    foreach ($dataBaccThaiSharedChit as $dataThaiShared) {
                        if ($dataThaiShared['discount']) {
                            $totalAmountThaiShared += $dataThaiShared['discount'];
                        } else {
                            $totalAmountThaiShared += $dataThaiShared['amount'];
                        }
                        $totalWinCreditThaiShared += $dataThaiShared['win_credit'];
                        $type[$dataThaiShared['code']] = [
                            'amount' => $dataThaiShared['amount'],
                            'discount' => $dataThaiShared['discount'],
                            'win_credit' => $dataThaiShared['win_credit']
                        ];
                    }
                }
                $dataBaccShared[$val['title']] = [
                    'create_at' => $val['endDate'],
                    'three_top' => (!empty($type) && !empty($type['three_top']) ? $type['three_top'] : ''),
                    'three_tod' => (!empty($type) && !empty($type['three_tod']) ? $type['three_tod'] : ''),
                    'two_top' => (!empty($type) && !empty($type['two_top']) ? $type['two_top'] : ''),
                    'two_under' => (!empty($type) && !empty($type['two_under']) ? $type['two_under'] : ''),
                    'run_top' => (!empty($type) && !empty($type['run_top']) ? $type['run_top'] : ''),
                    'run_under' => (!empty($type) && !empty($type['run_under']) ? $type['run_under'] : ''),
                ];
            }
        }

        //หวยลาว จำปาสัก
        $dataLaosChampasakThaiShared = Yii::$app->db->createCommand
        ("
            SELECT * FROM " . ThaiSharedGame::tableName() . " WHERE ((DATE(startDate) BETWEEN '" . $date . "' AND '" . $date . "' 
            AND gameId = ".Constants::LAOS_CHAMPASAK_LOTTERY_GAME.") OR (DATE(endDate) BETWEEN '" . $date . "' AND '" . $date . "' 
            AND gameId = ".Constants::LAOS_CHAMPASAK_LOTTERY_GAME.")) AND status IN (1, 9)
            ORDER BY endDate ASC
        ")->queryAll();
        $dataLaosChampasakShared = array();
        if (!empty($dataLaosChampasakThaiShared)) {
            foreach ($dataLaosChampasakThaiShared as $val) {
                $dataLaosChampasakThaiSharedChit = Yii::$app->db->createCommand
                ("
                    SELECT pl.code, SUM(yd.amount) AS amount, SUM(yd.discount) AS discount, SUM(yd.win_credit) AS win_credit, yc.thaiSharedGameId, yc.status FROM " . ThaiSharedGameChit::tableName() . " yc
                    INNER JOIN " . ThaiSharedGameChitDetail::tableName() . " yd ON yd.thaiSharedGameChitId=yc.id
                    INNER JOIN " . PlayType::tableName() . " pl ON pl.id = yd.playTypeId
                    WHERE yc.thaiSharedGameId='" . $val['id'] . "' and yc.status != 5 GROUP BY yc.thaiSharedGameId, yd.playTypeId
                ")->queryAll();
                $type = array();
                if (!empty($dataLaosChampasakThaiSharedChit)) {
                    foreach ($dataLaosChampasakThaiSharedChit as $dataThaiShared) {
                        if ($dataThaiShared['discount']) {
                            $totalAmountThaiShared += $dataThaiShared['discount'];
                        } else {
                            $totalAmountThaiShared += $dataThaiShared['amount'];
                        }
                        $totalWinCreditThaiShared += $dataThaiShared['win_credit'];
                        $type[$dataThaiShared['code']] = [
                            'amount' => $dataThaiShared['amount'],
                            'discount' => $dataThaiShared['discount'],
                            'win_credit' => $dataThaiShared['win_credit']
                        ];
                    }
                }
                $dataLaosChampasakShared[$val['title']] = [
                    'create_at' => $val['endDate'],
                    'three_top' => (!empty($type) && !empty($type['three_top']) ? $type['three_top'] : ''),
                    'three_tod' => (!empty($type) && !empty($type['three_tod']) ? $type['three_tod'] : ''),
                    'two_top' => (!empty($type) && !empty($type['two_top']) ? $type['two_top'] : ''),
                    'two_under' => (!empty($type) && !empty($type['two_under']) ? $type['two_under'] : ''),
                    'run_top' => (!empty($type) && !empty($type['run_top']) ? $type['run_top'] : ''),
                    'run_under' => (!empty($type) && !empty($type['run_under']) ? $type['run_under'] : ''),
                ];
            }
        }

        $dataDownJoan = Yii::$app->db->createCommand
        ("
            SELECT * FROM " . ThaiSharedGame::tableName() . " WHERE (DATE(startDate) BETWEEN '" . $date . "' AND '" . $date . "' 
            AND gameId NOT IN (" . Constants::LOTTERYLAOGAME . ", " . Constants::LOTTERYLAODISCOUNTGAME . ", " . Constants::LOTTERYGAME . ", "
            . Constants::LOTTERYGAMEDISCOUNT . ") AND title = 'หวยหุ้นดาวน์โจน') OR (DATE(endDate) BETWEEN '" . $date . "' AND '" . $date . "' 
            AND gameId NOT IN (" . Constants::LOTTERYLAOGAME . ", " . Constants::LOTTERYLAODISCOUNTGAME . ", "
            . Constants::LOTTERYGAME . ", " . Constants::LOTTERYGAMEDISCOUNT . ") AND title = 'หวยหุ้นดาวน์โจน') AND status IN (1, 9) ORDER BY endDate ASC
        ")->queryAll();
        $dataDownJoans = array();
        if (!empty($dataDownJoan)) {
            foreach ($dataDownJoan as $val) {
                $dataThaiSharedChit = Yii::$app->db->createCommand
                ("
                    SELECT pl.code, SUM(yd.amount) AS amount, SUM(yd.discount) AS discount, SUM(yd.win_credit) AS win_credit, yc.thaiSharedGameId, yc.status FROM " . ThaiSharedGameChit::tableName() . " yc
                    INNER JOIN " . ThaiSharedGameChitDetail::tableName() . " yd ON yd.thaiSharedGameChitId=yc.id
                    INNER JOIN " . PlayType::tableName() . " pl ON pl.id = yd.playTypeId
                    WHERE yc.thaiSharedGameId='" . $val['id'] . "' and yc.status != 5 GROUP BY yc.thaiSharedGameId, yd.playTypeId
                ")->queryAll();
                $type = array();
                if (!empty($dataThaiSharedChit)) {
                    foreach ($dataThaiSharedChit as $dataThaiShared) {
                        if ($dataThaiShared['discount']) {
                            $totalAmountThaiShared += $dataThaiShared['discount'];
                        } else {
                            $totalAmountThaiShared += $dataThaiShared['amount'];
                        }
                        $totalWinCreditThaiShared += $dataThaiShared['win_credit'];
                        $type[$dataThaiShared['code']] = [
                            'amount' => $dataThaiShared['amount'],
                            'discount' => $dataThaiShared['discount'],
                            'win_credit' => $dataThaiShared['win_credit']
                        ];
                    }
                }
                $dataDownJoans[$val['title']] = [
                    'create_at' => $val['endDate'],
                    'three_top' => (!empty($type) && !empty($type['three_top']) ? $type['three_top'] : ''),
                    'three_tod' => (!empty($type) && !empty($type['three_tod']) ? $type['three_tod'] : ''),
                    'two_top' => (!empty($type) && !empty($type['two_top']) ? $type['two_top'] : ''),
                    'two_under' => (!empty($type) && !empty($type['two_under']) ? $type['two_under'] : ''),
                    'run_top' => (!empty($type) && !empty($type['run_top']) ? $type['run_top'] : ''),
                    'run_under' => (!empty($type) && !empty($type['run_under']) ? $type['run_under'] : ''),
                ];
            }
        }


        $dataLotteryLaoSetGame = Yii::$app->db->createCommand
        ("
            SELECT * FROM " . ThaiSharedGame::tableName() . " WHERE (DATE(startDate) BETWEEN '" . $date . "' AND '" . $date . "' 
            AND gameId IN (" . Constants::LOTTERYLAOGAME . "," . Constants::LOTTERYLAODISCOUNTGAME . ")) OR 
            (DATE(endDate) BETWEEN '" . $date . "' AND '" . $date . "' AND gameId IN 
            (" . Constants::LOTTERYLAOGAME . "," . Constants::LOTTERYLAODISCOUNTGAME . ")) AND status IN (1, 9)
            ORDER BY endDate ASC
        ")->queryAll();
        $dataLotteryLaoSets = array();
        if (!empty($dataLotteryLaoSetGame)) {
            foreach ($dataLotteryLaoSetGame as $val) {
                $totalSetNumberLotteryLaoSet = 0;
                $totalAmountLotteryLaoSet = 0;
                $dataLotteryLaoSetChits = Yii::$app->db->createCommand
                ("
                    SELECT pl.code, amount, SUM(yd.win_credit) AS win_credit, yc.thaiSharedGameId, yd.flag_result, setNumber, yc.id, yc.status FROM " . ThaiSharedGameChit::tableName() . " yc
                    INNER JOIN " . ThaiSharedGameChitDetail::tableName() . " yd ON yd.thaiSharedGameChitId=yc.id
                    INNER JOIN " . PlayType::tableName() . " pl ON pl.id = yd.playTypeId
                    WHERE yc.thaiSharedGameId='" . $val['id'] . "' AND yd.flag_result = 1 AND yc.status != 5 GROUP BY yc.thaiSharedGameId, pl.code
                ")->queryAll();
                $type = array();
                if (!empty($dataLotteryLaoSetChits)) {
                    $totalWinCreditLotteryLaoSet = 0;
                    foreach ($dataLotteryLaoSetChits as $dataLotteryLaoSetChit) {
                        $totalWinCreditLotteryLaoSet += $dataLotteryLaoSetChit['win_credit'];
                        $type[$dataLotteryLaoSetChit['code']] = [
                            'amount' => 0,
                            'win_credit' => $dataLotteryLaoSetChit['win_credit']
                        ];
                    }
                }
                $dataLotteryLaoDiscountSetChits = Yii::$app->db->createCommand
                ("
                    SELECT yc.thaiSharedGameId,yc.totalAmount, yd.flag_result, yd.numberSetLottery, yd.amount, yd.setNumber, yc.id, yc.status, yd.win_credit FROM " . ThaiSharedGameChit::tableName() . " yc
                    INNER JOIN " . ThaiSharedGameChitDetail::tableName() . " yd ON yd.thaiSharedGameChitId=yc.id
                    WHERE yc.thaiSharedGameId='" . $val['id'] . "' AND yd.flag_result = 0 AND yc.status != 5 GROUP BY yc.thaiSharedGameId, yc.id, yd.numberSetLottery
                ")->queryAll();
                if (!empty($dataLotteryLaoDiscountSetChits)) {
                    $totalWinCreditLotteryLaoSet = 0;
                    foreach ($dataLotteryLaoDiscountSetChits as $dataLotteryLaoDiscountSetChit) {
                        $totalAmountLotteryLaoSet += $dataLotteryLaoDiscountSetChit['amount'];
                        $totalSetNumberLotteryLaoSet += $dataLotteryLaoDiscountSetChit['setNumber'];
                        $totalWinCreditLotteryLaoSet += $dataLotteryLaoDiscountSetChit['win_credit'];
                        $type['amount'] = [
                            'amount' => $totalAmountLotteryLaoSet,
                            'win_credit' => $totalWinCreditLotteryLaoSet,
                            'totalSet' => $totalSetNumberLotteryLaoSet,
                        ];
                    }
                }
                $dataLotteryLaoSets[$val['title']] = [
                    'create_at' => $val['endDate'],
                    'four_dt' => (!empty($type) && !empty($type['four_dt']) ? $type['four_dt'] : ''),
                    'four_tod' => (!empty($type) && !empty($type['four_tod']) ? $type['four_tod'] : ''),
                    'three_top' => (!empty($type) && !empty($type['three_top']) ? $type['three_top'] : ''),
                    'three_tod' => (!empty($type) && !empty($type['three_tod']) ? $type['three_tod'] : ''),
                    'two_ft' => (!empty($type) && !empty($type['two_ft']) ? $type['two_ft'] : ''),
                    'two_bk' => (!empty($type) && !empty($type['two_bk']) ? $type['two_bk'] : ''),
                    'amount' => (!empty($type) && !empty($type['amount']) ? $type['amount'] : ''),
                ];
            }
        }

        $dataLotteryVietnamSetGame = Yii::$app->db->createCommand
        ("
            SELECT * FROM " . ThaiSharedGame::tableName() . " WHERE (DATE(startDate) BETWEEN '" . $date . "' AND '" . $date . "' 
            AND gameId = " . Constants::LOTTERY_VIETNAM_SET . ") OR 
            (DATE(endDate) BETWEEN '" . $date . "' AND '" . $date . "' AND gameId = 
            " . Constants::LOTTERY_VIETNAM_SET . ") AND status IN (1, 9)
            ORDER BY endDate ASC
        ")->queryAll();
        $dataLotteryVietnamSets = array();
        if (!empty($dataLotteryVietnamSetGame)) {
            foreach ($dataLotteryVietnamSetGame as $val) {
                $totalSetNumberLotteryVietnamSet = 0;
                $totalAmountLotteryVietnamSet = 0;
                $dataLotteryVietnamSetChits = Yii::$app->db->createCommand
                ("
                    SELECT pl.code, amount, SUM(yd.win_credit) AS win_credit, yc.thaiSharedGameId, yd.flag_result, setNumber, yc.id, yc.status FROM " . ThaiSharedGameChit::tableName() . " yc
                    INNER JOIN " . ThaiSharedGameChitDetail::tableName() . " yd ON yd.thaiSharedGameChitId=yc.id
                    INNER JOIN " . PlayType::tableName() . " pl ON pl.id = yd.playTypeId
                    WHERE yc.thaiSharedGameId='" . $val['id'] . "' AND yd.flag_result = 1 AND yc.status != 5 GROUP BY yc.thaiSharedGameId, pl.code
                ")->queryAll();
                $type = array();
                if (!empty($dataLotteryVietnamSetChits)) {
                    $totalWinCreditLotteryVietnamSet = 0;
                    foreach ($dataLotteryVietnamSetChits as $dataLotteryVietnamSetChit) {
                        $totalWinCreditLotteryVietnamSet += $dataLotteryVietnamSetChit['win_credit'];
                        $type[$dataLotteryVietnamSetChit['code']] = [
                            'amount' => 0,
                            'win_credit' => $dataLotteryVietnamSetChit['win_credit']
                        ];
                    }
                }
                $dataLotteryVietnamDiscountSetChits = Yii::$app->db->createCommand
                ("
                    SELECT yc.thaiSharedGameId,yc.totalAmount, yd.flag_result, yd.numberSetLottery, yd.amount, yd.setNumber, yc.id, yc.status, yd.win_credit FROM " . ThaiSharedGameChit::tableName() . " yc
                    INNER JOIN " . ThaiSharedGameChitDetail::tableName() . " yd ON yd.thaiSharedGameChitId=yc.id
                    WHERE yc.thaiSharedGameId='" . $val['id'] . "' AND yd.flag_result = 0 AND yc.status != 5 GROUP BY yc.thaiSharedGameId, yc.id, yd.numberSetLottery
                ")->queryAll();
                if (!empty($dataLotteryVietnamDiscountSetChits)) {
                    $totalWinCreditLotteryVietnamSet = 0;
                    foreach ($dataLotteryVietnamDiscountSetChits as $dataLotteryVietnamDiscountSetChit) {
                        $totalAmountLotteryVietnamSet += $dataLotteryVietnamDiscountSetChit['amount'];
                        $totalSetNumberLotteryVietnamSet += $dataLotteryVietnamDiscountSetChit['setNumber'];
                        $totalWinCreditLotteryVietnamSet += $dataLotteryVietnamDiscountSetChit['win_credit'];
                        $type['amount'] = [
                            'amount' => $totalAmountLotteryVietnamSet,
                            'win_credit' => $totalWinCreditLotteryVietnamSet,
                            'totalSet' => $totalSetNumberLotteryVietnamSet,
                        ];
                    }
                }
                $dataLotteryVietnamSets[$val['title']] = [
                    'create_at' => $val['endDate'],
                    'four_dt' => (!empty($type) && !empty($type['four_dt']) ? $type['four_dt'] : ''),
                    'four_tod' => (!empty($type) && !empty($type['four_tod']) ? $type['four_tod'] : ''),
                    'three_top' => (!empty($type) && !empty($type['three_top']) ? $type['three_top'] : ''),
                    'three_tod' => (!empty($type) && !empty($type['three_tod']) ? $type['three_tod'] : ''),
                    'two_ft' => (!empty($type) && !empty($type['two_ft']) ? $type['two_ft'] : ''),
                    'two_bk' => (!empty($type) && !empty($type['two_bk']) ? $type['two_bk'] : ''),
                    'amount' => (!empty($type) && !empty($type['amount']) ? $type['amount'] : ''),
                ];
            }
        }

        //หวยฮานอย 4D
        $dataVietnam4DThaiShared = Yii::$app->db->createCommand
        ("
            SELECT * FROM " . ThaiSharedGame::tableName() . " WHERE ((DATE(startDate) BETWEEN '" . $date . "' AND '" . $date . "' 
            AND gameId = ".Constants::VIETNAM4D_GAME.") OR (DATE(endDate) BETWEEN '" . $date . "' AND '" . $date . "' 
            AND gameId = ".Constants::VIETNAM4D_GAME.")) AND status IN (1, 9)
            ORDER BY endDate ASC
        ")->queryAll();
        $dataVietnam4DShared = array();
        if (!empty($dataVietnam4DThaiShared)) {
            foreach ($dataVietnam4DThaiShared as $val) {
                $dataVietnam4DThaiSharedChit = Yii::$app->db->createCommand
                ("
                    SELECT pl.code, SUM(yd.amount) AS amount, SUM(yd.discount) AS discount, SUM(yd.win_credit) AS win_credit, yc.thaiSharedGameId, yc.status FROM " . ThaiSharedGameChit::tableName() . " yc
                    INNER JOIN " . ThaiSharedGameChitDetail::tableName() . " yd ON yd.thaiSharedGameChitId=yc.id
                    INNER JOIN " . PlayType::tableName() . " pl ON pl.id = yd.playTypeId
                    WHERE yc.thaiSharedGameId='" . $val['id'] . "' and yc.status != 5 GROUP BY yc.thaiSharedGameId, yd.playTypeId
                ")->queryAll();
                $type = array();
                if (!empty($dataVietnam4DThaiSharedChit)) {
                    foreach ($dataVietnam4DThaiSharedChit as $dataThaiShared) {
                        if ($dataThaiShared['discount']) {
                            $totalAmountThaiShared += $dataThaiShared['discount'];
                        } else {
                            $totalAmountThaiShared += $dataThaiShared['amount'];
                        }
                        $totalWinCreditThaiShared += $dataThaiShared['win_credit'];
                        $type[$dataThaiShared['code']] = [
                            'amount' => $dataThaiShared['amount'],
                            'discount' => $dataThaiShared['discount'],
                            'win_credit' => $dataThaiShared['win_credit']
                        ];
                    }
                }
                $dataVietnam4DShared[$val['title']] = [
                    'create_at' => $val['endDate'],
                    'three_top' => (!empty($type) && !empty($type['three_top']) ? $type['three_top'] : ''),
                    'three_tod' => (!empty($type) && !empty($type['three_tod']) ? $type['three_tod'] : ''),
                    'two_top' => (!empty($type) && !empty($type['two_top']) ? $type['two_top'] : ''),
                    'two_under' => (!empty($type) && !empty($type['two_under']) ? $type['two_under'] : ''),
                    'run_top' => (!empty($type) && !empty($type['run_top']) ? $type['run_top'] : ''),
                    'run_under' => (!empty($type) && !empty($type['run_under']) ? $type['run_under'] : ''),
                ];
            }
        }

        //หวยลาวทดแทน
        $dataReserveThaiShared = Yii::$app->db->createCommand
        ("
            SELECT * FROM " . ThaiSharedGame::tableName() . " WHERE ((DATE(startDate) BETWEEN '" . $date . "' AND '" . $date . "' 
            AND gameId = ".Constants::LOTTERYRESERVEGAME.") OR (DATE(endDate) BETWEEN '" . $date . "' AND '" . $date . "' 
            AND gameId = ".Constants::LOTTERYRESERVEGAME.")) AND status IN (1, 9)
            ORDER BY endDate ASC
        ")->queryAll();
        $dataReserveShared = array();
        if (!empty($dataReserveThaiShared)) {
            foreach ($dataReserveThaiShared as $val) {
                $dataReserveThaiSharedChit = Yii::$app->db->createCommand
                ("
                    SELECT pl.code, SUM(yd.amount) AS amount, SUM(yd.discount) AS discount, SUM(yd.win_credit) AS win_credit, yc.thaiSharedGameId, yc.status FROM " . ThaiSharedGameChit::tableName() . " yc
                    INNER JOIN " . ThaiSharedGameChitDetail::tableName() . " yd ON yd.thaiSharedGameChitId=yc.id
                    INNER JOIN " . PlayType::tableName() . " pl ON pl.id = yd.playTypeId
                    WHERE yc.thaiSharedGameId='" . $val['id'] . "' and yc.status != 5 GROUP BY yc.thaiSharedGameId, yd.playTypeId
                ")->queryAll();
                $type = array();
                if (!empty($dataReserveThaiSharedChit)) {
                    foreach ($dataReserveThaiSharedChit as $dataThaiShared) {
                        if ($dataThaiShared['discount']) {
                            $totalAmountThaiShared += $dataThaiShared['discount'];
                        } else {
                            $totalAmountThaiShared += $dataThaiShared['amount'];
                        }
                        $totalWinCreditThaiShared += $dataThaiShared['win_credit'];
                        $type[$dataThaiShared['code']] = [
                            'amount' => $dataThaiShared['amount'],
                            'discount' => $dataThaiShared['discount'],
                            'win_credit' => $dataThaiShared['win_credit']
                        ];
                    }
                }
                $dataReserveShared[$val['title']] = [
                    'create_at' => $val['endDate'],
                    'three_top' => (!empty($type) && !empty($type['three_top']) ? $type['three_top'] : ''),
                    'three_tod' => (!empty($type) && !empty($type['three_tod']) ? $type['three_tod'] : ''),
                    'two_top' => (!empty($type) && !empty($type['two_top']) ? $type['two_top'] : ''),
                    'two_under' => (!empty($type) && !empty($type['two_under']) ? $type['two_under'] : ''),
                    'run_top' => (!empty($type) && !empty($type['run_top']) ? $type['run_top'] : ''),
                    'run_under' => (!empty($type) && !empty($type['run_under']) ? $type['run_under'] : ''),
                ];
            }
        }

        return $this->render('game', [
            'searchModel' => $searchModel,
            'data' => $data,
            'date' => $date,
            'blackreds' => $blackreds,
            'configYeekee' => $configYeekee,
            'configBlackred' => $configBlackred,
            'round' => $round,
            'tab' => isset($tab) ? $tab : 'tab1',
            'dataShared' => $dataShared,
            'dataLotteryLaoSets' => $dataLotteryLaoSets,
            'dataLotterys' => $dataLotterys,
            'dataDownJoans' => $dataDownJoans,
            'dataLotteryVietnamSets' => $dataLotteryVietnamSets,
            'dataGsbShared' => $dataGsbShared,
            'dataBaccShared' => $dataBaccShared,
            'dataLaosChampasakShared' => $dataLaosChampasakShared,
            'dataVietnam4DShared' => $dataVietnam4DShared,
            'dataReserveShared' => $dataReserveShared,
        ]);
    }

    /**
     * @return string
     * @throws \yii\db\Exception
     */
    public function actionRefill()
    {

        $dataBank = Bank::find()->where(['status' => 1])->asArray()->all();

        $param = Yii::$app->request->post();
        $searchModel = new YeekeeSearch();
        $date = '';
        if (!empty($param)) {
            $date = $param['YeekeeSearch']['date_at_search'];
        } else {
            $date = date('Y-m-d');
        }
        $data = array();
        if (!empty($dataBank)) {
            foreach ($dataBank as $valBank) {
                $CreditAll = Yii::$app->db->createCommand
                ("
                    SELECT 
                        SUM(pct.amount) as amount
                    FROM post_credit_transection pct
                    INNER JOIN user_has_bank uhb ON uhb.id=pct.user_has_bank_id
                    WHERE 
                        (pct.action_id='" . Constants::action_credit_top_up . "' OR 
                            pct.action_id='" . Constants::action_credit_top_up_admin . "') AND
                        pct.status IN ('" . Constants::status_approve . "','" . Constants::status_cancel . "') AND
                        uhb.bank_id='" . $valBank['id'] . "'
                        AND DATE(pct.create_at) = '$date'
                ")->queryone();


                $CreditAllApprove = Yii::$app->db->createCommand
                ("
                    SELECT 
                        SUM(pct.amount) as amount
                    FROM post_credit_transection pct
                    INNER JOIN user_has_bank uhb ON uhb.id=pct.user_has_bank_id
                    WHERE 
                        (pct.action_id='" . Constants::action_credit_top_up . "' OR 
                            pct.action_id='" . Constants::action_credit_top_up_admin . "') AND
                        pct.status='" . Constants::status_approve . "' AND
                        uhb.bank_id='" . $valBank['id'] . "'
                        AND DATE(pct.create_at) = '$date'
                ")->queryone();

                $WithdrawAll = Yii::$app->db->createCommand
                ("
                    SELECT 
                        SUM(pct.amount) as amount
                    FROM post_credit_transection pct
                    INNER JOIN user_has_bank uhb ON uhb.id=pct.user_has_bank_id
                    WHERE 
                        (pct.action_id='" . Constants::action_credit_withdraw . "' OR 
                            pct.action_id='" . Constants::action_credit_withdraw_admin . "') AND
                            pct.status IN ('" . Constants::status_approve . "','" . Constants::status_cancel . "') AND
                        uhb.bank_id='" . $valBank['id'] . "'
                        AND DATE(pct.create_at) = '$date'
                ")->queryone();


                $WithdrawAllApprove = Yii::$app->db->createCommand
                ("
                    SELECT 
                        SUM(pct.amount) as amount
                    FROM post_credit_transection pct
                    INNER JOIN user_has_bank uhb ON uhb.id=pct.user_has_bank_id
                    WHERE 
                        (pct.action_id='" . Constants::action_credit_withdraw . "' OR 
                            pct.action_id='" . Constants::action_credit_withdraw_admin . "') AND
                        pct.status='" . Constants::status_approve . "' AND
                        uhb.bank_id='" . $valBank['id'] . "'
                        AND DATE(pct.create_at) = '$date'
                ")->queryone();

                $data[] = [
                    'bank_info' => $valBank,
                    'CreditAll' => (!empty($CreditAll) && $CreditAll['amount'] !== null ? $CreditAll['amount'] : 0),
                    'CreditAllApprove' => (!empty($CreditAllApprove) && $CreditAllApprove['amount'] !== null ? $CreditAllApprove['amount'] : 0),
                    'WithdrawAll' => (!empty($WithdrawAll) && $WithdrawAll['amount'] !== null ? $WithdrawAll['amount'] : 0),
                    'WithdrawAllApprove' => (!empty($WithdrawAllApprove) && $WithdrawAllApprove['amount'] !== null ? $WithdrawAllApprove['amount'] : 0),
                ];
            }
        }
        $amountCreditTopUpPromotion = CreditTransection::find()->select('sum(amount) as amount')->where([
            'action_id' => Constants::action_credit_top_up_admin,
            'reason_action_id' => Constants::reason_credit_top_up_promotion
        ])->andWhere(['=', 'DATE(create_at)', $date])->one();
        $amountCreditWithDrawPromotion = CreditTransection::find()->select('sum(amount) as amount')->where([
            'action_id' => Constants::action_credit_withdraw_admin,
            'reason_action_id' => Constants::reason_credit_withdraw_direct
        ])->andWhere(['=', 'DATE(create_at)', $date])->one();

        return $this->render('refill', [
            'searchModel' => $searchModel,
            'data' => $data,
            'date' => $date,
            'amountCreditTopUpPromotion' => $amountCreditTopUpPromotion,
            'amountCreditWithDrawPromotion' => $amountCreditWithDrawPromotion,
        ]);
    }

}
