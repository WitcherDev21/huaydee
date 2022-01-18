<?php

namespace frontend\component;

use common\models\Credit;
use yii\base\Widget;

class SubHeader extends Widget {
    public function run()
    {
        $credit = 0;
        $model = Credit::find()->select(['balance'])->where(['user_id' => \Yii::$app->user->id])->one();

        if (!empty($model)) {
            $credit = $model->balance;
        }
        echo $this->render('sub-header', [
            'credit' => $credit,
        ]);
    }
}