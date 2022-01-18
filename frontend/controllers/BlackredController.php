<?php

namespace frontend\controllers;

use common\models\BotBlackRedFixResult;
use common\models\Credit;
use common\models\CreditSearch;
use common\models\PlayType;
use common\models\PostCreditTransectionSearch;
use common\models\Queue;
use DateTime;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\filters\AccessControl;
use common\libs\Constants;
use common\models\Running;
use common\models\Games;
use common\models\BlackRed;
use yii\helpers\ArrayHelper;
use common\models\BlackredChit;
use yii\web\ServerErrorHttpException;


/**
 * Site controller
 */
class BlackredController extends Controller
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
                'class' => VerbFilter::className(),
                'actions' => [
                    'buy-blackred' => ['POST'],
                ],
            ],
        ];
    }


    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {

        $game = Games::find()->select(['title', 'id', 'rule'])->where(['id' => Constants::BLACKRED])->one();
        $running = Running::find()->where([
            'game_id' => $game->id
        ])->orderBy([
            'running' => SORT_DESC
        ])->one();
        $running = $running->running;


        //get round blackred
        $arrBlackredRound = [];
        $query = BlackRed::find()->where(['group' => $running])->andWhere('`finish_at` >= NOW()');
        foreach ($query->each() as $model) {
            $arrBlackredRound[$model->id] = 'ดำแดงรอบที่ ' . $model->round . ' : ' . date('d/m/Y H:i', strtotime($model->finish_at));
        }

        //get top 10 result
        $query = BlackRed::find()->where(['group' => $running])
            ->andWhere('result IS NOT NULL')
            ->orderBy(['id' => SORT_DESC])
            ->limit(20);
        $arrBlackredTopTen = $query->all();

        $blackred_id = \Yii::$app->request->post('select_blackred_id');

        if (!empty($blackred_id)) {
            $blackred = BlackRed::find()->where(['id' => $blackred_id])->one();
        } else {
            $blackred = BlackRed::find()->where(['>', 'finish_at', date('Y-m-d H:i:s')])->orderBy(['finish_at' => SORT_ASC])->one();
            if (empty($blackred)) {
                $blackred = BlackRed::find()->where(['group' => $running])->orderBy(['round' => SORT_DESC])->one();
            }
        }

        /*
        if(empty($blackred)){
            return $this->render ( 'empty_game', [
                'game' => $game
            ] );
        }
        */
        $blackred = new BlackRed();
        //$blackred->finish_at = date('Y-m-d H:i:s',strtotime('1 hour'));//'2018-06-20 08:35:00';
        $blackred->finish_at = date('Y-m-d H:i:s', strtotime('2 minutes'));
        //$blackred->status = Constants::status_finish_show_result;
        //$blackred->result = Constants::blackred_black;
        $blackred->status = Constants::status_active;

        $groupPlayType = [];
        $modelGroup = PlayType::find()->select([
            'group_id'
        ])->where([
            'game_id' => Constants::BLACKRED
        ])->groupBy([
            'group_id'
        ])->all();
        $allPlayType = [];
        foreach ($modelGroup as $group) {
            $playType = [];
            $modelsPlayType = PlayType::find()->where([
                'game_id' => Constants::BLACKRED,
                'group_id' => $group->group_id
            ])->all();
            foreach ($modelsPlayType as $model) {
                $playType [] = [
                    'title' => $model->title,
                    'code' => $model->code,
                    'jackpot' => $model->jackpot_per_unit,
                    'min' => $model->minimum_play,
                    'max' => $model->maximum_play
                ];
                $allPlayType[] = [
                    'title' => $model->title,
                    'code' => $model->code,
                    'jackpot' => $model->jackpot_per_unit,
                    'min' => $model->minimum_play,
                    'max' => $model->maximum_play
                ];
            }

            $groupPlayType [] = [
                'group_id' => $group->group->id,
                'group_title' => $group->group->title,
                'number_length' => $group->group->number_length,
                'number_range' => $group->group->number_range,
                'play_type_list' => $playType
            ];
        }

        return $this->render('index2', [
            'game' => $game,
            'arrBlackredRound' => $arrBlackredRound,
            'blackred' => $blackred,
            'arrBlackredTopTen' => $arrBlackredTopTen,
            'playType' => $playType,
            'allPlayType' => $allPlayType,
            'groupPlayType' => $groupPlayType
        ]);
    }

    public function actionGetBlackredResult()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $blackred_id = \Yii::$app->request->post('blackred_id');
        $blackred = BlackRed::find()->where(['id' => $blackred_id])->one();

        if (empty($blackred)) {
            $blackred = new BlackRed();
            $blackred->finish_at = date('Y-m-d H:i:s');
        }
        $blackred->finish_at = '2018-06-20 03:05:00';
        $isBlackRedChit = BlackredChit::find()->where(['blackred_id' => $blackred_id])->count();
        if (!$isBlackRedChit) {
            $botBlackRedFixResult = BotBlackRedFixResult::find()->where(['round' => $blackred->round, 'date' => $blackred->date_at])->one();
            if ($botBlackRedFixResult) {
                if ($botBlackRedFixResult->play_type_code === Constants::BLACK) {
                    $answer = [1, 3, 5, 7];
                } else {
                    $answer = [2, 4, 6, 8];
                }
            } else {
                $answer = [2, 4, 6, 8];
            }
        } else {
            $totalBlack = BlackredChit::find()->select('total_amount')->where(['blackred_id' => $blackred_id, 'play_type_code' => Constants::blackred_black])->sum('total_amount');
            $totalRed = BlackredChit::find()->select('total_amount')->where(['blackred_id' => $blackred_id, 'play_type_code' => Constants::blackred_red])->sum('total_amount');
            $playTypeCodeBlack = Constants::getPlayTypeCode(['black']);
            $playTypeCodeRed = Constants::getPlayTypeCode(['red']);
            if ($playTypeCodeBlack) {
                $totalBlack = $totalBlack * $playTypeCodeBlack->jackpot_per_unit;
            }
            if ($playTypeCodeRed) {
                $totalRed = $totalRed * $playTypeCodeRed->jackpot_per_unit;
            }
            if ($totalRed > $totalBlack) {
                $answer = [1, 3, 5, 7];
            } else {
                $answer = [2, 4, 6, 8];
            }
        }
        $blackred->result = $answer[array_rand($answer, 1)];

        $data = [
            'state' => 'continue',
            'result' => ''
        ];
        if (time() >= strtotime($blackred->finish_at) && !empty($blackred->result)) {
            $data = [
                'state' => 'finish',
                'result' => $blackred->result
            ];
        }
        return $data;
    }

    public function actionGetSelectBlackred()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $blackred_id = \Yii::$app->request->post('blackred_id');
        $blackred = BlackRed::find()->where(['id' => $blackred_id])->one();
        $data = [
            'finish_at' => strtotime($blackred->finish_at)
        ];
        return $data;
    }

    public function actionBuyBlackred()
    {
        $playBlack = Yii::$app->request->post('playBlack');
        $playRed = Yii::$app->request->post('playRed');
        $blackRedId = Yii::$app->request->post('selectBlackRed');
        $userId = Yii::$app->user->id;
        $total = $playBlack + $playRed;
        $blackRed = BlackRed::find()->where(['id' => $blackRedId])->one();
        $finish_time = strtotime($blackRed->finish_at);
        $time = new DateTime();
        $time->modify('+30 second');
        $time = $time->format('U');
        if ($time > $finish_time) {
            return $this->render('play-result', [
                'pass' => false,
                'reason' => 1,
                'text' => 'หมดเวลาแทง',
                'result' => 'ทำรายการไม่สำเร็จ'
            ]);
        }
        $blackRedChitObj = BlackredChit::find()->where(['blackred_id' => $blackRedId, 'user_id' => $userId, 'status' => Constants::status_playing])->all();
        if ($blackRedChitObj) {
            return $this->render('play-result', [
                'pass' => false,
                'reason' => 4,
                'text' => 'คุณแทงโพยไปแล้วในรอบที่ '.$blackRed->round.' กรุณายกเลิกโพยก่อนหน้า',
                'result' => 'ทำรายการไม่สำเร็จ'
            ]);
        }
        $canPay = $this->getCreditCanPay($total, $userId);
        if (!$canPay) {
            $canWithdraw = [
                'credit' => true,
                'reason' => 'ยอดเงินเครดิตของท่านไม่เพียงพอ'
            ];
            return $this->render('/info/index', ['canWithdraw' => $canWithdraw]);
        }
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $queue = new Queue();
            $queue->userId = $userId;
            $queue->gameId = Constants::BLACKRED;
            if (!$queue->save()) {
                throw new ServerErrorHttpException('Can not save queue');
            }
            if ($playBlack > 0) {
                $playType = PlayType::find()->where(['code' => 'black'])->one();
                $blackRedChit = new  BlackredChit();
                $blackRedChit->setScenario('create');
                $blackRedChit->blackred_id = $blackRedId;
                $blackRedChit->total_amount = Yii::$app->request->post('playBlack');
                $blackRedChit->user_id = $userId;
                $blackRedChit->create_at = date("Y-m-d H:i:s");
                $blackRedChit->create_by = $userId;
                $blackRedChit->play_type_code = Constants::BLACK;
                $blackRedChit->status = Constants::status_playing;
                $blackRedChit->win_credit = $blackRedChit->total_amount * $playType->jackpot_per_unit;
                if (!$blackRedChit->save()) {
                    return $this->render('/site/error', [
                        'exception' => 'เกิดข้อผิดพลาดกรุณาลองใหม่่อีกครั้ง',
                    ]);
                }
            }
            if ($playRed > 0) {
                $playType = PlayType::find()->where(['code' => 'red'])->one();
                $blackRedChit = new  BlackredChit();
                $blackRedChit->setScenario('create');
                $blackRedChit->blackred_id = $blackRedId;
                $blackRedChit->total_amount = Yii::$app->request->post('playRed');
                $blackRedChit->user_id = $userId;
                $blackRedChit->create_at = date("Y-m-d H:i:s");
                $blackRedChit->create_by = $userId;
                $blackRedChit->play_type_code = Constants::RED;
                $blackRedChit->win_credit = $blackRedChit->total_amount * $playType->jackpot_per_unit;
                $blackRedChit->status = Constants::status_playing;
                if (!$blackRedChit->save()) {
                    return $this->render('/site/error', [
                        'exception' => 'เกิดข้อผิดพลาดกรุณาลองใหม่่อีกครั้ง',
                    ]);
                }
            }
            $reason = 'แทงดำแดง รอบที่ ' . $blackRed->round . ' / ' . date('d/m/Y', strtotime($blackRed->date_at)) . ' #' . $blackRed->getOrderId();
            $credit = Credit::creditWalk(Constants::action_credit_withdraw, $userId, $userId, Constants::reason_credit_bet_play, $blackRedChit->total_amount, $reason);
            if (!Queue::deleteAll(['id' => $queue->id])) {
                throw new ServerErrorHttpException('Can not delete queue');
            }
            $transaction->commit();
        }catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
        return $this->render('play-result', [
            'result' => 'ทำรายการสำเร็จ',
            'pass' => true,
            'reason' => 3
        ]);
    }

    public function actionPlayresult()
    {
        $blackred_id = \Yii::$app->request->get('blackred_id');
        $can = \Yii::$app->request->get('can');

        return $this->render('play-result', ['blackred_id' => $blackred_id, 'can' => $can]);
    }

    public static function getCreditCanPay($payAmount, $user_id, $reason = 0)
    {
        $creditBalance = CreditSearch::find()->select(['balance'])->where(['user_id'=>$user_id])->one();
        if(empty($creditBalance)){
            if($reason == 1){
                return ['result'=>false,'reason'=>'ไม่มีข้อมูลเครดิต '];
            }
            return false;
        }
        //หายอดที่จะขอถอนที่ยังไม่อนุมัติ
        $sumWaittingPostWithDraw = PostCreditTransectionSearch::find()
            ->where(['poster_id'=>$user_id,'action_id'=>Constants::action_credit_withdraw,'status'=>Constants::status_waitting])
            ->sum('amount');
        $realCredit = ($creditBalance->balance - $sumWaittingPostWithDraw);
        //จ่ายมากกว่ามี
        if(($payAmount>$realCredit)){
            if($reason == 1){
                return ['result'=>false,'reason'=>'จำนวนเครดิตของคุณไม่เพียงพอ หรืออาจมีการแจ้งถอนที่รออนุมัติอยู่'];
            }
            return false;
        }else {
            if($reason == 1){
                return ['result'=>true,'reason'=>'success '];
            }
            return true;
        }
    }
}
