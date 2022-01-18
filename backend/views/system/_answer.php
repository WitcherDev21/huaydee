<?php
/**
 * Created by PhpStorm.
 * User: topte
 * Date: 11/22/2018
 * Time: 9:23 PM
 */

use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>
<div class="widget-box">
    <div class="widget-title bg_lg">
        <span class="icon"><i class="icon-star"></i></span>
        <h5>เฉลยเกมยี่กี่รอบที่ <?= $yeekee->round ?></h5>
    </div>
    <div class="widget-content">
        <?php $form = ActiveForm::begin([
            'id' => 'answer',
            'options' => [
                'class' => 'result'
            ]
        ]); ?>

        <?= $form->field($model, 'number')->textInput([
            'value' => isset($model->number) ? str_pad($model->number, 5, '0', STR_PAD_LEFT) : ''
        ])->label(Yii::t('app', 'Reuslt')) ?>


        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success', 'id' => 'confirm',]) ?>
            <?= Html::a('ยกเลิก', ['manageresult'], ['class' => 'btn btn-danger']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
