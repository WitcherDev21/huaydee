<?php

use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

?>
<div class="widget-box">
    <div class="widget-title bg_lg">
        <span class="icon"><i class="icon-cog"></i></span>
        <h5>แก้ไข: <?= $model->name ?></h5>
    </div>
    <div class="widget-content tab-content">
        <div class="vip-form">

            <?php
            $form = ActiveForm::begin([
                'method' => 'post',
                'action' => Url::to(['vip/update', 'id' => $model->id]),
            ]); ?>

            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'point')->textInput() ?>

            <?= $form->field($model, 'commissionThreeTop')->textInput() ?>

            <?= $form->field($model, 'commissionThreeTod')->textInput() ?>

            <?= $form->field($model, 'commissionTwoTop')->textInput() ?>

            <?= $form->field($model, 'commissionTwoTod')->textInput() ?>

            <?= $form->field($model, 'commissionRunOn')->textInput() ?>

            <?= $form->field($model, 'commissionRunUnder')->textInput() ?>
            <div class="form-group">
                <?= Html::submitButton('บันทึก', ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
