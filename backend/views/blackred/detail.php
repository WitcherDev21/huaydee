<?php

use yii\grid\GridView;
use yii\widgets\Pjax;
use common\libs\Constants;
use yii\widgets\Breadcrumbs;

$params['breadcrumbs'][] = ['label' => 'รายการดำแดง', 'url' => ['blackred/list-current', 'layout' => 'none']];
$params['breadcrumbs'][] = '#' . $orderId;
?>

<div class="col-xs-12">
    <div class="panel">

        <?= $this->render('_tab', ['active_tab' => $active_tab]) ?>
        <div style="overflow: auto;">
            <?php Pjax::begin(); ?>
            <?= Breadcrumbs::widget([
                'homeLink' => false,
                'links' => isset($params['breadcrumbs']) ? $params['breadcrumbs'] : [],
            ]); ?>
            <?php
            echo GridView::widget([
                    'dataProvider' => $dataProvider,
                    // 'filterModel' => $searchModel,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn', 'header' => 'ลำดับ'],
                        [
                            'label' => 'ประเภทดำแดง',
                            'value' => function ($model) {
                                return $model->play_type_code === 1 ? 'ดำ' : 'แดง';
                            }
                        ],
                        [
                            'label' => 'ราคาที่แทง',
                            'footer' => Constants::getTotal($dataProvider->models, 'total_amount'),
                            'value' => function ($model) {
                                return number_format($model->total_amount);
                            }
                        ],
                        [
                            'label' => 'ราคาจ่าย',
                            'value' => function ($model) {
                                $playTypeCode = $model->play_type_code === 1 ? 'black' : 'red';
                                $playTypeCode = Constants::getPlayTypeCode($playTypeCode);
                                return $playTypeCode->jackpot_per_unit;
                            }
                        ],
                        [
                            'label' => 'ผลดำแดงที่ออก',
                            'value' => function ($model) {
                                if (!$model->blackred->result) {
                                    return '';
                                }
                                return $model->blackred->result === 1 ? 'ดำ' : 'แดง';
                            }
                        ],
                        [
                            'label' => 'สถานะ',
                            'format' => 'html',
                            'value' => function ($model) {
                                $result = '';
                                if ($model->status == Constants::status_finish_show_result) {
                                    if ($model->flag_result == 1) {
                                        $result = '<a href="javascript:;" class="btn btn-xs btn-success" style="color: #ffffff;">' . 'ชนะ' . '</a>';
                                    } else {
                                        $result = '<a href="javascript:;" class="btn btn-xs btn-danger" style="color: #ffffff;">' . 'แพ้' . '</a>';
                                    }
                                }
                                return $result;
                            }
                        ]
                    ],
                    'showFooter' => true,

                ]
            );
            ?>
            <?php yii\widgets\Pjax::end(); ?>
        </div>
    </div>
</div>
