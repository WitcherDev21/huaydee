<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\DiscountGame */

$this->title = $model->playType->game->title. '-' .$model->playType->title;
$this->params['breadcrumbs'][] = ['label' => 'Discount Games', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<p>
    <?= Html::a('กลับสู่หน้า discount', ['setting/index/discount_game'], ['class' => 'btn btn-danger']) ?>
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
               'label' => 'เกม-ประเภท',
               'value' => function ($model) {
                    return $model->playType->game->title. '-' . $model->playType->title;
               }
            ],
            'title',
            'discount',
            [
                'label' => 'สถานะ',
                'value' => function ($model) {
                    return $model->status === 1 ? 'เปิด' : 'ปิด';
                }
            ],
            'createdAt',
            'updatedAt',
        ],
    ]) ?>

</div>
