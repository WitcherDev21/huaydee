<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ThaiSharedValueAdded */
/* @var $playTypesObj \common\models\PlayType */

$this->title = 'Create Thai Shared Value Added';
$this->params['breadcrumbs'][] = ['label' => 'Thai Shared Value Addeds', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="widget-box">
    <div class="widget-title bg_lg">
        <span class="icon"><i class="icon-star"></i></span>
        <h5><?= $this->title ?></h5>
    </div>
    <div class="widget-content">
    <?= $this->render('_form', [
        'model' => $model,
        'playTypesObj' => $playTypesObj,
    ]) ?>
    </div>
</div>
