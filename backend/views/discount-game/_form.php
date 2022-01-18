<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\ThaiSharedGame;

/* @var $this yii\web\View */
/* @var $model common\models\DiscountGame */
/* @var $form yii\widgets\ActiveForm */
/* @var $gameObjs \common\models\PlayType */
?>

<div class="discount-game-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'playTypeId')->dropDownList($gameObjs, ['prompt' => 'กรุณาเลือกเกม - ประเภท']) ?>

    <?= $form->field($model, 'title')->dropDownList(ThaiSharedGame::getTitles(), ['prompt' => 'กรุณาเลือกหวยหุเ้น']) ?>

    <?= $form->field($model, 'discount')->textInput() ?>

    <?= $form->field($model, 'status')->radioList(['1' => 'เปิด', '0' => 'ปิด']) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
