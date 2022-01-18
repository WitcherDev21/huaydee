<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Alert;
use common\models\FixNumberYeekee;
use common\libs\Constants;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Game Yeekee');
$this->params['breadcrumbs'][] = $this->title;
?>
<h2><?= $this->title; ?></h2>
<?php if(Yii::$app->session->hasFlash('alert')):?>
    <?= Alert::widget([
        'body' => ArrayHelper::getValue(Yii::$app->session->getFlash('alert'), 'body'),
        'options' => ArrayHelper::getValue(Yii::$app->session->getFlash('alert'), 'options'),
    ])?>
<?php endif; ?>
<div class="widget-box">
    <div class="widget-title bg_lg">
        <span class="icon"><i class="icon-star"></i></span>
        <h5><?= $this->title ?></h5>
    </div>
    <div class="widget-content">
        <div class="table-responsive">
            <?php Pjax::begin(['id' => 'bank-owner']) ?>
            <?php echo $this->render('_search', [
                'model' => $searchModel,
                'date' => $date,
            ]); ?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
//                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    [
                        'label' => Yii::t('app', 'Yeekee round'),
                        'value' => function ($model) {
                            return Yii::t('app', 'Yeekee round').$model->round;
                        }
                    ],
                    [
                        'label' => Yii::t('app', 'Yeekee result'),
                        'value' => function ($model) {
                            return $model->result ? $model->result : 'รอผล';
                        },
                    ],
                    [
                        'label' => Yii::t('app', 'Start Date Time'),
                        'value' => 'start_at'
                    ],
                    [
                        'label' => Yii::t('app', 'End Date Time'),
                        'value' => 'finish_at'
                    ],
                    [
                        'label' => Yii::t('app', 'Status'),
                        'format' => 'html',
                        'value' => function ($model) {
                            if ($model->status === \common\libs\Constants::status_finish_show_result) {
                                $textStatus = '<span style="color: red">ออกผล</span>';
                            } else if ($model->status === \common\libs\Constants::status_cancel){
                                $textStatus = '<span style="color: orange">ยกเลิก</span>';
                            }else {
                                $textStatus = '<span style="color: blue">ยังไม่ออกผล</span>';
                            }
                            return $textStatus;
                        }
                    ],
                    [
                        'label' => Yii::t('app', 'Enabled'),
                        'format' => 'html',
                        'value' => function ($model) {
                            if ($model->status === \common\libs\Constants::status_inactive) {
                                $textStatus = '<span style="color: red">ปิด</span>';
                            } else {
                                $textStatus = '<span style="color: blue">เปิด</span>';
                            }
                            return $textStatus;
                        }
                    ],
                    [
                        'label' => Yii::t('app', 'Open Bot'),
                        'format' => 'html',
                        'value' => function ($model) {
                            if ($model->isOpenBot === \common\libs\Constants::status_inactive) {
                                $textStatus = '<span style="color: red">ปิด</span>';
                            } else {
                                $textStatus = '<span style="color: blue">เปิด</span>';
                            }
                            return $textStatus;
                        }
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'buttonOptions' => ['class' => 'btn btn-default'],
                        'template' => '<div class="btn-group btn-group-sm text-center" role="group">{status}{outCome}{view}{viewFixNumber}{cancelOutCome}</div>',
                        'visibleButtons' => [
                            'status' => function ($model) {
                                return $model->status === 1;
                            },
                            'outCome' => function ($model) use ($arrRoles) {
                                return $model->status === 1 && in_array('fix-yeekee-number', $arrRoles);
                            },
                            'cancelOutCome' =>  function ($model) {
                                return $model->status === Constants::status_active || $model->status === Constants::status_processing || $model->status === Constants::status_processing_2;
                            },
                            'viewFixNumber' => function ($model) use ($arrRoles) {
                                $isFixNumber = FixNumberYeekee::find()->where(['yeekeeId' => $model->id])->count();
                                return $isFixNumber && in_array('fix-yeekee-number', $arrRoles);
                            }
                        ],
                        'buttons'=>[
                            'status' => function($url, $model){
                                return Html::a(Yii::t('app', 'Status'), [
                                    'system/status',
                                    'id' => $model->id
                                ],['class'=>'btn btn-default']);
                            },
                            'outCome' => function($url, $model) {
                                return Html::a(Yii::t('app', 'Fix Number'), [
                                    'system/answer',
                                    'id' => $model->id,
                                ],
                                    [
                                        'class'=>'btn btn-default',
                                    ]
                                );
                            },
                            'view' => function($url, $model) {
                                return Html::a('<i class="glyphicon glyphicon-eye-open"></i>', [
                                    'system/view',
                                    'id' => $model->id,
                                ],
                                    [
                                        'class'=>'btn btn-default',
                                    ]
                                );
                            },
                            'viewFixNumber' => function($url, $model) {
                                return Html::a('View Fix Number', [
                                    'system/view-fix-number',
                                    'id' => $model->id,
                                ],
                                    [
                                        'class'=>'btn btn-default',
                                    ]
                                );
                            },
                            'cancelOutCome' => function($url, $model){
                                return Html::a(Yii::t('app', 'cancel Out Come'), [
                                    'system/cancel-yeekee',
                                    'id' => $model->id
                                ],[
                                    'class'=>'btn btn-default',
                                    'data' => [
                                        'confirm' => 'Are you sure you want to cancel this item?',
                                        'method' => 'post',
                                    ],
                                ]);
                            },
                        ]
                    ]
                ],
            ]); ?>
            <?php Pjax::end() ?>
        </div>
    </div>
</div>