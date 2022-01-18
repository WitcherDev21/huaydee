<?php

namespace backend\controllers;

use common\libs\Constants;
use common\models\ConfigGenerateGame;
use common\models\Games;
use common\models\Running;
use common\models\Yeekee;
use Yii;
use yii\rest\Controller;
use common\models\YeekeePost;
use common\models\YeekeeSearch;
use yii\web\ServerErrorHttpException;
use common\models\Alian;


/**
 * Created by PhpStorm.
 * User: topte
 * Date: 11/5/2018
 * Time: 9:01 PM
 */

class ApiYeekeeController extends Controller
{

    public function actionCreate($authKey)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $isGame = Games::find()->where(['id' => Constants::YEEKEE, 'gameAuthKey' => $authKey])->count();
        if (!$isGame) {
            return ['message' => Yii::t('app', 'Not Found')];
        }
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
        return $result;
    }

    //ยิงเลข auto
    public function actionGenYeekeePost($authKey, $post_amount)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $game = Games::find()->where(['gameAuthKey' => $authKey, 'id' => Constants::YEEKEE])->one();
        if (!$game) {
            throw new ServerErrorHttpException('Not found authkey yeekee');
        }
        $user_id = Constants::user_system_id;
        $watchYeekee = YeekeeSearch::find()->where(['in', 'status', [Constants::status_active, Constants::status_processing]])
            ->orderBy(['finish_at' => SORT_ASC])->one();

        if (empty($watchYeekee)) {
            return false;
        }

        if (!in_array($watchYeekee->status, [Constants::status_active, Constants::status_processing])) {
            return false;
        }

        $post_amount = $post_amount * 1;

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
        return $results;
    }
	
	
    public function actionDeleteYeekeeOld()
    {

		$fifago = date('Y-m-d H:i:s', strtotime('-30 minutes'));
		$hourago = date('Y-m-d H:i:s', strtotime('-1 hour'));
        $yesterday = date('Y-m-d 23:59:59',strtotime("-1 days"));
        $yeekee = YeekeePost::find()
        ->where(" create_at <= '$fifago'")
		->orderBy('create_at')
        ->all();
		
		  foreach($yeekee as $delete)
		{
			$delete->delete();
		}
		  return ['message' => 'Delete Success !',];
    }
	
	   public function actionPhpInfo()
    {
		phpinfo();
    }
	
	
	
}