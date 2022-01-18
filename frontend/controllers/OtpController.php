<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;

/**
 * Site controller
 */
class OtpController extends Controller
{
    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://portal-otp.smsmkt.com/api/otp-send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "api_key:".Yii::$app->params['api_key'],
                "secret_key:".Yii::$app->params['secret_key'],
            ),
            CURLOPT_POSTFIELDS =>json_encode(array(
               "project_key"=>Yii::$app->params['project_key'],
               "phone"=>\Yii::$app->request->get('phone'),
            )),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return $response;
        // {"code":1010,"detail":"Too many requests, please try again after an 10 minute","result":""}
        // {"code":"000","detail":"OK.","result":{"token":"50759117-ef68-4ca7-9a88-aa94b6dd8988","ref_code":""}}
    }
}
