<?php

use common\libs\Constants;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \common\models\ThaiSharedAnswerGameForm */
/* @var $form yii\widgets\ActiveForm */
/* @var $thaiSharedGame \common\models\ThaiSharedGame */
?>

<?php $form = ActiveForm::begin([
    'action' => ['preview-answer', 'id' => $thaiSharedGame->id],
    'method' => 'get',
    'options' => ['data-pjax' => true]
]);
if ($thaiSharedGame->gameId !== Constants::LOTTERYLAODISCOUNTGAME && $thaiSharedGame->gameId !== Constants::LOTTERYLAOGAME) { ?>
    <div class="col-md-6">
        <?php
        echo $form->field($model, 'three_top')->textInput([
            'maxlength' => 3,
            'value' => Yii::$app->request->get('ThaiSharedAnswerGameForm')['three_top']
        ]);
        ?>
    </div>
    <div class="col-md-6">
        <?php
        echo $form->field($model, 'two_under')->textInput([
            'maxlength' => 2,
            'value' => Yii::$app->request->get('ThaiSharedAnswerGameForm')['two_under']
        ]); ?>
    </div>
    <?php
    if ($thaiSharedGame->gameId === Constants::LOTTERYGAME || $thaiSharedGame->gameId === Constants::LOTTERYGAMEDISCOUNT) {
        ?>
        <div class="col-md-6">
            <?php
            echo $form->field($model, 'three_top2_1')->textInput([
                'maxlength' => 3,
                'value' => Yii::$app->request->get('ThaiSharedAnswerGameForm')['three_top2_1']
            ]);
            ?>
        </div>
        <div class="col-md-6">
            <?php
            echo $form->field($model, 'three_top2_2')->textInput([
                'maxlength' => 3,
                'value' => Yii::$app->request->get('ThaiSharedAnswerGameForm')['three_top2_2']
            ]);
            ?>
        </div>
        <div class="col-md-6">
            <?php
            echo $form->field($model, 'three_under2_1')->textInput([
                'maxlength' => 3,
                'value' => Yii::$app->request->get('ThaiSharedAnswerGameForm')['three_under2_1']
            ]);
            ?>
        </div>
        <div class="col-md-6">
            <?php
            echo $form->field($model, 'three_under2_2')->textInput([
                'maxlength' => 3,
                'value' => Yii::$app->request->get('ThaiSharedAnswerGameForm')['three_under2_2']
            ]);
            ?>
        </div>
        <?php
    }
    ?>
    <?php
} else {
    ?>
<div class="col-md-12">
    <?php
    echo $form->field($model, 'four_dt')->textInput([
        'maxlength' => 4,
        'value' => Yii::$app->request->get('ThaiSharedAnswerGameForm')['four_dt']
    ]);
    }
    ?>
</div>
<span class="input-group-btn">
        <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i> ค้นหา</button>
      </span>
<?php ActiveForm::end(); ?>
