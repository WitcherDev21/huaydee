<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ConditionLimitNumber */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="widget-box">
    <div class="widget-title bg_lg">
        <span class="icon"><i class="icon-star"></i></span>
        <h5><?= Yii::t('app', 'Condition Limit Number') ?></h5>
    </div>
    <div class="widget-content">
        <div class="table-responsive">

            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'playTypeId')->dropDownList($playTypes, ['prompt' => 'กรุณาเลือกเกม']) ?>

            <?= $form->field($model, 'limit')->textInput(['maxlength' => true]) ?>

            <div class="form-group">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>