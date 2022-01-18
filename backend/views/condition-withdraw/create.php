<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ConditionWithdraw */

$this->title = 'Create Condition Withdraw';
$this->params['breadcrumbs'][] = ['label' => 'Condition Withdraws', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="widget-box">
    <div class="widget-title bg_lg">
        <span class="icon"><i class="icon-star"></i></span>
        <h5><?= $this->title ?></h5>
    </div>
    <div class="widget-content">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    </div>
</div>
