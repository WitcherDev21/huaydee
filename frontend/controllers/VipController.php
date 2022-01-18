<?php
namespace frontend\controllers;

use backend\models\Vip;
use common\libs\Constants;
use common\models\CreditTransection;
use common\models\YeekeeChit;
use yii\filters\AccessControl;
use yii\web\Controller;

/**
 * Created by PhpStorm.
 * User: topte
 * Date: 7/27/2018
 * Time: 12:07 AM
 */

class VipController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['*'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $creditTransactionAmount = YeekeeChit::find()->where([
            'user_id' => \Yii::$app->user->id,
        ])->andWhere(['status' => 9])->sum('total_amount');
        $vips = Vip::find()->orderBy('point ASC')->all();
        $vip = Constants::getVip();
       return $this->render('index',[
           'vips' => $vips,
           'creditTransactionAmount' => $creditTransactionAmount,
           'vip' => $vip
       ]);
    }
}