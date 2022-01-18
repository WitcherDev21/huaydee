<?php

use kartik\datetime\DateTimePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model common\models\ThaiSharedValueAdded */
/* @var $form yii\widgets\ActiveForm */
/* @var $playTypesObj \common\models\PlayType */
?>

<div class="thai-shared-value-added-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'playTypeId')->dropDownList($playTypesObj, ['placeholder' => 'กรุณาเลือกประเภทหวยหุ้น']); ?>

    <?= $form->field($model, 'number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'amount')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'createdAt')->widget(DateTimePicker::classname(), [
        'options' => ['autocomplete' => 'off', 'placeholder' => 'Select Start Datetime'],
        'pluginOptions' => [
            'autoclose' => true
        ]
    ]);?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
