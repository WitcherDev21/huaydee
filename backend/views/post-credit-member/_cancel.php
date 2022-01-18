<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
$this->title = 'ยกเลิกรายการฝากถอนสมาชิก';
?>
<div class="widget-box">
    <div class="widget-title bg_lg">
        <span class="icon"><i class="icon-star"></i></span>
        <h5><?= $this->title ?></h5>
    </div>
    <div class="widget-content">
        <?php $form = ActiveForm::begin([
            'action' => Url::to(['post-credit-member/updatestatus', 'id' => $postCreditTransetion->id, 'type' => 'cancel', 'active' => 'Current']),
        ]); ?>

        <?= $form->field($postCreditTransetion, 'remark')->textInput(['maxlength' => true]) ?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
            <?= Html::a('ยกเลิก', ['index'], ['class' => 'btn btn-danger']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
