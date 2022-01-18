<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\LimitLotteryNumberGame */
/* @var $playTypes array*/
/* @var $maxLengths array */

$this->title = 'Update Limit Lottery Number Game: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Limit Lottery Number Games', 'url' => ['index']];
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
        'maxLengths' => $maxLengths,
        'thaiSharedGame' => $thaiSharedGame,
    ]) ?>
    </div>
</div>
