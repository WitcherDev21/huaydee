<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ConditionWithdrawSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Condition Withdraws';
$this->params['breadcrumbs'][] = $this->title;
?>
<p>
    <?= Html::a(Yii::t('app', 'สร้างเงื่อนไขการถอน'), ['/condition-withdraw/create'], ['class' => 'btn btn-success']) ?>
</p>
<div class="widget-box">
    <div class="widget-title bg_lg">
        <span class="icon"><i class="icon-star"></i></span>
        <h5>เงื่อนไขการถอน</h5>
    </div>
    <div class="widget-content">
        <div class="table-responsive">

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    'percent',
                    [
                        'attribute' => 'status',
                        'value' => function ($model) {
                            return $model->status === 1 ? 'เปิดใช้งาน' : 'ปิดใช้งาน';
                        }
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'buttonOptions' => ['class' => 'btn btn-default'],
                        'template' => '<div class="btn-group btn-group-sm text-center" role="group">{update}</div>',
                    ]
                ],
            ]); ?>
        </div>
    </div>
</div>
