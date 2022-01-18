<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\SettingLotteryLaoSet */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="widget-box">
    <div class="widget-title bg_lg">
        <span class="icon"><i class="icon-star"></i></span>
        <h5>ปรเภทการเล่น</h5>
    </div>
    <div class="widget-content">
        <div class="table-responsive">

            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'gameId')->dropDownList($gameObjs, ['prompt' => 'กรุณาเลือกเกมชุด']) ?>

            <?= $form->field($model, 'value')->textInput() ?>

            <?= $form->field($model, 'amountNumber')->textInput() ?>

            <?= $form->field($model, 'amountSet')->textInput() ?>

            <div class="form-group">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>

