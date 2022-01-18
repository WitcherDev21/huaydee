<?php

use yii\widgets\DetailView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Games */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'เกมในระบบ'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<p>
    <?= Html::a('กลับสู่หน้า game', ['index'], ['class' => 'btn btn-danger']) ?>
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
                'format' => 'raw',
                'attribute' => 'image',
                'value' => Html::img($model->photoViewer, ['class'=>'img-thumbnail', 'style'=>'width:200px;'])
            ],
            'title',
            'description',
            'uri',
            'rule:html',
            'period_des',
            'create_at',
            'update_at',
        ],
    ]) ?>
</div>
