<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\LotteryGame */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Lottery Games', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<p>
    <?= Html::a('กลับสู่หน้า lottery game', ['index'], ['class' => 'btn btn-danger']) ?>
</p>
<div class="widget-box">
    <div class="widget-title bg_lg">
        <span class="icon"><i class="icon-star"></i></span>
        <h5><?= $this->title ?></h5>
    </div>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'game',
                'value' => function ($model) {
                    return $model->game->title;
                }
            ],
            'title',
            'startDate',
            'endDate',
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    return $model->status ? 'เปิด' : 'ปิด';
                }
            ],
            'createdAt',
            'updateAt',
        ],
    ]) ?>

</div>
