<?php

namespace backend\controllers;

use common\libs\Constants;
use common\models\AuthRoles;
use common\models\Commission;
use common\models\Credit;
use common\models\LotteryShowNumberResultWin;
use common\models\PlayType;
use common\models\Queue;
use common\models\SettingCommissionCredit;
use common\models\ThaiSharedAnswerGame;
use common\models\ThaiSharedAnswerGameForm;
use common\models\ThaiSharedGame;
use common\models\ThaiSharedGameChit;
use common\models\ThaiSharedGameChitDetail;
use common\models\ThaiSharedGameChitDetailSearch;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\ServerErrorHttpException;

class ApiAnswerGameController extends Controller

{

    public function actionAnswerShow()
    {
        require 'script.php';

        $html = file_get_html('https://jhaosua.com');

        // หวยรัฐบาล
        $first = $html->find('p[class="font-weight-bold mb-0"]', 0);
        $threefront = $html->find('p[class="card-text mb-0 font-weight-bold"]', 0);
        $threeback = $html->find('p[class="card-text mb-0 font-weight-bold"]', 1);
        $two_under = $html->find('p[class="card-text mb-0 font-weight-bold"]', 2);

        $first = $first->plaintext;
        $two_under = $two_under->plaintext;
        $threefront = explode("," ,$threefront->plaintext);
        $threeback = explode("," ,$threeback->plaintext);

        echo '<p>หวยรัฐบาล</p>';
        echo 'รางวัลที่ 1 => ' .$first.'<br>';
        echo 'เลขท้าย 2 ตัว => ' .$two_under.'<br>';
        echo 'เลขหน้า 3 ตัว ครั้งที่ 1 => ' .$threefront[0].'<br>';
        echo 'เลขหน้า 3 ตัว ครั้งที่ 2 => ' .$threefront[1].'<br>';
        echo 'เลขท้าย 3 ตัว ครั้งที่ 1 => ' .$threeback[0].'<br>';
        echo 'เลขท้าย 3 ตัว ครั้งที่ 2 => ' .$threeback[1].'<br>';


        // หวยออมสิน
        $firstsixGSB = $html->find('p[class="card-text"]', 0);
        $threetopGSB = $html->find('p[class="card-text"]', 1);
        $twodownGSB = $html->find('p[class="card-text"]', 2);
        
        $firstsixGSB = $firstsixGSB->plaintext;
        $threetopGSB = $threetopGSB->plaintext;
        $twodownGSB = $twodownGSB->plaintext;

        echo '<p>หวยออมสิน</p>';
        echo 'รางวัลเลขท้าย 6 ตัว => ' . $firstsixGSB.'<br>';
        echo '3 ตัวบน => ' . $threetopGSB.'<br>';
        echo '2 ตัวล่าง => ' . $twodownGSB.'<br>';


        // หวย ธกส
        $firstBAAC = $html->find('p[class="card-text"]', 3);
        $threetopBAAC = $html->find('p[class="card-text"]', 4);
        $twodownBAAC = $html->find('p[class="card-text"]', 5);
        
        $firstBAAC = $firstBAAC->plaintext;
        $threetopBAAC = $threetopBAAC->plaintext;
        $twodownBAAC = $twodownBAAC->plaintext;

        echo '<p>หวย ธกส.</p>';
        echo 'เลขที่ออก => ' . $firstBAAC.'<br>';
        echo '3 ตัวบน => ' . $threetopBAAC.'<br>';
        echo '2 ตัวล่าง => ' . $twodownBAAC.'<br>';
    }



