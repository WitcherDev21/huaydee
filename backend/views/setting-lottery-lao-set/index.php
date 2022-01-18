<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SettingLotteryLaoSetSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Setting Lottery Lao Sets';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="setting-lottery-lao-set-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('สร้างราคาเลขชุด', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <div class="widget-box">
        <div class="widget-title bg_lg">
            <span class="icon"><i class="icon-star"></i></span>
            <h5>ราคาเลขชุด</h5>
        </div>
        <div class="widget-content">
            <div class="table-responsive">

                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        [
                            'attribute' => 'gameId',
                            'value' => function ($model) {
                                return $model->game->title;
                            }
                        ],
                        'value',
                        'amountNumber',
                        'amountSet',

                        [
                            'class' => 'yii\grid\ActionColumn',
                            'buttonOptions' => ['class' => 'btn btn-default'],
                            'template' => '<div class="btn-group btn-group-sm text-center" role="group">{update}</div>',
                            'buttons' => [
                                'update' => function ($url, $model) {
                                    return Html::a('<i class="glyphicon glyphicon-pencil"></i>', [
                                        'setting-lottery-lao-set/update',
                                        'id' => $model->id,
                                    ],
                                        [
                                            'class' => 'btn btn-default',
                                        ]
                                    );
                                },
                            ],
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>
