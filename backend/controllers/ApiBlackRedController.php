<?php

namespace backend\controllers;

use common\libs\Constants;
use common\models\BlackRed;
use common\models\BotBlackRedFixResult;
use common\models\ConfigGenerateGame;
use common\models\Games;
use common\models\Running;
use Yii;
use yii\rest\Controller;


/**
 * Created by PhpStorm.
 * User: topte
 * Date: 11/5/2018
 * Time: 9:01 PM
 */
class ApiBlackRedController extends Controller
{

    public function actionFixResult($authKey)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $isGame = Games::find()->where(['id' => \common\libs\Constants::BLACKRED, 'gameAuthKey' => $authKey])->count();
        if (!$isGame) {
            return ['message' => Yii::t('app', 'Auth Game Not Found')];
        }
        for ($round = 1; $round <= 654; $round++) {
            $model = new BotBlackRedFixResult();
            $model->play_type_code = rand(1, 2);
            $model->round = $round;
            $model->date = date('Y-m-d');
            if (!$model->save()) {
                $roundWrong[] = $round;
            }
        }
        if (isset($roundWrong)) {
            return ['message' => 'Can not save bot fix black red result round ' . implode(',', $roundWrong)];
        }
        return ['message' => Yii::t('app', 'success')];
    }

    public function actionCreate($authKey)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $isGame = Games::find()->where(['id' => \common\libs\Constants::BLACKRED, 'gameAuthKey' => $authKey])->count();
        if (!$isGame) {
            return ['message' => Yii::t('app', 'Auth Game Not Found')];
        }
        $today = date('Y-m-d');
        $running = Running::find()->where([
            'game_id' => Constants::BLACKRED
        ])->one();
        $isBlackred = BlackRed::find()->where(['date_at' => $today])->count();
        if ($isBlackred) {
            return ['message' => 'Can not create game blackred because game blackred duplicate'];
        }
        $configGame = ConfigGenerateGame::find()->where([
            'game_id' => Constants::BLACKRED
        ])->one();


        if (!empty($configGame) && !empty($running)) {
            $result = true;
            $running->running = $running->running + 1;
            $startAt = $today . ' ' . $configGame->start_time;

            $transaction = Yii::$app->db->beginTransaction();
            try {
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
                        $transaction->rollBack();
                        return ['message' => 'Can not create game blackred'];
                    }

                }
                $running->update_at = date('Y-m-d H:i:s');
                $running->update_by = 1;
                if (!$running->save()) {
                    return ['message' => 'Can not update running'];
                }
                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            }
        }

        return ['message' => 'Success'];
    }
}