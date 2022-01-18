<?php

use common\libs\Constants;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ThaiSharedAnswerGameForm */
/* @var $form yii\widgets\ActiveForm */
/* @var $thaiSharedGame \common\models\ThaiSharedGame */
?>

<div class="widget-box">
    <div class="widget-title bg_lg">
        <span class="icon"><i class="icon-star"></i></span>
        <h5><?= $thaiSharedGame->title ?> / <?= date('Y-m-d', strtotime($thaiSharedGame->startDate)) ?></h5>
    </div>
    <div class="widget-content">
        <div class="table-responsive">

            <?php $form = ActiveForm::begin();
            if ($thaiSharedGame->gameId === Constants::THAISHARED ||
                $thaiSharedGame->gameId === Constants::VIETNAMVIP ||
                $thaiSharedGame->gameId === Constants::VIETNAM4D_GAME) {
                echo $form->field($model, 'three_top')->textInput(['maxlength' => 3]);
                echo $form->field($model, 'two_under')->textInput(['maxlength' => 2]);
            } else if ($thaiSharedGame->gameId === Constants::GSB_THAISHARD_GAME ||
                $thaiSharedGame->gameId === Constants::BACC_THAISHARD_GAME ||
                $thaiSharedGame->gameId === Constants::LAOS_CHAMPASAK_LOTTERY_GAME ||
                $thaiSharedGame->gameId === Constants::LOTTERYRESERVEGAME) {
                echo $form->field($model, 'result')->textInput([]);
                echo $form->field($model, 'three_top')->textInput(['maxlength' => 3]);
                echo $form->field($model, 'two_under')->textInput(['maxlength' => 2]);
            } else if ($thaiSharedGame->gameId === Constants::LOTTERYGAME ||
                $thaiSharedGame->gameId === Constants::LOTTERYGAMEDISCOUNT) {
                echo $form->field($model, 'firstResult')->textInput(['maxlength' => 6]);
                echo $form->field($model, 'two_under')->textInput(['maxlength' => 2]);
                echo $form->field($model, 'three_top2_1')->textInput(['maxlength' => 3]);
                echo $form->field($model, 'three_top2_2')->textInput(['maxlength' => 3]);
                echo $form->field($model, 'three_under2_1')->textInput(['maxlength' => 3]);
                echo $form->field($model, 'three_under2_2')->textInput(['maxlength' => 3]);
            } else {
                echo $form->field($model, 'four_dt')->textInput(['maxlength' => 4]);
            }
            ?>

            <div class="form-group">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
                <?= Html::a('ยกเลิก', ['thai-shared/index'], ['class' => 'btn btn-danger']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>
