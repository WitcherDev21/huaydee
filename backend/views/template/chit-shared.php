<?php
/* @var $dataUser
 * @var $dataProvider
 */
use common\models\ThaiSharedGameChitDetail;
use yii\grid\GridView;
use common\libs\Constants;
use yii\widgets\Pjax;
?>

<div class="widget-box">
    <div class="widget-title bg_lg">
        <span class="icon"><span class="glyphicon glyphicon-file"></span></span>
        <h5>รายการโพย</h5>
    </div>
    <?php Pjax::begin(['id' => 'countries-tab1', 'timeout' => false, 'enablePushState' => false, 'clientOptions' => ['method' => 'GET']]) ?>
    <div class="widget-content ">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">ข้อมูลสมาชิก</h3>
            </div>
            <div class="panel-body">
                <b>Username ::</b> <?php echo $dataUser->username; ?><br><br>
                <b>อีเมล์ ::</b> <?php echo $dataUser->email; ?><br><br>
                <b>เบอร์โทร ::</b> <?php echo $dataUser->tel; ?>
            </div>
        </div>

        <div class="table-responsive">
            <?php
            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    [
                        'label' => 'เลขที่รายการ',
                        'value' => function ($model) {
                            return $model->getOrder();
                        }
                    ],
                    [
                        'label' => 'สมาชิก',
                        'value' => function ($model) {
                            return $model->user->username;
                        }
                    ],
                    [
                        'label' => 'เอเย็นต์',
                        'value' => function ($model) {
                            $agent = \common\models\User::findOne([$model->user->agent]);
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
                        'label' => 'เงินจ่ายจริง',
                        'value' => function ($model) {
                            $thaiSharedGameChitDetailTotal = ThaiSharedGameChitDetail::find()->where(['thaiSharedGameChitId' => $model->id])->sum('discount');
                            return number_format($thaiSharedGameChitDetailTotal, 2);
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
                        'header' => 'ดูโพย',
                        'headerOptions' => ['width' => '60', 'class' => 'text-center'],
                        'contentOptions' => ['style' => 'text-align: center;'],
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{view}',
                        'buttons' => [
                            'view' => function ($url,$model,$key) {
                                $urlChit = \yii\helpers\Url::to(['members/chit-shared-detail', 'id' => $model->id]);
                                return '<a href="'.$urlChit.'" target="_blank" data-pjax="0"><span class="glyphicon glyphicon-list-alt"></span></a>';
                            }
                        ],
                    ],
                ]
            ]);
            ?>
        </div>
    </div>
    <?php Pjax::end() ?>
</div>
