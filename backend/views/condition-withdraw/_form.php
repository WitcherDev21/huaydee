<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ConditionWithdraw */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="condition-withdraw-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'percent')->textInput() ?>

    <?= $form->field($model, 'status')->radioList(['1' => 'เปิดใช้งาน', '0' => 'ปิดใช้งาน']) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
