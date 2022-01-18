<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\LotteryGameSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Lottery Games';
$this->params['breadcrumbs'][] = $this->title;
?>
<p>
    <?= Html::a(Yii::t('app', 'สร้างเกมหวยรัฐบาล'), ['/lottery-game/create'], ['class' => 'btn btn-success']) ?>
</p>
<div class="widget-box">
    <div class="widget-title bg_lg">
        <span class="icon"><i class="icon-star"></i></span>
        <h5>เกมหวยรัฐบาลในระบบ</h5>
    </div>
    <div class="widget-content">
        <div class="table-responsive">
            <?php Pjax::begin(['id' => 'bank-owner']) ?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    [
                        'label' => 'เกม',
                        'attribute' => 'gameId',
                        'value' => 'game.title',
                        'filter' => $games,
                    ],
                    'title',
                    [
                        'attribute' => 'startDate',
                        'filterInputOptions' => [
                            'class'       => 'form-control',
                            'placeholder' => date('Y-m-d'),
                            'type' => 'date',
                        ]
                    ],
                    [
                        'attribute' => 'endDate',
                        'filterInputOptions' => [
                            'class'       => 'form-control',
                            'placeholder' => date('Y-m-d'),
                            'type' => 'date',
                        ]
                    ],
                    [
                        'label' => 'username',
                        'value' => function ($model) {
                            return $model->user->username;
                        }
                    ],
                    [
                        'label' => 'สถานะ',
                        'format' => 'html',
                        'value' => function ($model) {
                            if ($model->status === \common\libs\Constants::status_finish_show_result) {
                                $textStatus = '<span style="color: red">ออกผล</span>';
                            } else {
                                $textStatus = '<span style="color: blue">ยังไม่ออกผล</span>';
                            }
                            return $textStatus;
                        }
                    ],
                    [
                        'label' => 'สถานะเปิด/ปิด',
                        'format' => 'html',
                        'value' => function ($model) {
                            if ($model->status === 1) {
                                $textStatus = '<span style="color: green">'.Yii::t('app', 'Open').'</span>';
                            } else if($model->status === 0) {
                                $textStatus = '<span style="color: blue">'.Yii::t('app', 'Close').'</span>';
                            }else {
                                $textStatus = 'ไม่ได้ระบุ';
                            }
                            return $textStatus;
                        }
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'buttonOptions' => ['class' => 'btn btn-default'],
                        'template' => '<div class="btn-group btn-group-sm text-center" role="group">{update}{answer}{view}{copy}{cancel}</div>',
                        'visibleButtons' => [
                            'answer' => function () use ($arrRoles) {
                                return in_array('answer-lottery-game', $arrRoles);
                            },
                            'view' => function () use ($arrRoles) {
                                return in_array('lottery-game', $arrRoles);
                            },
                            'update' => function () use ($arrRoles) {
                                return in_array('update-lottery-game', $arrRoles);
                            },
                            'cancel' => function ($model) use ($arrRoles) {
                                $isArrRoles = in_array('cancel-lottery-game', $arrRoles);
                                if ($isArrRoles && $model->status === 1) {
                                    return true;
                                }
                                return false;
                            },
                        ],
                        'buttons'=>[
                            'answer' => function($url,$model){
                                return Html::a('ออกผล', [
                                    'lottery-answer-game/create',
                                    'id' => $model->id
                                ],['class'=>'btn btn-default']);
                            },
                            'view' => function($url,$model){
                                return Html::a('<i class="glyphicon glyphicon-eye-open"></i>', [
                                    'lottery-game/view',
                                    'id' => $model->id,
                                ],
                                    [
                                        'class'=>'btn btn-default',
                                    ]
                                );
                            },
                            'cancel' => function($url, $model) {
                                return Html::a('Cancel', [
                                    'lottery-game/cancel',
                                    'id' => $model->id,
                                ],
                                    [
                                        'class'=>'btn btn-default',
                                        'data' => [
                                            'confirm' => Yii::t('app', 'Are you sure you want to cancel this item ?'),
                                            'method' => 'post',
                                        ],
                                    ]
                                );
                            },
                        ]
                    ]
                ],
            ]); ?>
            <?php Pjax::end() ?>
        </div>
    </div>
</div>
