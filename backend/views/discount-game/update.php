<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\DiscountGame */
/* @var $gameObjs \common\models\PlayType */

$this->title = 'Update Discount Game: ' . $model->playType->game->title. '-' .$model->playType->title;
$this->params['breadcrumbs'][] = ['label' => 'Discount Games', 'url' => ['index']];
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
            'gameObjs' => $gameObjs,
        ]) ?>
    </div>
</div>
