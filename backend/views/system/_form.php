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
        <h5>อับเดตสถานะเกมยี่กี่รอบที่ <?= $model->round ?></h5>
    </div>
    <div class="widget-content">
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'status')->radioList([
            \common\libs\Constants::status_active => Yii::t('app', 'Open'),
            \common\libs\Constants::status_inactive => Yii::t('app', 'Close'),
        ]) ?>

        <?php if (in_array('open-bot', $arrRoles)) {
            echo $form->field($model, 'isOpenBot')->radioList([
                \common\libs\Constants::status_active => Yii::t('app', 'Open'),
                \common\libs\Constants::status_inactive => Yii::t('app', 'Close'),
            ]);
        } ?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
            <?= Html::a('ยกเลิก', ['manageresult'], ['class' => 'btn btn-danger']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
