<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\PlayType */
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

            <?= $form->field($model, 'code')->dropDownList($model->getCode(), ['prompt' => 'กรุณาเลือกรหัส']) ?>

            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'group_id')->dropDownList($titlePlayTypeGroups, ['prompt' => 'กรุณาเลือกกลุ่ม']) ?>

            <?= $form->field($model, 'game_id')->dropDownList($titleGames, ['prompt' => 'กรุณาเลือกเกม']) ?>


            <?= $form->field($model, 'jackpot_per_unit')->textInput() ?>

            <?= $form->field($model, 'minimum_play')->textInput() ?>

            <?= $form->field($model, 'maximum_play')->textInput() ?>

            <?= $form->field($model, 'limitByUser')->textInput() ?>

            <?= $form->field($model, 'sort')->textInput() ?>


            <div class="form-group">
                <?= Html::submitButton('บันทึก', ['class' => 'btn btn-success']) ?>
                <?= Html::a('ยกเลิก', ['index'], ['class' => 'btn btn-danger']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>