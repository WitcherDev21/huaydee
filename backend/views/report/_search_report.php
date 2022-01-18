<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ThaiSharedGameChitSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <div class="col-md-9 col-md-offset-3">

        <?php $form = ActiveForm::begin([
            'action' => ['report/report-game'],
            'method' => 'get',
        ]); ?>

        <div class="col-md-6 col-sm-6">
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
