<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Alert;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel \common\models\ThaiSharedGameSearch */
/* @var $typeGameShareds */

$this->title = Yii::t('app', 'เกมหวยหุ้น');
$this->params['breadcrumbs'][] = $this->title;
?>
<h2><?= $this->title; ?></h2>
<?php
if(in_array('create-shared-game', $arrRoles)){ ?>
<p>
    <?= Html::a(Yii::t('app', 'สร้างเกมหวยหุ้น'), ['/thai-shared/create'], ['class' => 'btn btn-success']) ?>
</p>
<?php } ?>
<?php if(Yii::$app->session->hasFlash('alert')):?>
    <?= Alert::widget([
        'body' => ArrayHelper::getValue(Yii::$app->session->getFlash('alert'), 'body'),
        'options' => ArrayHelper::getValue(Yii::$app->session->getFlash('alert'), 'options'),
    ])?>
<?php endif; ?>
<div class="widget-box">
    <div class="widget-title bg_lg">
        <span class="icon"><i class="icon-star"></i></span>
        <h5>เกมหวยหุ้นในระบบ</h5>
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
                        'label' => 'ประเภทเกม',
                        'attribute' => 'typeSharedGameId',
                        'value' => 'typeGameShared.title',
                        'filter' => $typeGameShareds
                    ],
                    'title',
                    [
                        'attribute' => 'startDate',
                        'filterInputOptions' => [
                            'class'       => 'form-control',
                            'placeholder' => date('d-m-Y'),
                            'type' => 'date',
                        ],
                         'value' => function ($model) {
                            return date('d/m/Y H:i:s', strtotime($model->startDate));
                        }
                    ],
                    [
                        'attribute' => 'endDate',
                        'filterInputOptions' => [
                            'class'       => 'form-control',
                            'placeholder' => date('d/m/Y'),
                            'type' => 'date',
                        ],
                        'value' => function ($model) {
                            return date('d/m/Y H:i:s', strtotime($model->endDate));
                        }
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
                            } else if($model->status === 2) {
                                $textStatus = '<span style="color: orange">'.Yii::t('app', 'Close Special').'</span>';
                            } else if($model->status === 9) {
                                $textStatus = '<span style="color: red">'.Yii::t('app', 'Outcome').'</span>';
                            } else if ($model->status === \common\libs\Constants::status_cancel) {
                                $textStatus = '<span style="color: purple">'.Yii::t('app', 'Cancel').'</span>';
                            } else if ($model->status === 3){
                                $textStatus = 'ยังไม่เปิดรับแทง';
                            }else {
                                $textStatus = 'ไม่ได้ระบุ';
                            }
                            return $textStatus;
                        }
                    ],
                    [
                        'label' => 'เปิดปิด/ปุ่ม',
                        'format' => 'html',
                        'value' => function ($model) {
                            if($model->disabled === 0) {
                                $textButton = '<span style="color: purple">'.Yii::t('app', 'Close Button').'</span>';
                            } else {
                                $textButton = '<span style="color: blue">'.Yii::t('app', 'Open Button').'</span>';
                            }
                            return $textButton;
                        }
                    ],
                    [
                        'label' => 'Limit',
                        'format' => 'html',
                        'value' => function ($model) {
                            if($model->limitAuto === 1) {
                                $textButton = '<span style="color: purple">'.Yii::t('app', 'Auto').'</span>';
                            } else {
                                $textButton = '<span style="color: blue">'.Yii::t('app', 'Manual').'</span>';
                            }
                            return $textButton;
                        }
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'buttonOptions' => ['class' => 'btn btn-default'],
                        'template' => '<div class="btn-group btn-group-sm text-center" role="group">{update}{answer}{view}{copy}{cancel}{isAuto}{manualLimit}{autoLimit}</div>',
                        'visibleButtons' => [
                             'answer' => function () use ($arrRoles) {
                                return in_array('answer-shared-game', $arrRoles);
                             },
                            'view' => function () use ($arrRoles) {
                                return in_array('thai-shared-game', $arrRoles);
                            },
                            'copy' => function () use ($arrRoles) {
                                return in_array('create-shared-game', $arrRoles);
                            },
                            'update' => function () use ($arrRoles) {
                                return in_array('update-shared-game', $arrRoles);
                            },
                            'cancel' => function ($model) use ($arrRoles) {
                               $isArrRoles = in_array('cancel-shared-game', $arrRoles);
                               if ($isArrRoles && $model->status === 1) {
                                   return true;
                               }
                               return false;
                            },
                            'manualLimit' => function ($model) {
//                                $gameLimit = ['หวยลาว', 'หวยลาว แบบมีส่วนลด', 'หวยลาวชุด 120', 'หวยลาวชุด 90', 'เวียดนาม/ฮานอย',
//                                    'เวียดนาม/ฮานอย (พิเศษ)', 'หวยฮานอย VIP', 'หวยเวียดนามชุด', 'หวยรัฐบาลไทย', 'หวยรัฐบาลไทย แบบมีส่วนลด',
//                                    'หวยออมสิน', 'หวย ธกส', 'หวยลาว จำปาสัก', 'หวยฮานอย 4D', 'หวยลาวทดแทน'
//                                ];
//                                if (in_array($model->title, $gameLimit, true)) {
//                                    return true;
//                                }
                                return true;
                            },
                            'autoLimit' => function ($model) {
                                return $model->limitAuto === 1 && $model->title !== 'หวยลาวชุด 120' && $model->title !== 'หวยลาวชุด 90' && $model->title !== 'หวยเวียดนามชุด' ? true : false;
                            }
                        ],
                        'buttons'=>[
                            'answer' => function($url,$model){
                                return Html::a('ออกผล', [
                                    'thai-shared-answer-game/create',
                                    'id' => $model->id
                                ],['class'=>'btn btn-default']);
                            },
                            'view' => function($url,$model){
                                return Html::a('<i class="glyphicon glyphicon-eye-open"></i>', [
                                    'thai-shared-answer-game/view',
                                    'id' => $model->id,
                                ],
                                    [
                                        'class'=>'btn btn-default',
                                    ]
                                );
                            },
                            'copy' => function($url,$model){
                                return Html::a('Copy', [
                                    'thai-shared/copy',
                                    'id' => $model->id,
                                ],
                                    [
                                        'class'=>'btn btn-default',
                                    ]
                                );
                            },
                            'cancel' => function($url, $model) {
                                return Html::a('Cancel', [
                                    'thai-shared/cancel',
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
                            'manualLimit' => function($url, $model) {
                                return Html::a('Manual Limit', ['limit-lottery-number-game/index', 'thaiSharedGameId' => $model->id],
                                    [
                                        'class'=>'btn btn-default',
                                    ]
                                );
                            },
                            'autoLimit' => function($url, $model) {
                                return Html::a('Auto Limit', ['limit-auto-lottery-number-game/index', 'thaiSharedGameId' => $model->id],
                                    [
                                        'class'=>'btn btn-default',
                                    ]
                                );
                            }
                        ]
                    ]
                ],
            ]); ?>
            <?php Pjax::end() ?>
        </div>
    </div>
</div>
