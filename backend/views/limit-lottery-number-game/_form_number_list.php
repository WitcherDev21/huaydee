<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\LimitLotteryNumberGame */
/* @var $form yii\widgets\ActiveForm */
/* @var $playTypes array */
/* @var $maxLengths array s */

$js = <<<EOT
function changeValue(dropdown) {
    var length = dropdown.options[dropdown.selectedIndex].getAttribute('data-length');
    number = document.getElementById('number');
    numberTo = document.getElementById('numberTo');
    if (length) {
        number.maxLength = length;
        numberTo.maxLength = length;
    }
}
EOT;
$this->registerJs($js, \yii\web\View::POS_HEAD);
?>
<div class="widget-box">
    <div class="widget-title bg_lg">
        <span class="icon"><i class="icon-star"></i></span>
        <h5>สร้าง limit ราคาแทง / เลข</h5>
    </div>
    <div class="widget-content">
        <div class="row">
            <?php $form = ActiveForm::begin(); ?>

            <div class="col-md-12">
                <?= $form->field($model, 'playTypeId')->dropDownList($playTypes, ['prompt' => 'กรุณาเลือกประเภท', 'onchange' => 'changeValue(this);',
                    'options' => $maxLengths
                ]); ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'numberFrom')->textInput(['id' => 'number', 'maxlength' => 4]); ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'numberTo')->textInput(['id' => 'numberTo', 'maxlength' => 4]); ?>
            </div>
            <div class="col-md-12">
                <?= $form->field($model, 'jackPotPerUnit')->textInput(); ?>
            </div>
            <div class="col-md-12">
                <?= $form->field($model, 'amountLimit')->textInput(); ?>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
                    <?= Html::a('Cancel', ['index', 'thaiSharedGameId' => $thaiSharedGame->id], ['class' => 'btn btn-danger']) ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
