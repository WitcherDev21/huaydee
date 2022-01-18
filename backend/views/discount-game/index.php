<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\DiscountGameSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $gameObjs \common\models\PlayType */

$this->title = 'Discount Games';
$this->params['breadcrumbs'][] = $this->title;
?>
<p>
    <?= Html::a(Yii::t('app', 'สร้างส่วนลด'), ['/discount-game/create'], ['class' => 'btn btn-success']) ?>
</p>
<div class="widget-box">
    <div class="widget-title bg_lg">
        <span class="icon"><i class="icon-star"></i></span>
        <h5>ส่วนลดในระบบ</h5>
    </div>
    <div class="widget-content">
        <div class="table-responsive">
            <?php Pjax::begin(['id' => 'discount-game']) ?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    [
                        'attribute' => 'playTypeId',
                        'value' => function ($model) {
                            return $model->playType->game->title . '-' . $model->playType->title;
                        },
                        'filter' => $gameObjs,
                    ],
                    'title',
                    'discount',
                    'createdAt',
                    [
                        'label' => 'สถานะ',
                        'value' => function ($model) {
                            return $model->status === 1 ? 'เปิด' : 'ปิด';
                        }
                    ],

                    [
                        'class' => 'yii\grid\ActionColumn',
                        'buttonOptions' => ['class' => 'btn btn-default'],
                        'template' => '<div class="btn-group btn-group-sm text-center" role="group">{view}{update}{copy}</div>',
                        'buttons' => [
                            'view' => function ($url, $model) {
                                return Html::a('<i class="glyphicon glyphicon-eye-open"></i>', [
                                    'discount-game/view',
                                    'id' => $model->id,
                                ],
                                    [
                                        'class' => 'btn btn-default',
                                    ]
                                );
                            },
                            'update' => function ($url, $model) {
                                return Html::a('<i class="glyphicon glyphicon-pencil"></i>', [
                                    'discount-game/update',
                                    'id' => $model->id,
                                ],
                                    [
                                        'class' => 'btn btn-default',
                                    ]
                                );
                            },
                            'copy' => function($url,$model){
                                return Html::a('Copy', [
                                    'discount-game/copy',
                                    'id' => $model->id,
                                ],
                                    [
                                        'class'=>'btn btn-default',
                                    ]
                                );
                            },
                        ],
                    ],
                ],
            ]);
            Pjax::end();
            ?>
        </div>
    </div>
</div>
