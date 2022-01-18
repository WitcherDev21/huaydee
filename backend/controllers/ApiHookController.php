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
class ApiHookController extends Controller
{

    public function actionLine()
    {


        /*Get Data From POST Http Request*/
        $datas = file_get_contents('php://input');
        /*Decode Json From LINE Data Body*/
        $deCode = json_decode($datas,true);

        file_put_contents(Yii::getAlias('@backend/logs/line/log.txt'), file_get_contents('php://input') . PHP_EOL, FILE_APPEND);

        $replyToken = $deCode['events'][0]['replyToken'];

        $messages = [];
        $messages['replyToken'] = $replyToken;
        if ($deCode['events'][0]['message']['text'] == 'ขอทราบผล') {
            $messages['messages'][0] = $this->getFormatTextMessage('ผลรางวัลรอบที่ ');
        }else{
            $messages['messages'][0] = $this->getFormatTextMessage('ไม่มีข้อมูล ');
        }

        $encodeJson = json_encode($messages);

        $LINEDatas['url'] = "https://api.line.me/v2/bot/message/reply";
        $LINEDatas['token'] = "CNRzhlB1gkovGfQzex3UJhokrRkTmHaAVhSdrom1Df4s+xffOXRwbs2PhSrzAmgvbeoFQ1bSTnoMEZns1lYdvbMVfmszZJD4JDokutrwliUuxjKl4slYYZQ4FVfNz+wYqiWKmRRqsxpXGNIpjBfG/AdB04t89/1O/w1cDnyilFU=";
        $results = $this->sentMessage($encodeJson,$LINEDatas);

        /*Return HTTP Request 200*/
        http_response_code(200);
    }

    public function getFormatTextMessage($text)
    {
        $datas = [];
        $datas['type'] = 'text';
        $datas['text'] = $text;

        return $datas;
    }

    public function sentMessage($encodeJson,$datas)
    {
        $datasReturn = [];
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $datas['url'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $encodeJson,
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer ".$datas['token'],
                "cache-control: no-cache",
                "content-type: application/json; charset=UTF-8",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            $datasReturn['result'] = 'E';
            $datasReturn['message'] = $err;
        } else {
            if($response == "{}"){
                $datasReturn['result'] = 'S';
                $datasReturn['message'] = 'Success';
            }else{
                $datasReturn['result'] = 'E';
                $datasReturn['message'] = $response;
            }
        }

        return $datasReturn;
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