    public function actionAnswerShowRuay()
    {
        require 'script.php';

        $html = file_get_html('https://www.ruay.com/login');

        // หวยรัฐบาล
        $first = $html->find('p[class="card-text"]', 2);
        $threefront = $html->find('p[class="card-text"]', 3);
        $threeback = $html->find('p[class="card-text"]', 4);
        $two_under = $html->find('p[class="card-text"]', 5);

        $first = $first->plaintext;
        $two_under = $two_under->plaintext;
        $threefront = explode("," ,$threefront->plaintext);
        $threeback = explode("," ,$threeback->plaintext);

        echo '<p>หวยรัฐบาล</p>';
        echo 'รางวัลที่ 1 => ' .$first.'<br>';
        echo 'เลขท้าย 2 ตัว => ' .$two_under.'<br>';
        echo 'เลขหน้า 3 ตัว ครั้งที่ 1 => ' .$threefront[0].'<br>';
        echo 'เลขหน้า 3 ตัว ครั้งที่ 2 => ' .$threefront[1].'<br>';
        echo 'เลขท้าย 3 ตัว ครั้งที่ 1 => ' .$threeback[0].'<br>';
        echo 'เลขท้าย 3 ตัว ครั้งที่ 2 => ' .$threeback[1].'<br>';


        // หวยออมสิน
        $firstsixGSB = $html->find('p[class="card-text"]', 6);
        $threetopGSB = $html->find('p[class="card-text"]', 7);
        $twodownGSB = $html->find('p[class="card-text"]', 8);
        
        $firstsixGSB = $firstsixGSB->plaintext;
        $threetopGSB = $threetopGSB->plaintext;
        $twodownGSB = $twodownGSB->plaintext;

        echo '<p>หวยออมสิน</p>';
        echo 'รางวัลเลขท้าย 6 ตัว => ' . $firstsixGSB.'<br>';
        echo '3 ตัวบน => ' . $threetopGSB.'<br>';
        echo '2 ตัวล่าง => ' . $twodownGSB.'<br>';


        // // หวย ธกส
        $firstBAAC = $html->find('p[class="card-text"]', 9);
        $threetopBAAC = $html->find('p[class="card-text"]', 10);
        $twodownBAAC = $html->find('p[class="card-text"]', 11);
        
        $firstBAAC = $firstBAAC->plaintext;
        $threetopBAAC = $threetopBAAC->plaintext;
        $twodownBAAC = $twodownBAAC->plaintext;

        echo '<p>หวย ธกส.</p>';
        echo 'เลขที่ออก => ' . $firstBAAC.'<br>';
        echo '3 ตัวบน => ' . $threetopBAAC.'<br>';
        echo '2 ตัวล่าง => ' . $twodownBAAC.'<br>';
    }




