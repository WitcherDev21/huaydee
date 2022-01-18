<?php

namespace frontend\controllers;

use common\models\Credit;
use yii\filters\AccessControl;
use yii\web\Controller;


/**
 * Site controller
 */
class CreditController extends Controller
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
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionGetCreditBalance()
    {
        $user_id = \Yii::$app->user->id;
        $credit = Credit::findOne(['user_id' => $user_id]);
        if (!$credit) {
            return 0;
        }
        return $credit->balance;
    }
}
