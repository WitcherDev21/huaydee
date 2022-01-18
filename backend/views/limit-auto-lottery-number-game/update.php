<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\LimitAutoLotteryNumberGame */

$this->title = 'Update Limit Auto Lottery Number Game: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Limit Auto Lottery Number Games', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="widget-box">
    <div class="widget-title bg_lg">
        <span class="icon"><i class="icon-star"></i></span>
        <h5><?= $this->title ?></h5>
    </div>
    <div class="widget-content">
        <?= $this->render('_form', [
            'model' => $model,
            'playTypes' => $playTypes,
        ]) ?>
    </div>
</div>