    public function actionAnswerGoverment()
    {
        $goverment = ThaiSharedGame::find()->where(['title' => 'หวยรัฐบาลไทย'])->orderBy(['id' => SORT_DESC])->one();
        $id = $goverment['id'];
        $thaiSharedAnswerGame = ThaiSharedAnswerGame::find()->where(['thaiSharedGameId' => $id])->count();
        if ($thaiSharedAnswerGame) {
            throw new ServerErrorHttpException('หวยหุ้นนี้ถูกสร้างแล้วไม่สามารสร้างเฉลยซ้ำได้');
        }
        
        $model = new ThaiSharedAnswerGameForm();
        $thaiSharedGame = ThaiSharedGame::findOne(['id' => $id]);
        if ($thaiSharedGame->status !== 1) {
            throw new ServerErrorHttpException('หวยหุ้นนี้ถูกเฉลยไปแล้วไม่สามารถเฉลยซ้ำได้');
        }
        if ($thaiSharedGame->startDate >= date('Y-m-d H:i:s') || $thaiSharedGame->endDate > date('Y-m-d H:i:s')) {
            throw new ServerErrorHttpException('ไม่สามารถออกผลได้เนื่องจากยังไม่สิ้นสุดเวลาหรือยังไม่สิ้นสุดเวลา');
        }
        if ($thaiSharedGame->gameId === Constants::LOTTERYGAMEDISCOUNT || $thaiSharedGame->gameId === Constants::LOTTERYGAME) {
            $model->setScenario('lotteryGame');
        } elseif ($thaiSharedGame->gameId === Constants::LOTTERYLAOGAME || $thaiSharedGame->gameId === Constants::LOTTERYLAODISCOUNTGAME || $thaiSharedGame->gameId === Constants::LOTTERY_VIETNAM_SET) {
            $model->setScenario('lottery-lao-set');
        }

        require 'script.php';

        $html = file_get_html('https://www.ruay.com/login');

        // หวยรัฐบาล
        $first = $html->find('p[class="card-text"]', 2);
        $threefront = $html->find('p[class="card-text"]', 3);
        $threeback = $html->find('p[class="card-text"]', 4);
        $two_under = $html->find('p[class="card-text"]', 5);

        $first = $first->plaintext;
        $two_under = $two_under->plaintext;
        $threefront = explode("," ,$threefront->plaintext);
        $threeback = explode("," ,$threeback->plaintext);
        
        if(is_numeric($first) == 1){
            $transaction = Yii::$app->db->beginTransaction();
            try {

                    if ($thaiSharedGame->gameId ===  Constants::LOTTERYGAME) {
                        $three_top = substr($first, 3, 3);
                        $lotteryShowNumberResultWin = new LotteryShowNumberResultWin();
                        $lotteryShowNumberResultWin->number = $first;
                        $lotteryShowNumberResultWin->thaiSharedGameId = $thaiSharedGame->id;
                        if (!$lotteryShowNumberResultWin->save()) {
                            throw new ServerErrorHttpException('Can not save Lottery Number Result Win');
                        }
                    }
                    $answers = [
                        'three_top' => $three_top,
                        'three_tod' => $this->findThreeTod($three_top),
                        'two_top' => $this->findTwoTop($three_top),
                        'run_top' => $this->findRunTop($three_top),
                        'two_under' => $two_under,
                        'run_under' => $this->findRunUnder($two_under),
                    ];

                    if ($thaiSharedGame->gameId === Constants::LOTTERYGAMEDISCOUNT || $thaiSharedGame->gameId === Constants::LOTTERYGAME) {
                        $answerLotteryGames = [
                            'three_top2' => [
                                $threefront[0],
                                $threefront[1]
                            ],
                            'three_und2' => [
                                $threeback[0],
                                $threeback[1]
                            ],
                        ];
                        $answers = array_merge($answers, $answerLotteryGames);
                    }

                    foreach ($answers as $key => $answer) {
                        $playType = PlayType::find()->where(['game_id' => $thaiSharedGame->gameId, 'code' => $key])->one();
                        if (!$playType) {
                            throw new ServerErrorHttpException('Not Found PlayType');
                        }
                        if (is_array($answer)) {
                            foreach ($answer as $number) {
                                $thaiSharedAnswerGame = new ThaiSharedAnswerGame();
                                $thaiSharedAnswerGame->thaiSharedGameId = $id;
                                $thaiSharedAnswerGame->playTypeId = $playType->id;
                                $thaiSharedAnswerGame->number = $number;
                                $thaiSharedAnswerGame->createdBy = '1';
                                if (!$thaiSharedAnswerGame->save()) {
                                    throw new ServerErrorHttpException('Can not Save');
                                }
                            }
                        } else {
                            $thaiSharedAnswerGame = new ThaiSharedAnswerGame();
                            $thaiSharedAnswerGame->thaiSharedGameId = $id;
                            $thaiSharedAnswerGame->playTypeId = $playType->id;
                            $thaiSharedAnswerGame->number = $answer;
                            $thaiSharedAnswerGame->createdBy = '1';
                            if (!$thaiSharedAnswerGame->save()) {
                                throw new ServerErrorHttpException('Can not Save');
                            }
                        }
                    }

                    $this->redirect(array('tricker', 'id' => $id));
                    
                    $transaction->commit();
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    throw $e;
                }
            }else{
                return ['message' => 'เว็บไซต์ต้นทางยังไม่ออกผลรางวัล'];
            }
    }    



