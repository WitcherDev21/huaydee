<?php

namespace frontend\component;

use yii\base\Widget;
use common\models\Credit;

class Header extends Widget {
	public function run()
    {
        $credit = 0;
        $model = Credit::find()->select(['balance'])->where(['user_id' => \Yii::$app->user->id])->one();

        if (!empty($model)) {
            $credit = $model->balance;
        }
        echo $this->render('header', [
            'credit' => $credit,
        ]);
    }
}

