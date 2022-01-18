<?php

namespace backend\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Running;
use common\models\ConfigGenerateGame;
use common\models\Yeekee;
use common\libs\Constants;
use common\models\YeekeePost;
use common\models\YeekeeChitSearch;
use common\models\YeekeeChitDetailSearch;
use common\models\YeekeeSearch;
use common\models\SettingBenefit;
use common\models\Alian;
use common\models\BlackRed;
use yii\web\Controller;
use yii\web\ServerErrorHttpException;
use common\models\AuthRoles;

/**
 * Site controller
 */
class AdminController extends Controller
{
    public function behaviors()
    {
        $identity = \Yii::$app->user->getIdentity();
        if (empty($identity)) {
            $this->layout = false;
            $this->redirect(Yii::$app->urlManager->createUrl(['user/login']));
        } else {
            $modelAuthRoles = new AuthRoles();
            $arrRoles = $modelAuthRoles->_getRoles($identity->auth_roles_id);
            if (!in_array('admin', $arrRoles)) {
                $this->layout = false;
                $this->redirect(Yii::$app->urlManager->createUrl(['user/logout']));
            }
        }
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
            ],
            'access'=> [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ]
            ]
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $identity = \Yii::$app->user->getIdentity();
        $modelAuthRoles = new AuthRoles();
        $arrRoles = $modelAuthRoles->_getRoles($identity->auth_roles_id);
        $yeekee = Yeekee::find()->where(['date_at' => date('Y-m-d')])->one();
        $blackred = BlackRed::find()->where(['date_at' => date('Y-m-d')])->one();

        return $this->render('index', [
            'yeekee' => $yeekee,
            'blackred' => $blackred,
            'arrRoles' => $arrRoles,
        ]);
    }

    public function actionGenerateYeekee()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $today = date('Y-m-d');

        $running = Running::find()->where([
            'game_id' => Constants::YEEKEE
        ])->one();

        $configGame = ConfigGenerateGame::find()->where([
            'game_id' => Constants::YEEKEE
        ])->one();


        if (!empty($configGame) && !empty($running)) {
            $result = true;
            $running->running = $running->running + 1;
            $startAt = $today . ' ' . $configGame->start_time;
            //check duplicate yeekii
            $yeekee = Yeekee::find()->where([
                'start_at' => $startAt,
                'status' => Constants::status_active
            ])->one();

            if (empty($yeekee)) {
                for ($i = 0; $i < $configGame->amount_of_round; $i++) {
                    $round = $i + 1;
                    $addFinishTime = $configGame->sec_per_round * $round;
                    $finishAt = date('Y-m-d H:i:s', strtotime($startAt) + $addFinishTime);

                    $yeekee = new Yeekee();
                    $yeekee->group = $running->running;
                    $yeekee->date_at = $today;
                    $yeekee->round = $round;
                    $yeekee->start_at = $startAt;
                    $yeekee->finish_at = $finishAt;
                    $yeekee->status = Constants::status_active;
                    $yeekee->create_at = date('Y-m-d H:i:s');
                    $yeekee->create_by = 1;
                    if (!$yeekee->save()) {
                        $result = false;
                        echo 'generate fail';
                        exit;

                    }
                }

                $running->update_at = date('Y-m-d H:i:s');
                $running->update_by = 1;
                if (!$running->save()) {
                    $result = false;
                }
            } else {
                $result = false;
            }
        }
        /*
        if($result){
            Yii::$app->session->setFlash('success', "Gennerate Successfully");
        }else{
            Yii::$app->session->setFlash('warning', "Gennerate Warning");
        }
        return $this->redirect(['admin/index']);
       */
        return ['message' => 'success'];
    }

    public function actionGenerateBlackred()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $identity = \Yii::$app->user->getIdentity();
        $modelAuthRoles = new AuthRoles();
        $arrRoles = $modelAuthRoles->_getRoles($identity->auth_roles_id);
        if (!in_array('create-game-blackred', $arrRoles)) {
            throw new ServerErrorHttpException('คุณไม่มีสิทธิ์ในการสร้างเกมดำแดง');
        }
        $today = date('Y-m-d');

        $running = Running::find()->where([
            'game_id' => Constants::BLACKRED
        ])->one();

        $configGame = ConfigGenerateGame::find()->where([
            'game_id' => Constants::BLACKRED
        ])->one();


        if (!empty($configGame) && !empty($running)) {
            $result = true;
            $running->running = $running->running + 1;
            $startAt = $today . ' ' . $configGame->start_time;
            //check duplicate black-red
            $blackred = BlackRed::find()->where([
                'start_at' => $startAt,
                'status' => Constants::status_active
            ])->one();

            if (empty($blackred)) {
                for ($i = 0; $i < $configGame->amount_of_round; $i++) {
                    $round = $i + 1;
                    $addFinishTime = $configGame->sec_per_round * $round;
                    $finishAt = date('Y-m-d H:i:s', strtotime($startAt) + $addFinishTime);

                    $blackred = new BlackRed();
                    $blackred->group = $running->running;
                    $blackred->date_at = $today;
                    $blackred->round = $round;
                    $blackred->start_at = $startAt;
                    $blackred->finish_at = $finishAt;
                    $blackred->status = Constants::status_active;
                    $blackred->create_at = date('Y-m-d H:i:s');
                    $blackred->create_by = 1;
                    if (!$blackred->save()) {
                        $result = false;
                        echo 'generate fail';
                        exit;

                    }
                }

                $running->update_at = date('Y-m-d H:i:s');
                $running->update_by = 1;
                if (!$running->save()) {
                    $result = false;
                }
            } else {
                $result = false;
            }
        }

        if ($result) {
            //Yii::$app->session->setFlash('success', "Gennerate Successfully");
        } else {
            //Yii::$app->session->setFlash('warning', "Gennerate Warning");
        }
        return ['message' => 'success'];
    }

    public function actionWatchYeekee()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $summaryResult = [];
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $watchYeekee = Yeekee::find()->where(['in', 'status', [Constants::status_active]])
            ->orderBy(['finish_at' => SORT_ASC])->one();
        if (!empty($watchYeekee)) {
            //หาตัวเลขผลลัพธ์ ในรอบนี้
            //หา การยิงในรอบนี้
            //*หาโดยใช้ config benefit (bot)

            //หา การยิงในรอบนี้
            $yeekeePost = YeekeePost::find()->where(['yeekee_id' => $watchYeekee->id])
                ->orderBy(['id' => SORT_DESC])
                ->all();

            //หารายการโพยทั้งหมด ในรอบนี้
            $yeekeeChitAll = YeekeeChitSearch::find()->where(['yeekee_id' => $watchYeekee->id])->all();

            //ออกผล
            //get config benifit
            $setBenefit = SettingBenefit::findOne(['game_id' => Constants::YEEKEE, 'status' => Constants::status_active]);
            $estimate_income_config = $setBenefit->value;

            $sumYeekeePost = 0;
            foreach ($yeekeePost as $post) {
                $sumYeekeePost += (1 * $post->post_num);
            }

            //user 1st
            $user_1 = isset($yeekeePost[0]) ? $yeekeePost[0] : new YeekeePost();

            //user 16th
            $user_16 = isset($yeekeePost[15]) ? $yeekeePost[15] : new YeekeePost();

            $resultYeekee = $sumYeekeePost - $user_16->post_num;

            //ดูทุกโพยว่า มีใครซื้อมาและถูกเท่าไหร่
            $income = 0;
            $outlay = 0;
            $summaryResult = [];
            $sumPlayType = [];
            $sumPlayType['user-win'] = [];
            $sumPlayType['user-lost'] = [];
            $sumPlayType['play-all'] = [];
            $sumCreditType = [];
            $find_low_cost = [];

            foreach ($yeekeeChitAll as $yeekeeChit) {
                $detailInChit = $yeekeeChit->yeekeeChitDetails;
                foreach ($detailInChit as $detail) {
                    $detailSearch = YeekeeChitDetailSearch::findOne(['id' => $detail->id]);
                    $income += $detailSearch->amount;
                    $pay = YeekeeChitDetailSearch::getWinCreditStatic($resultYeekee, $detailSearch->play_type_code, $detailSearch->number, $detailSearch->amount);
                    $outlay += $pay;

                    if ($pay > 0) {    //มีการชนะ
                        if (!isset($sumPlayType['user-win'][$detailSearch->play_type_code][$detailSearch->number])) {
                            $sumPlayType['user-win'][$detailSearch->play_type_code][$detailSearch->number] = 0;
                        }
                        $sumPlayType['user-win'][$detailSearch->play_type_code][$detailSearch->number] += $detailSearch->amount;

                        if (!isset($sumCreditType[$detailSearch->play_type_code][$detailSearch->number])) {
                            $sumCreditType[$detailSearch->play_type_code][$detailSearch->number] = 0;
                        }
                        $sumCreditType[$detailSearch->play_type_code][$detailSearch->number] += YeekeeChitDetailSearch::getWinCreditStatic($resultYeekee, $detailSearch->play_type_code, $detailSearch->number, $detailSearch->amount);
                    } else {
                        if (!isset($sumPlayType['user-lost'][$detailSearch->play_type_code][$detailSearch->number])) {
                            $sumPlayType['user-lost'][$detailSearch->play_type_code][$detailSearch->number] = 0;
                        }
                        $sumPlayType['user-lost'][$detailSearch->play_type_code][$detailSearch->number] += $detailSearch->amount;
                    }
                    if (!isset($sumPlayType['play-all'][$detailSearch->play_type_code][$detailSearch->number])) {
                        $sumPlayType['play-all'][$detailSearch->play_type_code][$detailSearch->number] = 0;
                    }
                    $sumPlayType['play-all'][$detailSearch->play_type_code][$detailSearch->number] += $detailSearch->amount;


                    //หาค่ารวมที่บอกว่า ถ้าออกเลขนี้ต้องจ่ายเท่าไหร่
                    if (!isset($find_low_cost[$detailSearch->play_type_code][$detailSearch->number])) {
                        $find_low_cost[$detailSearch->play_type_code][$detailSearch->number] = 0;
                    }
                    $find_low_cost[$detailSearch->play_type_code][$detailSearch->number] += YeekeeChitDetailSearch::getCreditIfWin($detailSearch->play_type_code, $detailSearch->amount);
                }

            }

            $group_low_cost = [];
            $compare_low_cost = [];
            foreach ($find_low_cost as $type => $arrNum) {
                foreach ($arrNum as $num => $price) {
                    if (empty($compare_low_cost[$type])) {
                        $compare_low_cost[$type] = $price;
                        $group_low_cost[$type] = [$num => $price];
                    }
                    if ($price < $compare_low_cost[$type]) {
                        $compare_low_cost[$type] = $price;
                        $group_low_cost[$type] = [$num => $price];
                    }
                }
            }
            $resultNumber = YeekeeSearch::getResultsIsLowCost($yeekeeChitAll);

            $estimate_income_credit = $income * ($estimate_income_config / 100);

            $summaryResult['yeekee_id'] = $watchYeekee->id;
            $summaryResult['income'] = $income;
            $summaryResult['outlay'] = $outlay;
            $summaryResult['income_config'] = $estimate_income_config . '%';
            $summaryResult['find_output_number'] = $resultNumber;
            $summaryResult['post'] = [
                'post_sum' => $sumYeekeePost,
                'post_result' => $resultYeekee,
                'post_1' => [
                    'post_id' => $user_1->id,
                    'user_username' => $user_1->username,
                    'user_post_name' => $user_1->post_name,
                    'post_number' => $user_1->post_num,
                ],
                'post_16' => [
                    'post_id' => $user_16->id,
                    'user_username' => $user_16->username,
                    'user_post_name' => $user_16->post_name,
                    'post_number' => $user_16->post_num,
                ],
            ];
            $summaryResult['group_low_cost'] = $group_low_cost;
            $summaryResult['play_credit'] = $sumCreditType;
            $summaryResult['play'] = $sumPlayType;

        }

        return ['message' => 'success'];
    }

    //ยิงเลข auto
    public function actionGenYeekeePost()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $user_id = Constants::user_system_id;
        $watchYeekee = YeekeeSearch::find()->where(['in', 'status', [Constants::status_active, Constants::status_processing]])
            ->orderBy(['finish_at' => SORT_ASC])->one();

        if (empty($watchYeekee)) {
            return false;
        }

        if (!in_array($watchYeekee->status, [Constants::status_active, Constants::status_processing])) {
            return false;
        }

        $post_amount = \Yii::$app->request->get('post_amount') * 1;

        if (empty($post_amount) || $post_amount <= 0 || $post_amount > 1000) {
            $post_amount = 1;
        }
        $alians = Alian::find()->select(['id', 'alian_name'])->where(['status' => Constants::status_active])->all();

        $max_rand = count($alians) - 1;
        for ($i = 0; $i < $post_amount; $i++) {
            $rand_alian = rand(0, $max_rand);
            $alian = $alians[$rand_alian];
            $alian->use_count = $alian->use_count + 1;
            $alian->save();

            $post_num = rand(1, 99999);
            if (empty($alian)) {
                return false;
            }

            $post_name = $alian->alian_name;
            if (!empty($post_name)) {
                $model = new YeekeePost ();
                $model->yeekee_id = $watchYeekee->id;
                $model->post_num = $post_num;
                $model->create_at = date('Y-m-d H:i:s');
                $model->create_by = $user_id;
                $model->username = 'system';
                $model->post_name = $post_name;
                $model->is_bot = 1;
                $model->order = time();
                $result = $model->save();

                $results[] = [
                    'result' => $result,
                    'post_name' => $post_name,
                    'post_num' => $post_num,
                    'error' => $model->getErrors(),
                    'yeekee' => [
                        'id' => $watchYeekee->id,
                        'round' => $watchYeekee->round,
                        'finish_at' => $watchYeekee->finish_at
                    ]
                ];
            }

        }
        return ['message' => 'success'];
    }
}
