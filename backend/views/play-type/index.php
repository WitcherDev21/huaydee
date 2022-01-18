<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel common\models\PlayTypeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'ประเภทการเล่น';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="play-type-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('สร้างประเภทการเล่น', ['/play-type/create'], ['class' => 'btn btn-success']) ?>
    </p>
    <div class="widget-box">
        <div class="widget-title bg_lg">
            <span class="icon"><i class="icon-star"></i></span>
            <h5>ประเภทการเล่น</h5>
        </div>
        <div class="widget-content">
            <div class="table-responsive">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        'code',
                        'title',
                        'description',
                        'jackpot_per_unit',
                        'limitByUser',
                        'minimum_play',
                        'maximum_play',
                        [
                            'attribute' => 'group_id',
                            'value' => function ($model) {
                                return $model->group->title;
                            }
                        ],
                        'sort',
                        [
                            'attribute' => 'game_id',
                            'value' => function ($model) {
                                return $model->game->title;
                            },
                            'filter' => ArrayHelper::map(\common\models\Games::find()->all(), 'id', 'title'),
                        ],

                        [
                            'class' => 'yii\grid\ActionColumn',
                            'buttonOptions' => ['class' => 'btn btn-default'],
                            'template' => '<div class="btn-group btn-group-sm text-center" role="group">{view}{update}</div>',
                            'buttons' => [
                                'view' => function ($url, $model) {
                                    return Html::a('<i class="glyphicon glyphicon-eye-open"></i>', [
                                        'play-type/view',
                                        'id' => $model->id,
                                    ],
                                        [
                                            'class' => 'btn btn-default',
                                        ]
                                    );
                                },
                                'update' => function ($url, $model) {
                                    return Html::a('<i class="glyphicon glyphicon-pencil"></i>', [
                                        'play-type/update',
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
