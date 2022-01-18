<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\LimitAutoLotteryNumberGame */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="limit-auto-lottery-number-game-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'minimumRate')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'maximumRate')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'playTypeId')->dropDownList($playTypes, ['prompt' => 'Select Play Type']) ?>

    <?= $form->field($model, 'jackPotPerUnit')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
