<?php

use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\web\View;
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
<div class="widget-box">
    <div class="widget-title bg_lg">
        <span class="icon"><i class="icon-star"></i></span>
        <h5>เกมหวยหุ้นในระบบ</h5>
    </div>
    <div class="widget-content">
        <div class="table-responsive">
            <?php Pjax::begin(['id' => 'preview-list-thai-shared']) ?>
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
                            } else if($model->status === 2) {
                                $textStatus = '<span style="color: orange">'.Yii::t('app', 'Close Special').'</span>';
                            } else if($model->status === 9) {
                                $textStatus = '<span style="color: red">'.Yii::t('app', 'Outcome').'</span>';
                            } else if ($model->status === \common\libs\Constants::status_cancel) {
                                $textStatus = '<span style="color: purple">'.Yii::t('app', 'Cancel').'</span>';
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
                        'class' => 'yii\grid\ActionColumn',
                        'buttonOptions' => ['class' => 'btn btn-default'],
                        'template' => '<div class="btn-group btn-group-sm text-center" role="group">{answer}</div>',
                        'visibleButtons' => [
                            'answer' => function () use ($arrRoles) {
                                return in_array('answer-shared-game', $arrRoles);
                            },
                        ],
                        'buttons'=>[
                            'answer' => function($url,$model){
                                return Html::a('ออกผล', [
                                    'thai-shared-answer-game/preview-answer',
                                    'id' => $model->id
                                ],['class'=>'btn btn-default']);
                            },
                        ]
                    ]
                ],
            ]); ?>
            <?php Pjax::end() ?>
        </div>
    </div>
</div>
