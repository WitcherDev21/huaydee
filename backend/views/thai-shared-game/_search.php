<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\ThaiSharedGame;

/* @var $this yii\web\View */
/* @var $model common\models\ThaiSharedGameChitSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <div class="col-md-9 col-md-offset-3">

        <?php $form = ActiveForm::begin([
            'action' => [Yii::$app->controller->action->id],
            'method' => 'get',
        ]); ?>
        <div class="col-md-7 col-sm-7">
            <?= Html::activeDropDownList($searchModel, 'title', ThaiSharedGame::getTitles(), [
                'prompt' => 'Select...',
                'class' => 'form-control',
                'style' => 'margin-top: 10px; margin-bottom: 15px;'
            ]) ?>
        </div>

        <div class="col-md-7 col-sm-7">
            <?= Html::activeInput('date', $searchModel, 'createdAt', ['class' => 'form-control', 'placeholder' => date('Y-m-d')]) ?>
        </div>
        <div class="form-group">
            <div class="col-md-2" style="margin-top: 20px;">
                <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

</div>
