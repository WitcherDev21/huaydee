<?php
namespace backend\controllers;

use common\libs\Constants;
use common\models\Alian;
use common\models\BlackRed;
use common\models\BlackredChit;
use common\models\BotBlackRedFixResult;
use common\models\Commission;
use common\models\CommissionBlackred;
use common\models\Credit;
use common\models\FixNumberYeekee;
use common\models\Games;
use common\models\Queue;
use common\models\SettingBenefit;
use common\models\SettingCommissionCredit;
use common\models\SettingGameBenefit;
use common\models\YeekeeChitDetailSearch;
use common\models\YeekeeChitSearch;
use common\models\YeekeePost;
use common\models\YeekeeSearch;
use Yii;
use yii\web\Controller;
use yii\web\ServerErrorHttpException;

/**
 * Created by PhpStorm.
 * User: topte
 * Date: 9/15/2018
 * Time: 3:16 PM
 */

class AnswerGameController extends Controller
{
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionYeekeeAnswer($authKey)
    {
        //echo "<pre>",var_dump($result),"</pre>";exit;
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $game = Games::find()->where(['id' => Constants::YEEKEE, 'status' => 1, 'gameAuthKey' => $authKey])->one();
        if (!$game) {
            return [
                'message' => 'game not found !',
            ];
        }
        //$admin_id = \Yii::$app->user->identity->id;
        $admin_id = Constants::user_system_id;// fix
        $now = time();
        $result = true;
        $watchYeekee = YeekeeSearch::find()->where(['in', 'status', [Constants::status_active, Constants::status_processing, Constants::status_processing_2]])
             ->orderBy(['finish_at' => SORT_ASC])->one();
        // $watchYeekee = YeekeeSearch::find()->where(['in', 'status', [Constants::status_active, Constants::status_processing]])
        //      ->orderBy(['finish_at' => SORT_ASC])->one();    
        if (empty($watchYeekee)) {
            return [
                'now' => date('Y-m-d H:i:s'),
                'yeekee_id' => 'null',
                'yeekee_finish_at' => 'null',
                'status' => 'null',
                'reason' => 'yeekee is empty'
            ];
        }
        //หมดเวลารับแทง ให้ปิดรอบ ปรับสถานะ  ตรงนี้อาจจะทำให้ปิดรับแทงก่อน และปล่อยให้ยิงเลขได้อยู่
        //$now =  strtotime($watchYeekee->finish_at); //test  change status step 1
        //$now = strtotime("+2 minutes",strtotime($watchYeekee->finish_at)); //test step 2 ปิดรอบ
        //$now = strtotime("+18 minutes",$now); //test step 2 ปิดรอบ
        if ($now >= strtotime($watchYeekee->finish_at)) {
            //$transaction = \Yii::$app->db->beginTransaction(); //for test
            $finishTime = strtotime("+2 minutes", strtotime($watchYeekee->finish_at));
            $watchYeekee->status = Constants::status_processing;
            $watchYeekee->update_at = date('Y-m-d H:i:s');
            $watchYeekee->update_by = $admin_id;
            $result = $watchYeekee->save();            
            if (!$result) {
                Yii::$app->runAction('system/cancel-yeekee', ['id' => $watchYeekee->id]);
            }
            if ($now < $finishTime) {
                return [
                    'now' => date('Y-m-d H:i:s'),
                    'yeekee_id' => $watchYeekee->id,
                    'yeekee_finish_at' => $watchYeekee->finish_at,
                    'status' => Constants::$status[$watchYeekee->status],
                    'reason' => '2 min. more can yeekee post. |' . date('d/m/Y H:i:s', $finishTime)
                ];
            }
            // more step check
            $finishTime = strtotime("+18 seconds", $finishTime);
            $watchYeekee->status = Constants::status_processing_2;
            $watchYeekee->update_at = date('Y-m-d H:i:s');
            $watchYeekee->update_by = $admin_id;
            $result = $watchYeekee->save();
            if (!$result) {
                Yii::$app->runAction('system/cancel-yeekee', ['id' => $watchYeekee->id]);
            }
            $yeekeeChitAll = YeekeeChitSearch::find()->where(['yeekee_id' => $watchYeekee->id])->all();   
            //echo "<pre>",var_dump($watchYeekee->id),"</pre>";exit();    
            foreach ($yeekeeChitAll as $yeekeeChit) {
                $queue = new Queue();
                $queue->gameId = Constants::YEEKEE;
                $queue->userId = $yeekeeChit->user_id;
                $queue->status = Constants::status_active;
                $queue->save(true);                
            }
            
            if ($now < $finishTime) {
                return [
                    'now' => date('Y-m-d H:i:s'),
                    'yeekee_id' => $watchYeekee->id,
                    'yeekee_finish_at' => $watchYeekee->finish_at,
                    'status' => Constants::$status[$watchYeekee->status],
                    'reason' => '1 min. system proseccing. |' . date('d/m/Y H:i:s', $finishTime)
                ];
            }

            //หา การยิงในรอบนี้
            $yeekeePost = YeekeePost::find()->where(['yeekee_id' => $watchYeekee->id])
                ->orderBy(['id' => SORT_DESC])
                ->all();

                if (count($yeekeePost) <= 20) {
                    Yii::$app->runAction('api-yeekee/gen-yeekee-post', ['authKey' =>'yeekee_rumruay', 'post_amount' =>3]);
                    return ['message' => 'test'];
                }  

            $sumYeekeePost = 0;
            foreach ($yeekeePost as $post) {
                $sumYeekeePost += (1 * $post->post_num);
            }  
            //หารายการโพยทั้งหมด ในรอบนี้
            $yeekeeChitAll = YeekeeChitSearch::find()->where(['yeekee_id' => $watchYeekee->id])->all();            
            $lenghtSumYeekeePost = strlen((string)$sumYeekeePost);           
            if (count($yeekeePost) < Constants::minimum_post || ($sumYeekeePost <= 0) && $watchYeekee->isOpenBot === 1) {
                //คืนเครดิต
                $transaction = \Yii::$app->db->beginTransaction();
                foreach ($yeekeeChitAll as $yeekeeChit) {
                    $remark = Constants::$reason_credit[Constants::reason_credit_cancel] . ' จับยี่กีรอบที่ ' . $watchYeekee->round . '/' . date('d/m/Y', strtotime($watchYeekee->date_at)) . ' #' . $yeekeeChit->getOrderId();
                    if (Credit::creditWalk(Constants::action_credit_top_up, $yeekeeChit->user_id, $admin_id, Constants::reason_credit_cancel, $yeekeeChit->total_amount, $remark)) {
                        $yeekeeChit->status = Constants::status_cancel;
                        $result = $yeekeeChit->save();
                    } else {
                        $result = false;
                    }

                    if (!$result) {
                        $error = true;
                        var_dump($yeekeeChit->getErrors());
                        exit;
                    }
                }
                //ปรับสถานะ ยกเลิก
                $watchYeekee->status = Constants::status_cancel;
                $watchYeekee->update_at = date('Y-m-d H:i:s');
                $watchYeekee->update_by = $admin_id;
                if (!$watchYeekee->save()) {
                    $result = false;
                }
                Queue::updateAll(['status' => Constants::status_inactive], ['gameId' => Constants::YEEKEE]);
                if ($result) {
                    $transaction->commit();
                } else {
                    Yii::$app->runAction('system/cancel-yeekee', ['id' => $watchYeekee->id]);
                    $transaction->rollBack();
                }
                return [
                    'now' => date('Y-m-d H:i:s'),
                    'yeekee_id' => $watchYeekee->id,
                    'yeekee_finish_at' => $watchYeekee->finish_at,
                    'status' => Constants::$status[$watchYeekee->status],
                    'reason' => 'post is minimum',
                    'error' => $watchYeekee->getErrors()
                ];
            }
            //ออกผล
            $transaction = \Yii::$app->db->beginTransaction();
            //get config benifit
            $setBenefit = SettingBenefit::findOne(['game_id' => Constants::YEEKEE, 'status' => Constants::status_active]);
            $is_setBenefit = true;
            if (empty($setBenefit)) {
                $setBenefit = new SettingBenefit();
                //$setBenefit->value = 10;
                //$setBenefit->value_type = 'percent';
                $is_setBenefit = true; //ยังไงๆ ก็เอากำไรไว้ก่อน
            }
            $estimate_income_config = $setBenefit->value;

            //user 1st
            $user_1 = $yeekeePost[0];

            //user 16th
            $user_16 = $yeekeePost[15];
            
            $resultYeekee = $sumYeekeePost - $user_16->post_num;            
            ///------------------start bot
            $outlay = 0;
            $income = 0;
            $commission_user_agent_play = [];
            //add queue process
            foreach ($yeekeeChitAll as $yeekeeChit) {
                $detailInChit = $yeekeeChit->yeekeeChitDetails;
                foreach ($detailInChit as $detail) {
                    $detailSearch = YeekeeChitDetailSearch::findOne(['id' => $detail->id]);
                    $income += $detailSearch->amount;
                    $pay = YeekeeChitDetailSearch::getWinCreditStatic($resultYeekee, $detailSearch->play_type_code, $detailSearch->number, $detailSearch->amount);
                    $outlay += $pay;
                }

                $amount = $yeekeeChit->total_amount;
                if (!empty($yeekeeChit->user->agent)) {
                    if (!isset($commission_user_agent_play[$yeekeeChit->user->agent])) {
                        $commission_user_agent_play[$yeekeeChit->user->agent] = 0;
                    }
                    $commission_user_agent_play[$yeekeeChit->user->agent] += $yeekeeChit->total_amount;
                }
            }

            //กำไรที่เว็บจะได้
            $estimate_income_credit = round($income * ($estimate_income_config / 100), 2);

            $new_postnum = 0;
            $new_sumYeekeePost = 0;
            $new_resultYeekee = 0;
            $new_user_16 = [];
            $is_bot = 0;
            $cal_round = 0;
            $new_yeekeePost = [];
            $befor_result = [];
            $after_result = [];
            $good_num = 0;
            $bot_count = 999;
            $fair_amount = $income - $estimate_income_credit;
            //check fix number
            $fixNumberYeekees = FixNumberYeekee::find()->where(['yeekeeId' => $watchYeekee->id])->one();
            

            if ($fixNumberYeekees) {
                $yeekeePost = YeekeePost::find()->where(['yeekee_id' => $watchYeekee->id])
                    ->orderBy(['id' => SORT_DESC])
                    ->all();

                $sumYeekeePost = 0;
                foreach ($yeekeePost as $post) {
                    $sumYeekeePost += (1 * $post->post_num);
                }
                $a = $sumYeekeePost;
                $b = $yeekeePost[14]->post_num;
                $c = $a - $b;
                $d = substr($c, -5);
                $e = $c - $d;
                $f = $fixNumberYeekees->number + 100000;
                $g = $f - $d;
                if ($g >= 100000) {
                    $g = $g - 100000;
                }
                if ($g > 0) {
                    $h = $g + $c;
                } elseif ($g < 0) {
                    $h = ($g) + ($c);
                }
                \Yii::info('yeekee Id: '.$watchYeekee->id, 'fix_yeekee');
                \Yii::info('round: '.$watchYeekee->round, 'fix_yeekee');
                \Yii::info("A Value: ".$a, 'fix_yeekee');
                \Yii::info("B Value: ".$b, 'fix_yeekee');
                \Yii::info("C Value: ".$c, 'fix_yeekee');
                \Yii::info("D Value: ".$d, 'fix_yeekee');
                \Yii::info("E Value: ".$e, 'fix_yeekee');
                \Yii::info("F Value: ".$f, 'fix_yeekee');
                \Yii::info("G Value: ".$g, 'fix_yeekee');
                \Yii::info("H Value: ".$h, 'fix_yeekee');
                $alians = Alian::find()->select(['id', 'alian_name'])->where(['status' => Constants::status_active])->all();
                $max_rand = count($alians) - 1;
                $rand_alian = rand(0, $max_rand);
                $alian = $alians[$rand_alian];
                $new_yeekeePost = new YeekeePost();
                $new_yeekeePost->order = $yeekeePost[0]->order;
                $new_yeekeePost->post_num = abs($g);
                $new_yeekeePost->post_name = $alian->alian_name;
                $new_yeekeePost->yeekee_id = $yeekeePost[0]->yeekee_id;
                $new_yeekeePost->is_bot = 1;
                $new_yeekeePost->username = 'fix-number';
                $new_yeekeePost->create_at = $yeekeePost[0]->create_at;
                $new_yeekeePost->create_by = 53;
                if (!$new_yeekeePost->save()) {
                    Yii::$app->runAction('system/cancel-yeekee', ['id' => $watchYeekee->id]);
                    throw new ServerErrorHttpException('Not fix number yeekeepost');
                }
                $new_sumYeekeePost = $sumYeekeePost + abs($g);    //ได้เลขรวมมาใหม่
                $new_resultYeekee = $new_sumYeekeePost - $yeekeePost[14]->post_num;
                $befor_result = [
                    'resultYeekee' => $resultYeekee,
                    'user_1' => $user_1,
                    'user_16' => $user_16,
                    'sumYeekeePost' => $sumYeekeePost
                ];
                $after_result = [
                    'resultYeekee' => $new_resultYeekee,
                    'user_1' => $new_yeekeePost,
                    'user_16' => $yeekeePost[14],
                    'sumYeekeePost' => $new_sumYeekeePost
                ];
                $resultYeekee = $new_resultYeekee;
                $user_1 =  $new_yeekeePost;
                $user_16 = $yeekeePost[14];
                $sumYeekeePost = $new_sumYeekeePost;
                ///////
//                $new_sumYeekeePost = $sumYeekeePost + abs($g);
//                $new_resultYeekee = $new_sumYeekeePost - $yeekeePost[14]->post_num;
//                $resultYeekee = $new_sumYeekeePost - $yeekeePost[14]->post_num;
//                $user_1->id =  $new_yeekeePost->id;
//                $user_16->id =  $yeekeePost[14]->id;
            }else {                
                \Yii::info('yeekee Id: '.$watchYeekee->id, 'yeekee_answer');
                \Yii::info('round: '.$watchYeekee->round, 'yeekee_answer');
                \Yii::info("Outlay Before Value: ".$outlay, 'yeekee_answer');
                \Yii::info("fair_amount Before Value: ".$fair_amount, 'yeekee_answer');
                \Yii::info("Result Yeekee Before Value: ".$resultYeekee, 'yeekee_answer');
                if ($outlay > $fair_amount && $watchYeekee->isOpenBot === 1) {   //ถ้าระบบ จ่ายหนักกว่า ที่ตั้งไว้
                    do {
                        $cal_round++;
                        $re_cal = false;
                        $is_bot = 1;
                        $new_user_16 = $yeekeePost[14];        //คือลำดับต่อไปที่จะเอาเลขมาลบ
                        $new_postnum = rand(1, 99999);    //เลขที่ระบบ random ขึ้นมาใหม่
//                        if ($bot_count == 0) {
//                            $tmp = str_pad($sumYeekeePost, 5, '0', STR_PAD_LEFT);
//                            $tmp = $tmp - $new_user_16->post_num;
//                            $lenght = strlen($tmp);
//                            $sum = substr('' . $tmp, $lenght - 5, $lenght);
//                            $good_num = YeekeeSearch::getResultsIsLowCost($yeekeeChitAll); //หาเลขที่ออกแล้ว จ่ายน้อยสุด
//                            $new_postnum = 0;
//
//
//                            if ($sum > $good_num) {
//                                $front = substr('' . $tmp, 0, $lenght - 5);
//                                $new = $front . '' . $good_num;
//                                $new = $new + 100000;
//                                $new_postnum = $new - $tmp;
//
//                            } else if ($sum < $good_num) {
//                                $front = substr('' . $tmp, 0, $lenght - 5);
//                                $new = $front . '' . $good_num;
//                                $new_postnum = $new - $tmp;
//                            }
//
//                        }
                        $new_sumYeekeePost = $sumYeekeePost + $new_postnum;    //ได้เลขรวมมาใหม่
                        $new_resultYeekee = $new_sumYeekeePost - $new_user_16->post_num;

                        $income = 0;
                        $outlay = 0;
                        //echo "<pre>",var_dump($watchYeekee->id),"</pre>";exit;
                        try {
                            foreach ($yeekeeChitAll as $key => $yeekeeChit) {                            
                                $detailInChit = $yeekeeChit->yeekeeChitDetails;
                                foreach ($detailInChit as $detailSearch) {
                                    $income += $detailSearch->amount;
                                    $outlay += YeekeeChitDetailSearch::getWinCreditStatic($new_resultYeekee, $detailSearch->play_type_code, $detailSearch->number, $detailSearch->amount);
                                }
                            }
                        }catch(\Exception $e) { return $e->getMessage(); exit;}  
                        if (($outlay < $fair_amount)) {
                            $re_cal = true;
                        }
                        $bot_count--;
                    } while (!$re_cal);
                    \Yii::info("Outlay After Value: ".$outlay, 'yeekee_answer');
                    \Yii::info("fair_amount After Value: ".$fair_amount, 'yeekee_answer');
                    \Yii::info("Result Yeekee After Value: ".$new_resultYeekee, 'yeekee_answer');
                    //bot post
                    //get alian name
                    $alians = Alian::find()->select(['id', 'alian_name'])->where(['status' => Constants::status_active])->all();
                    $max_rand = count($alians) - 1;
                    $rand_alian = rand(0, $max_rand);
                    $alian = $alians[$rand_alian];
                    $alian->use_count = $alian->use_count + 1;
                    $alian->save();

                    $new_yeekeePost = new YeekeePost();
                    $new_yeekeePost->order = $yeekeePost[0]->order;
                    $new_yeekeePost->post_num = $new_postnum;
                    $new_yeekeePost->post_name = $alian->alian_name;
                    $new_yeekeePost->yeekee_id = $yeekeePost[0]->yeekee_id;
                    $new_yeekeePost->is_bot = 1;
                    $new_yeekeePost->username = 'system';
                    $new_yeekeePost->create_at = $yeekeePost[0]->create_at;
                    $new_yeekeePost->create_by = 53;
                    $new_yeekeePost->save();


                    $befor_result = [
                        'resultYeekee' => $resultYeekee,
                        'user_1' => $user_1,
                        'user_16' => $user_16,
                        'sumYeekeePost' => $sumYeekeePost
                    ];
                    $after_result = [
                        'resultYeekee' => $new_resultYeekee,
                        'user_1' => $new_yeekeePost,
                        'user_16' => $new_user_16,
                        'sumYeekeePost' => $new_sumYeekeePost
                    ];

                    //replace
                    $resultYeekee = $new_resultYeekee;
                    $user_1 = $new_yeekeePost;
                    $user_16 = $new_user_16;
                    $sumYeekeePost = $new_sumYeekeePost;
                }
            }

            $bot = [
                'is_bot' => $is_bot,
                'bot_round' => $bot_count,
                'good_num' => $good_num,
                'income' => $income,
                'estimate_income_config' => $estimate_income_config,
                'estimate_income_credit' => $estimate_income_credit,
                'outlay' => $outlay,

                'cal_round' => $cal_round,
                'new_postnum' => $new_postnum,
                'new_sumYeekeePost' => $new_sumYeekeePost,
                'new_resultYeekee' => $new_resultYeekee,
                'new_user_1t' => $new_yeekeePost,
                'new_user_16' => $new_user_16,

                'befor_result' => $befor_result,
                'after_result' => $after_result
            ];

            ///------------------end bot

            $watchYeekee->status = Constants::status_finish_show_result;
            $watchYeekee->update_at = date('Y-m-d H:i:s');
            $watchYeekee->update_by = Constants::user_system_id;
            $watchYeekee->result = $resultYeekee;
            $watchYeekee->user_id_1 = $user_1->create_by;
            $watchYeekee->user_id_16 = $user_16->create_by;
            $watchYeekee->yeekee_post_id_1 = $user_1->id;
            $watchYeekee->yeekee_post_id_16 = $user_16->id;
            $watchYeekee->yeekee_post_sum = $sumYeekeePost;
            $result = $watchYeekee->save();
            $result_chit = [];
            if ($result) {
                $user_1_play = 0;
                $user_16_play = 0;

                //หาคนที่แทงถูก และจ่ายเครดิต
                foreach ($yeekeeChitAll as $yeekeeChit) {
                    //count user 1,16 play
                    if ($yeekeeChit->user->username == $user_1->username) {
                        $user_1_play += $yeekeeChit->total_amount;
                    }

                    if ($yeekeeChit->user->username == $user_16->username) {
                        $user_16_play += $yeekeeChit->total_amount;
                    }

                    $detailInChit = $yeekeeChit->yeekeeChitDetails;
                    foreach ($detailInChit as $detail) {
                        $detailSearch = YeekeeChitDetailSearch::findOne(['id' => $detail->id]);
                        $detail->flag_result = $detailSearch->checkWin() ? 1 : 0;
                        $detail->win_credit = $detailSearch->getWinCredit();
                        //ถ้าคืนโพย จะไม่ทำ รายการเครดิต
                        if ($detailSearch->yeekeeChit->status == Constants::status_cancel) {
                            $detail->flag_result = 0;
                            $detail->win_credit = 0;
                        }
                        //add credit when win
                        if ($detail->flag_result == 1) {

                            $remark = Constants::$reason_credit[Constants::reason_credit_bet_win] . ' จับยี่กีรอบที่ ' . $watchYeekee->round . '/' . date('d/m/Y', strtotime($watchYeekee->date_at)) . ' #' . $yeekeeChit->getOrderId();
                            if (!Credit::creditWalk(Constants::action_credit_top_up, $yeekeeChit->user_id, $admin_id, Constants::reason_credit_bet_win, $detailSearch->getWinCredit(), $remark)) {
                                $result = false;
                            }
                        }
                        if (!$detail->save()) {
                            $result = false;
                        }

                    }

                    //echo '<br>';
                    $yeekeeChit->status = Constants::status_finish_show_result;
                    if (!$yeekeeChit->save()) {
                        $result = false;
                    }
                }
                //จ่ายเงิน ลำดับที่ 1
                if ($watchYeekee->isOpenBot === 1) {
                    if ((!$user_1->is_bot) && ($user_1_play >= Constants::minimum_play_for_pay_credit)) {
                        $post_order = SettingGameBenefit::find()->where(['code' => Constants::bonus_yeekee_post_order_code_1])->one();
                        $remark = Constants::$reason_credit[Constants::reason_credit_yeekee_post_1] . ' จับยี่กีรอบที่ ' . $watchYeekee->round . '/' . date('d/m/Y', strtotime($watchYeekee->date_at)) . ' #' . $yeekeeChit->getOrderId();
                        if (!Credit::creditWalk(Constants::action_credit_top_up, $user_1->create_by, $admin_id, Constants::reason_credit_yeekee_post_1, (!empty($post_order) ? $post_order->value : 0), $remark)) {
                            $result = false;
                        }
                    }
                    //จ่ายเงิน ลำดับที่  16
                    if ((!$user_16->is_bot) && ($user_16_play >= Constants::minimum_play_for_pay_credit)) {
                        $post_order = SettingGameBenefit::find()->where(['code' => Constants::bonus_yeekee_post_order_code_16])->one();
                        $remark = Constants::$reason_credit[Constants::reason_credit_yeekee_post_16] . ' จับยี่กีรอบที่ ' . $watchYeekee->round . '/' . date('d/m/Y', strtotime($watchYeekee->date_at)) . ' #' . $yeekeeChit->getOrderId();
                        if (!Credit::creditWalk(Constants::action_credit_top_up, $user_16->create_by, $admin_id, Constants::reason_credit_yeekee_post_16, (!empty($post_order) ? $post_order->value : 0), $remark)) {
                            $result = false;
                        }
                    }
                }else{
                    if (($user_1_play >= Constants::minimum_play_for_pay_credit)) {
                        $post_order = SettingGameBenefit::find()->where(['code' => Constants::bonus_yeekee_post_order_code_1])->one();
                        $remark = Constants::$reason_credit[Constants::reason_credit_yeekee_post_1] . ' จับยี่กีรอบที่ ' . $watchYeekee->round . '/' . date('d/m/Y', strtotime($watchYeekee->date_at)) . ' #' . $yeekeeChit->getOrderId();
                        if (!Credit::creditWalk(Constants::action_credit_top_up, $user_1->create_by, $admin_id, Constants::reason_credit_yeekee_post_1, (!empty($post_order) ? $post_order->value : 0), $remark)) {
                            $result = false;
                        }
                    }
                    //จ่ายเงิน ลำดับที่  16
                    if (($user_16_play >= Constants::minimum_play_for_pay_credit)) {
                        $post_order = SettingGameBenefit::find()->where(['code' => Constants::bonus_yeekee_post_order_code_16])->one();
                        $remark = Constants::$reason_credit[Constants::reason_credit_yeekee_post_16] . ' จับยี่กีรอบที่ ' . $watchYeekee->round . '/' . date('d/m/Y', strtotime($watchYeekee->date_at)) . ' #' . $yeekeeChit->getOrderId();
                        if (!Credit::creditWalk(Constants::action_credit_top_up, $user_16->create_by, $admin_id, Constants::reason_credit_yeekee_post_16, (!empty($post_order) ? $post_order->value : 0), $remark)) {
                            $result = false;
                        }
                    }
                }
                //จ่ายค่าคอมมิชชั่น
                if (count($commission_user_agent_play) > 0) {
                    $model = SettingCommissionCredit::find()->where(['type' => Constants::setting_commission_credit_invite])->one();
                    $percent = 0;
                    if (!empty($model)) {
                        $percent = empty($model->value) ? 0 : $model->value;
                    }
                    foreach ($commission_user_agent_play as $agent_id => $sum_all_chit_amount) {
                        $commission_amount = $sum_all_chit_amount * $percent / 100;
                        $remark = Constants::$reason_credit[Constants::reason_credit_commission_in] . ' จับยี่กีรอบที่ ' . $watchYeekee->round . '/' . date('d/m/Y', strtotime($watchYeekee->date_at)) . ' #' . $yeekeeChit->getOrderCommissionId();
                        Commission::commissionWalk(Constants::action_commission_top_up, $agent_id, $admin_id, Constants::reason_commission_top_up, $commission_amount, $remark);
                    }
                }

                Queue::updateAll(['status' => Constants::status_inactive], ['gameId' => Constants::YEEKEE]);
                if ($result) {
                    $transaction->commit();
                } else {
                    Yii::$app->runAction('system/cancel-yeekee', ['id' => $watchYeekee->id]);
                    $transaction->rollBack();
                    return false;
                }
                return [
                    'now' => date('Y-m-d H:i:s'),
                    'outlay' => $outlay,
                    'income' => $income,
                    'fair_amount' => $fair_amount,
                    'estimate_income_config' => $estimate_income_config,
                    'estimate_income_credit' => $estimate_income_credit,
                    'yeekee_id' => $watchYeekee->id,
                    'yeekee_finish_at' => $watchYeekee->finish_at,
                    'status' => Constants::$status[$watchYeekee->status],
                    'reason' => 'finish yeekee done.',
                    'watchYeekee' => $watchYeekee,
                    //'result_chit'=>$result_chit,
                    'bot' => $bot
                ];
            } else {
                return [
                    'now' => date('Y-m-d H:i:s'),
                    'yeekee_id' => $watchYeekee->id,
                    'yeekee_finish_at' => $watchYeekee->finish_at,
                    'status' => Constants::$status[$watchYeekee->status],
                    'reason' => 'finish error.',
                    'error' => json_encode($watchYeekee->getErrors())
                ];
            }
        }
    }