    public function actionAnswerGsb()
    {
        $gsb = ThaiSharedGame::find()->where(['title' => 'หวยออมสิน'])->orderBy(['id' => SORT_DESC])->one();
        $id = $gsb['id'];
        $thaiSharedAnswerGame = ThaiSharedAnswerGame::find()->where(['thaiSharedGameId' => $id])->count();
        if ($thaiSharedAnswerGame) {
            throw new ServerErrorHttpException('หวยหุ้นนี้ถูกสร้างแล้วไม่สามารสร้างเฉลยซ้ำได้');
        }
        $model = new ThaiSharedAnswerGameForm();
        $thaiSharedGame = ThaiSharedGame::findOne(['id' => $id]);
        if ($thaiSharedGame->status !== 1) {
            throw new ServerErrorHttpException('หวยหุ้นนี้ถูกเฉลยไปแล้วไม่สามารถเฉลยซ้ำได้');
        }
        if ($thaiSharedGame->startDate >= date('Y-m-d H:i:s') || $thaiSharedGame->endDate > date('Y-m-d H:i:s')) {
            throw new ServerErrorHttpException('ไม่สามารถออกผลได้เนื่องจากยังไม่สิ้นสุดเวลาหรือยังไม่สิ้นสุดเวลา');
        }
        if ($thaiSharedGame->gameId === Constants::LOTTERYGAMEDISCOUNT || $thaiSharedGame->gameId === Constants::LOTTERYGAME) {
            $model->setScenario('lotteryGame');
        } elseif ($thaiSharedGame->gameId === Constants::LOTTERYLAOGAME || $thaiSharedGame->gameId === Constants::LOTTERYLAODISCOUNTGAME || $thaiSharedGame->gameId === Constants::LOTTERY_VIETNAM_SET) {
            $model->setScenario('lottery-lao-set');
        }

        require 'script.php';

        $html = file_get_html('https://www.ruay.com/login');

        // หวยออมสิน
        $firstsixGSB = $html->find('p[class="card-text"]', 6);
        $threetopGSB = $html->find('p[class="card-text"]', 7);
        $twodownGSB = $html->find('p[class="card-text"]', 8);
        
        $firstsixGSB = $firstsixGSB->plaintext;
        $threetopGSB = $threetopGSB->plaintext;
        $twodownGSB = $twodownGSB->plaintext;
        
        if(is_numeric($twodownGSB) == 1){
            $transaction = Yii::$app->db->beginTransaction();
            try {
                    $answers = [
                        'three_top' => $threetopGSB,
                        'two_under' => $twodownGSB,
                    ];

                    foreach ($answers as $key => $answer) {
                    $playType = PlayType::find()->where(['game_id' => $thaiSharedGame->gameId, 'code' => $key])->one();
                    if (!$playType) {
                        throw new ServerErrorHttpException('Not Found PlayType');
                    }
                    if (is_array($answer)) {
                        foreach ($answer as $number) {
                            $thaiSharedAnswerGame = new ThaiSharedAnswerGame();
                            $thaiSharedAnswerGame->thaiSharedGameId = $id;
                            $thaiSharedAnswerGame->playTypeId = $playType->id;
                            $thaiSharedAnswerGame->number = $number;
                            $thaiSharedAnswerGame->createdBy = '1';
                            if (!$thaiSharedAnswerGame->save()) {
                                throw new ServerErrorHttpException('Can not Save');
                            }
                        }
                    } else {
                        $thaiSharedAnswerGame = new ThaiSharedAnswerGame();
                        $thaiSharedAnswerGame->thaiSharedGameId = $id;
                        $thaiSharedAnswerGame->playTypeId = $playType->id;
                        $thaiSharedAnswerGame->number = $answer;
                        $thaiSharedAnswerGame->createdBy = '1';
                        if (!$thaiSharedAnswerGame->save()) {
                            throw new ServerErrorHttpException('Can not Save');
                        }
                    }
                }
                if ($thaiSharedGame->gameId === Constants::GSB_THAISHARD_GAME ) {
                    $thaiSharedGame->result = $firstsixGSB !== '' ? $firstsixGSB : null;
                    if (!$thaiSharedGame->save()) {
                        throw new ServerErrorHttpException('can not save result thai shared');
                    }
                }

                    $this->redirect(array('tricker', 'id' => $id));   
                    $transaction->commit();
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    throw $e;
                }
            }else{
                return ['message' => 'เว็บไซต์ต้นทางยังไม่ออกผลรางวัล'];
            }
    }   




