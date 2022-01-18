<?php

namespace frontend\controllers;

use common\models\Queue;
use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use common\libs\Constants;
use common\models\Yeekee;
use common\models\Running;
use common\models\Games;
use common\models\YeekeePost;
use common\models\PlayType;
use common\models\YeekeeChitDetail;
use common\models\YeekeeChit;
use yii\data\ActiveDataProvider;
use common\models\YeekeeSearch;
use common\models\YeekeeChitSearch;
use common\models\Credit;
use common\models\NumberMemo;
use yii\web\ServerErrorHttpException;

/**
 * Site controller
 */
class YeekeeController extends Controller
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
        ];
    }

    /**
     *
     * {@inheritdoc}
     *
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction'
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null
            ]
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $game = Games::find()->select([
            'title',
            'id'
        ])->where([
            'id' => Constants::YEEKEE
        ])->one();
        $running = Running::find()->where([
            'game_id' => $game->id
        ])->orderBy([
            'running' => SORT_DESC
        ])->one();
        $running = $running->running;
        $time = date('Y-m-d H:i:s');
        

        // can play
        $arrYeekee = Yeekee::find()->where([
            'group' => $running,
        ])->andWhere(['>' , 'finish_at' , $time,])->orderBy('status IN ('.Constants::status_active.') DESC')->addOrderBy([
            'round' => SORT_ASC
        ])->all();

        // not play
        $arrYeekeeNot = Yeekee::find()->where([
            'group' => $running,
        ])->andWhere(['<' , 'finish_at' , $time,])->orderBy('status IN ('.Constants::status_active.') DESC')->addOrderBy([
            'round' => SORT_ASC
        ])->all();

        return $this->render('index', [
            'game' => $game,
            'arrYeekee' => $arrYeekee,
            'arrYeekeeNot' => $arrYeekeeNot
        ]);
    }

    public function actionResult($id)
    {
        $yeekee = YeekeeSearch::find()->where([
            'id' => $id
        ])->one();
        $game = Games::find()->where([
            'id' => Constants::YEEKEE
        ])->one();
        $yeekeePost = YeekeePost::find()->where(['yeekee_id' => $id])->sum('post_num');
        if (empty($yeekee)) {
            return $this->redirect(['yeekee/index']);
        }

        $query = YeekeePost::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ],
        ]);

        $query->select([
            'post_num',
            'post_name',
            'create_at',
            'id'
        ])->where([
            'yeekee_id' => $id
        ]);

        return $this->render('result', [
            'yeekee' => $yeekee,
            'dataProvider' => $dataProvider,
            'yeekeePost' => $yeekeePost,
            'game' => $game,
        ]);
    }

    public function actionProcessing()
    {
        $yeekee_id = \Yii::$app->request->get('yeekee_id');

        $yeekee = Yeekee::find()->where([
            'id' => $yeekee_id
        ])->one();

        if (!empty($yeekee)) {
            if (in_array($yeekee->status, [Constants::status_finish_show_result, Constants::status_cancel])) {
                return $this->redirect(['yeekee/result', 'yeekee_id' => $yeekee_id]);
            }
        }

        return $this->render('processing', [
            'yeekee_id' => $yeekee_id
        ]);
    }

    public function actionCheckProcesed()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $yeekee_id = \Yii::$app->request->post('yeekee_id');

        $yeekee = Yeekee::find()->where([
            'id' => $yeekee_id
        ])->one();

        if (!empty($yeekee)) {
            if (in_array($yeekee->status, [Constants::status_finish_show_result, Constants::status_cancel])) {
                return true;
            }
        }
        return false;
    }

    public function actionPlay($id)
    {
        $this->layout = 'buy_yeekee';
//        $yeekeeGame = Yeekee::find()->andWhere('NOW() >= start_at AND finish_at >= NOW()')->andWhere(['id' => $id, 'status' => 1])->one();
//        if (!$yeekeeGame) {
//            return $this->redirect(['result', 'id' => $id]);
//        }
        $yeekeeGame = Yeekee::find()->where([
            'id' => $id
        ])->one();
        if (empty($yeekeeGame)) {
            return $this->redirect(['yeekee/index']);
        }
        if (in_array($yeekeeGame->status, [Constants::status_processing_2])) {
            return $this->redirect(['yeekee/result', 'id' => $yeekeeGame->id]);
        }
        $finishTime = strtotime("+1 minutes +60 seconds", strtotime($yeekeeGame->finish_at));
        if (time() >= $finishTime) {
            return $this->redirect(['yeekee/result', 'id' => $yeekeeGame->id]);
        }

        if ($yeekeeGame->status === Constants::status_finish_show_result) {
            return $this->redirect(['yeekee/result', 'id' => $yeekeeGame->id]);
        }
        $playTypeObj = PlayType::find()->where(['game_id' => Constants::YEEKEE])->orderBy('sort ASC')->all();
        foreach ($playTypeObj as $key => $playType) {
            $jackpotPerUnit = $playType->jackpot_per_unit;
            $playTypeCode = PlayType::instance()->getConvertPlayTypeCode($playType->code);
            $playTypes[$playTypeCode]['title'] = $playType->title;
            $playTypes[$playTypeCode]['code'] = $playType->code;
            $playTypes[$playTypeCode]['jackpot_per_unit'] = $jackpotPerUnit;
            $playTypes[$playTypeCode]['minimum_play'] = $playType->minimum_play;
            $playTypes[$playTypeCode]['maximum_play'] = $playType->maximum_play;
            $playTypes[$playTypeCode]['discount'] = 0;
            $betMaxMin[$key]['bet_option'] = $playTypeCode;
            $betMaxMin[$key]['bet_min'] = $playType->minimum_play;
            $betMaxMin[$key]['bet_max'] = $playType->maximum_play;
        };
        $betListDetail = json_encode($this->betListDetail($yeekeeGame, $playTypes, $betMaxMin), true);

        //ดึงเลขชุด
        $numberMemoList = NumberMemo::find()->where(['user_id' => \Yii::$app->user->id, 'gameId' => Constants::YEEKEE])->all();
        $poyList = json_encode(['bet_id' => $yeekeeGame->id, 'poy_list' => []]);
        $game = Games::find()->where(['id' => Constants::YEEKEE])->one();
        return $this->render('play', [
            'yeekeeGame' => $yeekeeGame,
            'numberMemoList' => $numberMemoList,
            'poyList' => $poyList,
            'playTypes' => $playTypes,
            'betListDetail' => $betListDetail,
            'game' => $game,
        ]);
    }

    //r=yeekee/numberyeekeepost กดแทงหวย
    public function actionNumberyeekeepost()
    {
        $data = Yii::$app->request->post();
        $user_id = \Yii::$app->user->identity->id;
        $yeekee_id = isset($data['yeekee_id']) ? $data['yeekee_id'] : '';

        $yeekee = YeekeeSearch::find()->where(['id' => $yeekee_id])->one();
        if (!$yeekee) {
            throw new ServerErrorHttpException('Not Found Yeekee');
        }
        $checkQueueProcess = Queue::find()->count();
        if ($checkQueueProcess) {
            $canWithdraw = ['reason' => 'ระบบกำลังประมวลผลอยู่ กรุณาแทงใหม่อีกครั้ง ภายใน 2 นาที'];
            return $this->render('/info/index', ['canWithdraw' => $canWithdraw]);
        }
        if (!empty($data)) {
            //check credit balance
            $playData = json_decode($data['play_data']);
            $canPay = YeekeeChitSearch::getCreditCanPay($playData, $user_id);
            $startTime = strtotime($yeekee->start_at);
            $finish_time = strtotime(date("Y-m-d H:i:s", strtotime($yeekee->finish_at)));
            if (time() > $finish_time || $startTime > time() || $yeekee->status <> Constants::status_active) {
                return $this->redirect(['yeekee/playresult', 'yeekee_id' => $yeekee_id, 'can' => $canPay, 'pass' => false, 'reason' => 1]);
            }
            $isYeekeeChitDuplicate = YeekeeChit::find()->where([
                'yeekee_id' => $yeekee_id,
                'user_id' => $user_id
            ])->andWhere('DATE_FORMAT(create_at, "%Y-%m-%d %H:%i:%s") = date_format(now(),"%Y-%m-%d %H:%i:%s")')->count();
            if ($isYeekeeChitDuplicate) {
                $canWithdraw = ['reason' => 'ไม่สามารถทำรายการได้่เนื่องจากคุณพึ่งซื้อไปคุณสามารถมาซื้อได้อีกครั้งหลังครบ 1 นาที'];
                return $this->render('/info/index', ['canWithdraw' => $canWithdraw]);
            }
            if (!$canPay) {
                $canWithdraw = [
                    'credit' => true,
                    'reason' => 'ยอดเงินเครดิตของท่านไม่เพียงพอ'
                ];
                //return $this->redirect(['yeekee/playresult','yeekee_id'=>$yeekee_id,'can'=>$canPay,'pass'=>false,'reason'=>'2']);
                return $this->render('/info/index', ['canWithdraw' => $canWithdraw]);
            }

            $transaction = \Yii::$app->db->beginTransaction();
            $countYeekeeChitDetailTwoTop = 0;
            $countYeekeeChitDetailTwoUnder = 0;
            try {
                $queue = new Queue();
                $queue->userId = $user_id;
                $queue->gameId = Constants::YEEKEE;
                if (!$queue->save()) {
                    throw new ServerErrorHttpException('Can not save queue');
                }
                $yeekeeChit = new YeekeeChit();
                $yeekeeChit->user_id = $user_id;
                $yeekeeChit->yeekee_id = $data['yeekee_id'];
                $yeekeeChit->create_at = date('Y-m-d H:i:s', time());
                $yeekeeChit->create_by = $user_id;
                $yeekeeChit->status = Constants::status_playing;
                if (!$yeekeeChit->save()) {
                    return $this->render('/site/error', [
                        'exception' => 'เกิดข้อผิดพลาดกรุณาลองใหม่่อีกครั้ง',
                    ]);
                }


                $playDatas = json_decode($data['play_data'], true);
                $numberTwoTopIsExists = false;
                $numberTwoUnderIsExists = false;
                $totalNumberTwoTops = 0;
                $totalNumberTwoUnders = 0;
                $playData = json_decode($data['play_data']);
                foreach ($playData as $type => $data2) {
                    $playType = PlayType::find()->select(['minimum_play', 'maximum_play'])->where([
                        'code' => $type,
                        'game_id' => Constants::YEEKEE
                    ])->one();
                    if (!$playType) {
                        throw new ServerErrorHttpException('Can Not Found Play Type');
                    }
                    $maximumPlay = $playType->maximum_play;
                    $minimumPlay = $playType->minimum_play;
                    $numSet = json_decode($data2);
                    foreach ($numSet as $number => $amount) {
                        if ($amount > $maximumPlay) {
                            $amount = $maximumPlay;
                        } elseif ($amount < $minimumPlay) {
                            $amount = $minimumPlay;
                        }
                        $model = new YeekeeChitDetail();
                        $model->yeekee_chit_id = $yeekeeChit->id;
                        $model->number = $number;
                        $model->play_type_code = $type;
                        $model->amount = $amount;
                        $model->create_at = date('Y-m-d H:i:s');
                        $model->create_by = $user_id;

                        if (!$model->save()) {
                            return $this->render('/site/error', [
                                'exception' => 'เกิดข้อผิดพลาดกรุณาลองใหม่่อีกครั้ง',
                            ]);
                        }
                        $yeekeeChit->total_amount += $model->amount;
                    }
                }
                $yeekeeChit->update_at = date('Y-m-d H:i:s', time());
                if (!$yeekeeChit->save()) {
                    return $this->render('/site/error', [
                        'exception' => 'เกิดข้อผิดพลาดกรุณาลองใหม่่อีกครั้ง',
                    ]);
                }
                //create transection
                $reason = 'แทงพนัน จับยี่กี รอบที่ ' . $yeekee->round . ' / ' . date('d/m/Y', strtotime($yeekee->date_at)) . ' #' . $yeekeeChit->getOrder();
                Credit::creditWalk(Constants::action_credit_withdraw, $user_id, $user_id, Constants::reason_credit_bet_play, $yeekeeChit->total_amount, $reason);
                if (!Queue::deleteAll(['id' => $queue->id])) {
                    throw new ServerErrorHttpException('Can not delete queue');
                }
                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            }
        }
        $this->redirect(['yeekee/playresult', 'yeekee_id' => $yeekee_id, 'pass' => true, 'reason' => 3]);
        //\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    }

    public function actionPlayresult()
    {
        $yeekee_id = \Yii::$app->request->get('yeekee_id');
        $pass = \Yii::$app->request->get('pass');
        $reason = \Yii::$app->request->get('reason');
        $result = '';
        $text = '';
        if ($reason == 1) {
            $text = 'หมดเวลาแทง';
            $result = 'ทำรายการไม่สำเร็จ';
        } else if ($reason == 2) {
            $result = 'ชี้แจง';
            $text = 'เครดิตของท่านไม่พอ กรุณาเติมก่อนครับ';
        } else if ($reason == 3) {
            $result = 'ทำรายการสำเร็จ';
        }
        return $this->render('play-result', ['yeekee_id' => $yeekee_id, 'pass' => $pass, 'text' => $text, 'reason' => $reason, 'result' => $result]);
    }

    /**
     * get yeekee post
     *
     * @return arrYeekeePost, sum_result
     */
    public function actionGetyeekeepost()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();

            $yeekee_id = $data ['yeekee_id'];
            $yeekeePost = $this->getYeekeePostTranfromData($yeekee_id);
            $sumResult = $this->getSumYeekee($yeekee_id);

            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [
                'data' => $yeekeePost,
                'sum_result' => $sumResult
            ];
        }
    }

    public function actionReceiveyeekeepost()
    {
        $user_id = Yii::$app->user->identity;
        $reason = '';
        $now = time();

        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $yeekee_id = $data ['yeekee_id'];

            $yeekee = YeekeeSearch::find()->select(['id', 'status', 'finish_at'])->where(['id' => $yeekee_id])->one();
            if (empty($yeekee)) {
                return false;
            }

            //เพราะยิงต่อได้ 2 นาที
            $finishTime = strtotime("+1 minutes +60 seconds", strtotime($yeekee->finish_at));
            if ($now >= $finishTime) {
                return false;
            }

            if (!in_array($yeekee->status, [Constants::status_active, Constants::status_processing])) {
                return false;
            }

            // check freq post
            $lastPost = YeekeePost::find()->select([
                'create_at'
            ])->where([
                'yeekee_id' => $yeekee_id,
                'create_by' => $user_id
            ])->orderBy([
                'create_at' => SORT_DESC
            ])->one();

            // check is post 5 in sec
            $can_post = true;
            if (!empty ($lastPost)) {
                if ((time() - strtotime($lastPost->create_at)) <= Constants::post_frequency_per_sec) {
                    $can_post = false;
                    $reason = "frequency lot.";
                }
            }
            // user can't save frequency
            $result = false;
            $model = new YeekeePost ();
            if ($can_post) {
                $model = new YeekeePost ();
                $model->yeekee_id = $data ['yeekee_id'];
                $model->post_num = $data ['post_num'];
                $model->create_at = date('Y-m-d H:i:s');
                $model->create_by = $user_id->id;
                $model->username = $user_id->username;
                $model->post_name = $user_id->username;
                $model->is_bot = 0;
                $model->order = time();
                $result = $model->save();
            }

            $yeekeePost = $this->getYeekeePostTranfromData($yeekee_id);
            $sumResult = $this->getSumYeekee($yeekee_id);

            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [
                'data' => $yeekeePost,
                'sum_result' => $sumResult,
                'result' => $result,
                'errors' => $result ? [] : $model->getErrors(),
                'reason' => $reason
            ];
        }
    }

    // get yeekee post 20
    function getYeekeePostTranfromData($yeekee_id = null, $limit = 20)
    {

        if (empty ($yeekee_id))
            return [];

        $query = YeekeePost::find()->select([
            'post_num',
            'post_name',
            'create_at'
        ])->where([
            'yeekee_id' => $yeekee_id
        ]);
        if ($limit > 0) {
            $query->limit($limit);
        }
        $yeekeePost = $query->orderBy([
            'id' => SORT_DESC
        ])->all();
        $dataResult = [];
        foreach ($yeekeePost as $i => $post) {
            $running = $i + 1;
            $class = '';
            if ($running == 1) {
                $class = 'table-info';
            } else if ($running == 16) {
                $class = 'table-danger';
            }

            $post_name = '';
            if (in_array($i, [0, 15])) {
                $post_name = $post->post_name;
            } else {
                $post_name = substr_replace($post->post_name, '***', 2, 6);
            }
            $dataResult [] = [
                'class' => $class,
                'running' => $running,
                'post_num' => str_pad($post->post_num, 5, '0', STR_PAD_LEFT),
                'post_name' => $post_name,
                'send_at' => date('d/m/Y H:i:s', strtotime($post->create_at))
            ];
        }
        return $dataResult;
    }

    // get sum yeekee
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

    public function getYeeKeeChitDetailsTwoTop($id)
    {
        $yeeKeeChitDetails = YeekeeChitDetail::find()->where([
            'yeekee_id' => $id,
            'play_type_code' => 'two_top',
            YeekeeChitDetail::tableName() . '.create_by' => Yii::$app->user->id
        ])->innerJoin(YeekeeChit::tableName())->groupBy('number')->asArray()->all();
        return $yeeKeeChitDetails;
    }

    public function getYeeKeeChitDetailsTwoUnder($id)
    {
        $yeeKeeChitDetails = YeekeeChitDetail::find()->where([
            'yeekee_id' => $id,
            'play_type_code' => 'two_under',
            YeekeeChitDetail::tableName() . '.create_by' => Yii::$app->user->id
        ])->innerJoin(YeekeeChit::tableName())->groupBy('number')->asArray()->all();
        return $yeeKeeChitDetails;
    }

    protected function BetListDetail($yeekeeGame, $playTypes, $betMinmMax)
    {
        $betListDetail = [
            'bet_id' => $yeekeeGame->id,
            'bet_type' => 'STOCK',
            'bet_name' => 'จับยี่กี รอบที่ '.$yeekeeGame->round,
            'bet_round' => '',
            'open_dt' => $yeekeeGame->start_at,
            'close_dt' => $yeekeeGame->finish_at,
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