    public function actionBlackredAnswer($authKey)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $admin_id = Constants::user_system_id;
        $game = Games::find()->where(['title' => 'ดำแดง', 'status' => 1, 'gameAuthKey' => $authKey])->one();
        if (!$game) {
            return [
                'message' => 'game not found !',
            ];
        }
        $watchBlackreds = BlackRed::find()->where(['status' => 1])->andWhere('finish_at <= DATE_SUB(NOW(), INTERVAL -30 SECOND)')->orderBy(['finish_at' => SORT_ASC])->all();
        if (!$watchBlackreds) {
            return [
                'now' => date('Y-m-d H:i:s'),
                'blackred_id' => 'null',
                'blackred_finish_at' => 'null',
                'status' => 'null',
                'reason' => 'blackred is empty'
            ];
        }
        $transaction = Yii::$app->db->beginTransaction();
        foreach ($watchBlackreds as $watchBlackred) {
            $commission_user_agent_play = [];
            try {
                $blackChit = BlackredChit::find()->where(['blackred_id' => $watchBlackred->id, 'play_type_code' => Constants::BLACK])->sum('total_amount');
                $redChit = BlackredChit::find()->where(['blackred_id' => $watchBlackred->id, 'play_type_code' => Constants::RED])->sum('total_amount');
                $playTypeCodeBlack = Constants::getPlayTypeCode(['black']);
                if (!$playTypeCodeBlack) {
                    return [
                        'message' => 'play type code black not found !',
                    ];
                }
                $totalBlackChit = $blackChit * $playTypeCodeBlack->jackpot_per_unit;
                $playTypeCodeRed = Constants::getPlayTypeCode(['red']);
                if (!$playTypeCodeRed) {
                    return [
                        'message' => 'play type code red not found !',
                    ];
                }
                $totalRedChit = $redChit * $playTypeCodeRed->jackpot_per_unit;
                $blackredChits = BlackredChit::find()->where(['blackred_id' => $watchBlackred->id, 'status' => Constants::status_playing])->all();
                if (!$blackredChits) {
                    $botBlackRedFixResult = BotBlackRedFixResult::find()->where(['round' => $watchBlackred->round, 'date' => $watchBlackred->date_at])->one();
                    if ($botBlackRedFixResult) {
                        $winCondition = $botBlackRedFixResult->play_type_code;
                    } else {
                        $winCondition = Constants::RED;
                    }
                }else {
                    if ($totalRedChit > $totalBlackChit) {
                        $winCondition = Constants::BLACK;
                    } else {
                        $winCondition = Constants::RED;
                    }
                }
                $totalAmount = 0;
                $watchBlackred->status = Constants::status_finish_show_result;
                $watchBlackred->update_at = date('Y-m-d H:i:s');
                $watchBlackred->update_by = $admin_id;
                $watchBlackred->result = $winCondition;
                if (!$watchBlackred->save()) {
                    return [
                        'message' => 'not save by blackred id ' . $watchBlackred->id,
                    ];
                }
                foreach ($blackredChits as $blackredChit) {
                    if ($blackredChit->play_type_code == $winCondition) {
                        $blackredChit->flag_result = 1;
                    } else {
                        $blackredChit->flag_result = 0;
                        $blackredChit->win_credit = 0;
                    }
                    $blackredChit->status = Constants::status_finish_show_result;
                    $blackredChit->update_at = date('Y-m-d H:i:s');
                    $blackredChit->update_by = $admin_id;
                    $totalAmount += $blackredChit->win_credit;
                    if (!$blackredChit->save()) {
                        return [
                            'message' => 'not save by blackred chit id ' . $blackredChit->id,
                        ];
                    }
                    if ($blackredChit->flag_result == 1) {
                        $remark = Constants::$reason_credit[Constants::reason_credit_bet_win] . ' ดำแดงรอบที่ ' . $watchBlackred->round . '/' . date('d/m/Y', strtotime($watchBlackred->date_at)) . ' #' . $watchBlackred->getOrderId();
                        if (!Credit::creditWalk(Constants::action_credit_top_up, $blackredChit->user_id, $admin_id, Constants::reason_credit_bet_win, $blackredChit->win_credit, $remark)) {
                            return [
                                'message' => 'not save by credit walk ' . $blackredChit->id,
                            ];
                        }
                    }
                }
                if (!empty($blackredChit->user->agent)) {
                    if (!isset($commission_user_agent_play[$blackredChit->user->agent])) {
                        $commission_user_agent_play[$blackredChit->user->agent] = 0;
                    }
                    $commission_user_agent_play[$blackredChit->user->agent] += $blackredChit->total_amount;
                }
                if ($commission_user_agent_play) {
                    $model = SettingCommissionCredit::find()->where(['type' => Constants::setting_commission_game_blackred_credit_invite])->one();
                    $percent = 0;
                    if (!empty($model)) {
                        $percent = empty($model->value) ? 0 : $model->value;
                    }
                    foreach ($commission_user_agent_play as $agent_id => $sum_all_chit_amount) {
                        $commission_amount = $sum_all_chit_amount * $percent / 100;
                        $remark = Constants::$reason_credit[Constants::reason_credit_commission_in] . ' แทงดำแดงรอบที่ ' . $watchBlackred->round . '/' . date('d/m/Y', strtotime($watchBlackred->date_at)) . ' #' . $watchBlackred->getOrderId();
                        CommissionBlackred::commissionWalk(Constants::action_commission_top_up, $agent_id, $admin_id, Constants::reason_commission_top_up, $commission_amount, $remark);
                    }
                }
                $transaction->commit();
                return [
                    'now' => date('Y-m-d H:i:s'),
                    'total_amount' => $totalAmount,
                    'blackred_id' => $watchBlackred->id,
                    'blackred_finish_at' => $watchBlackred->finish_at,
                    'status' => Constants::$status[$watchBlackred->status],
                    'reason' => 'finish blackred done.',
                    'watchBlackred' => $watchBlackred,
                ];
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            }
        }
        return ['message' => 'not time out answer game'];
    }

}