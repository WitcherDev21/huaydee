<?php

use common\models\ThaiSharedGameChitDetail;
use yii\helpers\Url;
use yii\bootstrap\Html;
//use yii\grid\GridView;
use common\libs\Constants;
use yii\widgets\Pjax;
use kartik\export\ExportMenu;
use kartik\grid\GridView;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

ini_set('memory_limit', '-1');

$csrf = \yii::$app->request->csrfParam;
$token = \yii::$app->request->csrfToken;
$cancel_chit = Url::toRoute('lottery-game/cancel');
$js = <<<EOT
EOT;
$this->registerJs($js);
$css = <<<EOT
	
EOT;
$this->registerCss($css);

$today = date('Y-m-d H:i:s');

?>
<div class="widget-box">
    <div class="widget-title">
        <span class="icon">
            <i class="icon-inbox"></i>
        </span>
        <h5>รายการโพยหวยหุ้น</h5>
        <span class="label label-info">SSL Secure</span>
    </div>
    <div class="widget-content tab-content">
        <div class="widget-box">
            <div class="panel">


                <?= $this->render('_tab', ['active_tab' => $active_tab]) ?>
                <div style="overflow: auto;">
                    <div class="tab-content">
                        <div id="list-current-lottery" class="tab-pane fade in active">
                            <?= $this->render('_search', ['searchModel' => $searchModel]) ?>
                            <?php Pjax::begin(['id' => 'list-thai-shared-game']) ?>
                            <?php
                            echo ExportMenu::widget([
                                'dataProvider' => $dataProvider,
                                
                                'columnSelectorOptions'=>[
                                    'label' => 'Cols...',
                                ],
                                'hiddenColumns'=>[0, 9], // SerialColumn & ActionColumn
                                'disabledColumns'=>[1, 2], // ID & Name
                                'dropdownOptions' => [
                                    'label' => 'Export All',
                                    'class' => 'btn btn-outline-secondary btn-default'
                                ]
                            ]) . "<hr>\n".
                            GridView::widget([
                                'dataProvider' => $dataProvider,
                                'columns' => [
                                    [
                                        'label' => 'เลขที่รายการ',
                                        'value' => function ($model) {
                                            return $model->id;
                                        }
                                    ],
                                    [
                                        'label' => 'สมาชิก',
                                        'value' => function ($model) {
                                            return $model->user['username'];
                                        }
                                    ],
                                    [
                                        'label' => 'เอเย็นต์',
                                        'value' => function ($model) {
                                            $agent = \common\models\User::findOne([$model->user['agent']]);
                                            return (!empty($agent) ? $agent->username : '');
                                        }
                                    ],
                                    [
                                        'label' => 'รายการโพยหวยหุ้น',
                                        'value' => function ($model) {
                                            return $model->thaiSharedGame->title;
                                        }
                                    ],
                                    [
                                        'label' => 'วันที่ / เวลา',
                                        'attribute' => 'createdAt',
                                        'value' => function ($model) {
                                            return date('d/m/Y H:i:s', strtotime($model->createdAt));
                                        }
                                    ],
                                    [
                                        'label' => 'เงินเดิมพัน',
                                        'value' => function ($model) {
                                            return number_format($model->totalAmount, 2);
                                        }
                                    ],
                                    [
                                        'label' => 'ยอดเงินหลังส่วนลด',
                                        'value' => function ($model) {
                                            $totalAmount = ThaiSharedGameChitDetail::find()->where(['thaiSharedGameChitId' => $model->id])->sum('discount');
                                            return number_format($totalAmount, 2);
                                        }
                                    ],
                                    [
                                        'label' => 'ผลชนะ',
                                        'format' => 'html',
                                        'value' => function ($model) {
                                            $result = 'รอผล';
                                            if ($model->status == Constants::status_finish_show_result) {
                                                if ($model->getIsWin()) {
                                                    $result = '<div style="color:' . Constants::color_credit_in . '">' . $model->getTotalWinCredit() . '</div>';
                                                } else {
                                                    $result = '<div style="color:' . Constants::color_credit_out . '">' . '0' . '</div>';
                                                }
                                            } else if ($model->status == Constants::status_cancel) {
                                                $result = '<div>' . '0' . '</div>';
                                            }
                                            return $result;

                                        }
                                    ],
                                    [
                                        'label' => 'สถานะ',
                                        'format' => 'html',
                                        'value' => function ($model) {
                                            return '<a href="javascript:;" class="btn btn-xs btn-' . Constants::$statusIcon[$model->status] . '" style="color:#ffffff;">'
                                                . Constants::$status[$model->status] . '</a>';
                                        }
                                    ],
                                    [
                                        'label' => 'ดูโพย',
                                        'format' => 'html',
                                        'value' => function ($model) use ($active_tab) {
                                            $btn = 'btn-info';
                                            $text = 'view';
                                            if ($model->thaiSharedGame->gameId === Constants::LOTTERYLAODISCOUNTGAME || $model->thaiSharedGame->gameId === Constants::LOTTERYLAOGAME || $model->thaiSharedGame->gameId === Constants::LOTTERY_VIETNAM_SET) {
                                                $url =
                                                    [
                                                        'thai-shared-game/detail-lottery-lao-set',
                                                        'thaiSharedGameChitId' => $model->id,
                                                        'from' => $active_tab
                                                    ];
                                            } else {
                                                $url =
                                                    [
                                                        'thai-shared-game/detail',
                                                        'thaiSharedGameChitId' => $model->id,
                                                        'from' => $active_tab
                                                    ];
                                            }
                                            $result = Html::a(Yii::t('app', ' {modelClass}', [
                                                'modelClass' => $text,
                                            ]), $url,
                                                [
                                                    'class' => 'btn btn-xs ' . $btn,
                                                    'style' => 'color:#ffffff;'
                                                ]
                                            );

                                            return $result;
                                        }
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