    public function actionAnswerBacc()
    {
        $bacc = ThaiSharedGame::find()->where(['title' => 'หวย ธกส'])->orderBy(['id' => SORT_DESC])->one();
        $id = $bacc['id'];
        $thaiSharedAnswerGame = ThaiSharedAnswerGame::find()->where(['thaiSharedGameId' => $id])->count();
        if ($thaiSharedAnswerGame) {
            throw new ServerErrorHttpException('หวยหุ้นนี้ถูกสร้างแล้วไม่สามารสร้างเฉลยซ้ำได้');
        }
        $model = new ThaiSharedAnswerGameForm();
        $thaiSharedGame = ThaiSharedGame::findOne(['id' => $id]);
        if ($thaiSharedGame->status !== 1) {
            throw new ServerErrorHttpException('หวยหุ้นนี้ถูกเฉลยไปแล้วไม่สามารถเฉลยซ้ำได้');
        }
        if ($thaiSharedGame->startDate >= date('Y-m-d H:i:s') || $thaiSharedGame->endDate > date('Y-m-d H:i:s')) {
            throw new ServerErrorHttpException('ไม่สามารถออกผลได้เนื่องจากยังไม่สิ้นสุดเวลาหรือยังไม่สิ้นสุดเวลา');
        }
        if ($thaiSharedGame->gameId === Constants::LOTTERYGAMEDISCOUNT || $thaiSharedGame->gameId === Constants::LOTTERYGAME) {
            $model->setScenario('lotteryGame');
        } elseif ($thaiSharedGame->gameId === Constants::LOTTERYLAOGAME || $thaiSharedGame->gameId === Constants::LOTTERYLAODISCOUNTGAME || $thaiSharedGame->gameId === Constants::LOTTERY_VIETNAM_SET) {
            $model->setScenario('lottery-lao-set');
        }

        require 'script.php';

        $html = file_get_html('https://www.ruay.com/login');

        // หวย ธกส
        $firstBAAC = $html->find('p[class="card-text"]', 9);
        $threetopBAAC = $html->find('p[class="card-text"]', 10);
        $twodownBAAC = $html->find('p[class="card-text"]', 11);
        
        $firstBAAC = $firstBAAC->plaintext;
        $threetopBAAC = $threetopBAAC->plaintext;
        $twodownBAAC = $twodownBAAC->plaintext;
        
        if(is_numeric($firstBAAC) == 1){
            $transaction = Yii::$app->db->beginTransaction();
            try {
                    $answers = [
                        'three_top' => $threetopBAAC,
                        'two_under' => $twodownBAAC,
                    ];

                    foreach ($answers as $key => $answer) {
                    $playType = PlayType::find()->where(['game_id' => $thaiSharedGame->gameId, 'code' => $key])->one();
                    if (!$playType) {
                        throw new ServerErrorHttpException('Not Found PlayType');
                    }
                    if (is_array($answer)) {
                        foreach ($answer as $number) {
                            $thaiSharedAnswerGame = new ThaiSharedAnswerGame();
                            $thaiSharedAnswerGame->thaiSharedGameId = $id;
                            $thaiSharedAnswerGame->playTypeId = $playType->id;
                            $thaiSharedAnswerGame->number = $number;
                            $thaiSharedAnswerGame->createdBy = '1';
                            if (!$thaiSharedAnswerGame->save()) {
                                throw new ServerErrorHttpException('Can not Save');
                            }
                        }
                    } else {
                        $thaiSharedAnswerGame = new ThaiSharedAnswerGame();
                        $thaiSharedAnswerGame->thaiSharedGameId = $id;
                        $thaiSharedAnswerGame->playTypeId = $playType->id;
                        $thaiSharedAnswerGame->number = $answer;
                        $thaiSharedAnswerGame->createdBy = '1';
                        if (!$thaiSharedAnswerGame->save()) {
                            throw new ServerErrorHttpException('Can not Save');
                        }
                    }
                }
                if ($thaiSharedGame->gameId === Constants::BACC_THAISHARD_GAME ) {
                    $thaiSharedGame->result = $firstBAAC !== '' ? $firstBAAC : null;
                    if (!$thaiSharedGame->save()) {
                        throw new ServerErrorHttpException('can not save result thai shared');
                    }
                }

                    $this->redirect(array('tricker', 'id' => $id));   
                    $transaction->commit();
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    throw $e;
                }
            }else{
                return ['message' => 'เว็บไซต์ต้นทางยังไม่ออกผลรางวัล'];
            }
    }   






