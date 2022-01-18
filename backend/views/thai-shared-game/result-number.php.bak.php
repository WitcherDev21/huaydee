<?php

use yii\grid\GridView;
use yii\widgets\Pjax;
use common\models\PlayType;


?>
<div class="widget-box">
    <div class="widget-title">
        <span class="icon">
            <i class="icon-inbox"></i>
        </span>
        <h5>รายการสรุปเลขที่แทง</h5>
        <span class="label label-info">SSL Secure</span>
    </div>
    <div class="widget-content tab-content">
        <div class="widget-box">
            <div class="panel">


                <?= $this->render('_tab', ['active_tab' => $active_tab]) ?>
                <div style="overflow: auto;">
                    <div class="tab-content">
                        <div id="list-current-lottery" class="tab-pane fade in active">
                            <?= $this->render('_search_result_number', [
                                'searchModel' => $searchModel,
                                'playTypeObjs' => $playTypeObjs
                            ]) ?>
                            <?php Pjax::begin(['id' => 'result-number']) ?>
                            <?php
                            echo GridView::widget([
                                'dataProvider' => $dataProvider,
                                'columns' => [
                                    [
                                        'label' => 'วันที่',
                                        'value' => 'createdAt'
                                    ],
                                    [
                                        'label' => 'ประเภท-เกม',
                                        'value' => function($model) {
                                           $playType = PlayType::find()->where(['id' => $model['playTypeId']])->one();
                                           return $playType->game->title . '-' . $playType->title;
                                        }
                                    ],
                                    [
                                        'label' => 'เลข',
                                        'value' => 'number',
                                    ],
                                    [
                                        'label' => 'จำนวนโพย',
                                        'value' => 'amount',
                                    ],
                                ],

                            ]);
                            ?>
                            <?php Pjax::end() ?>
                            <div style="color: #ff0000">อัพเดทล่าสุด <?= date('d/m/Y H:i:s') ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