        public function actionTricker($id)
        {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $thaiSharedAnswerGames = ThaiSharedAnswerGame::find()->where(['thaiSharedGameId' => $id])->groupBy('playTypeId, number')->all();
            $thaiSharedGame = ThaiSharedGame::find()->where(['id' => $id])->one();
            if ($thaiSharedGame->startDate >= date('Y-m-d H:i:s') || $thaiSharedGame->endDate > date('Y-m-d H:i:s')) {
                return ['message' => 'ไม่สามารถออกผลได้เนื่องจากยังไม่สิ้นสุดเวลาหรือยังไม่สิ้นสุดเวลา'];
            }
            if (!$thaiSharedAnswerGames) {
                return ['message' => 'lotteryGameChitAnswers Not Found'];
            }
            $thaiSharedGameChits = ThaiSharedGameChit::find()->where([
                'thaiSharedGameId' => $id,
                'status' => Constants::status_playing
            ])->all();
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $thaiSharedGame->status = Constants::status_finish_show_result;
                $thaiSharedGame->updatedBy = '1';
                $thaiSharedGame->updateAt = date('Y-m-d H:i:s');
                if (!$thaiSharedGame->save()) {
                    throw new ServerErrorHttpException('Can not save Thai Shared Game');
                }
                $thaiSharedGameChitIds = [];
                $commissionUserAgentPlay = [];
                if (!$thaiSharedGameChits){
                    return ['message' => 'ไม่มีรอบที่ต้องออกผลแล้ว'];
                }
                $userIds = [];
                foreach ($thaiSharedGameChits as $thaiSharedGameChit) {
                    $thaiSharedGameChitIds[] = $thaiSharedGameChit->id;
                    $thaiSharedGameChit->status = Constants::status_finish_show_result;
                    if (!$thaiSharedGameChit->save()) {
                        throw new ServerErrorHttpException('Can not save Thai Shared Game Chit');
                    }
                    if (!empty($thaiSharedGameChit->user->agent)) {
                        if (!isset($commissionUserAgentPlay[$thaiSharedGameChit->user->agent])) {
                            $commissionUserAgentPlay[$thaiSharedGameChit->user->agent] = 0;
                        }
                        $commissionUserAgentPlay[$thaiSharedGameChit->user->agent] += $thaiSharedGameChit->totalDiscount > 0 ?
                            $thaiSharedGameChit->totalDiscount : $thaiSharedGameChit->totalAmount;
                    }
                    $userIds[] = $thaiSharedGameChit->userId;
                }
                if (count($commissionUserAgentPlay) > 0) {
                    $settingCommissionCredit = SettingCommissionCredit::find()->where(['type' => Constants::setting_commission_credit_invite])->one();
                    $percent = 0;
                    if (!empty($settingCommissionCredit)) {
                        $percent = empty($settingCommissionCredit->value) ? 0 : $settingCommissionCredit->value;
                    }
                    foreach ($commissionUserAgentPlay as $agentId => $commissionUserAgent) {
                        $commissionAmount = $commissionUserAgent * $percent / 100;
                        $remark = Constants::$reason_credit[Constants::reason_credit_commission_in] . ' แทงหวยหุ้น ' . $thaiSharedGame->title . '/' . date('d/m/Y', strtotime($thaiSharedGame->endDate)) . ' #' . $thaiSharedGameChit->getOrder();
                        Commission::commissionWalk(Constants::action_commission_top_up, $agentId, 1, Constants::reason_commission_top_up, $commissionAmount, $remark);
                    }
                }
                foreach ($thaiSharedAnswerGames as $key => $thaiSharedAnswerGame) {
                    /* @var $thaiSharedAnswerGame ThaiSharedAnswerGame */
                    $thaiSharedAnswerGame->updatedBy = '1';
                    $thaiSharedAnswerGame->updatedAt = date('Y-m-d H:i:s');
                    if (!$thaiSharedAnswerGame->save()) {
                        throw new ServerErrorHttpException('Can not update thai shared answer game');
                    }
                    $thaiSharedGameChitDetails = ThaiSharedGameChitDetail::find()->where([
                        'number' => $thaiSharedAnswerGame->number,
                        'playTypeId' => $thaiSharedAnswerGame->playTypeId,
                        'thaiSharedGameChitId' => $thaiSharedGameChitIds
                    ])->all();
                    foreach ($thaiSharedGameChitDetails as $thaiSharedGameChitDetail) {
                        $thaiSharedGameChitDetail->flag_result = 1;
                        $jackpotPerUnit = $thaiSharedGameChitDetail->jackPotPerUnit;
                        $thaiSharedGameChitDetail->win_credit = $thaiSharedGameChitDetail->amount * $jackpotPerUnit;
                        $remark = Constants::$reason_credit[Constants::reason_credit_bet_win] . ' ' . $thaiSharedGame->title . '/ ' . date('d/m/Y', strtotime($thaiSharedGame->endDate)) . '/ ประเภทที่ถูกรางวัล: ' . $thaiSharedAnswerGame->playType->title . ' /#' . $thaiSharedGameChitDetail->thaiSharedGameChit->getOrder();
                        $creditWalk = Credit::creditWalk(Constants::action_credit_top_up, $thaiSharedGameChitDetail->userId, 1, Constants::reason_credit_bet_win, $thaiSharedGameChitDetail->win_credit, $remark);
                        if (!$creditWalk) {
                            throw new ServerErrorHttpException('Not save creditWalk');
                        }
                        if (!$thaiSharedGameChitDetail->save()) {
                            throw new ServerErrorHttpException('Can not save lottery game chit detail');
                        }
                    }
                }
                ThaiSharedGameChitDetail::updateAll(['flag_result' => 0, 'win_credit' => 0],
                    ['AND',
                        'flag_result = 0',
                        ['IN', 'thaiSharedGameChitId', $thaiSharedGameChitIds]
                    ]);
                Queue::updateAll(['status' => Constants::status_inactive], ['gameId' => $thaiSharedGame->gameId, 'userId' => $userIds]);
                $transaction->commit();
                return ['message' => 'success'];
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            }
        }
    
    
        /**
     * Creates a new LotteryGameChitAnswer model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */

    
    
     protected function findThreeTod($number)
    {
        $arr_num = str_split($number);
        $n = 0;
        $arr_swap_num = [];
        for ($i = 0; $i < count($arr_num); $i++) {
            $tmp = $arr_num[$i];
            for ($j = 0; $j < count($arr_num); $j++) {
                if ($i != $j) {
                    $tmp .= '' . $arr_num[$j];
                }
            }
            if (!in_array($tmp, $arr_swap_num)) {
                $arr_swap_num[$n++] = $tmp;
            }

            $tmp = $arr_num[$i];
            for ($j = (count($arr_num) - 1); $j >= 0; $j--) {
                if ($i != $j) {
                    $tmp .= '' . $arr_num[$j];
                }
            }
            if (!in_array($tmp, $arr_swap_num)) {
                $arr_swap_num[$n++] = $tmp;
            }
        }
        return $arr_swap_num;
    }


    protected function findTwoTop($number)
    {
        return substr($number, 1, 2);
    }

    protected function findRunTop($number)
    {
        return str_split($number);
    }

    protected function findRunUnder($number)
    {
        return str_split($number);
    }

    protected function findGenerateNumberByPlayType($number, $playType)
    {
        if ($playType === 'four_tod') {
            return Constants::permute($number);
        } else if ($playType === 'three_ft') {
            return substr($number, 0, 3);
        }else if ($playType === 'three_top') {
            return substr($number, 1, 3);
        } else if ($playType === 'three_tod') {
            $number = substr($number, 1, 3);
            return Constants::permute($number);
        } else if ($playType === 'two_ft') {
            return substr($number, 0, 2);
        } else if ($playType === 'two_bk') {
            return substr($number, 2, 2);
        }
        return $number;
    }
}