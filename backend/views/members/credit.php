<?php
/* @var $dataUser */

/* @var $dataProvider */

use yii\grid\GridView;
use common\libs\Constants;
use yii\widgets\Pjax;

?>

<div class="widget-box">
    <div class="widget-title bg_lg">
        <span class="icon"><span class="glyphicon glyphicon-list-alt"></span></span>
        <h5>รายงานเครดิต</h5>
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
                // 'filterModel' => $searchModel,
                'columns' => [
                    [
                        'label' => 'เลขที่ทำรายการ',
                        'attribute' => 'update_at',
                        'value' => function ($model, $key, $index, $column) {
                            return $model->getOrderId();
//                                    return str_pad($model->id, 8, '0', STR_PAD_LEFT);
                        }
                    ],
                    [
                        'label' => 'รายละเอียด',
                        'attribute' => 'poster_id',
                        'format' => 'raw',
                        'value' => function ($model, $key, $index, $column) {
                            if ($model->action_id === 3) {
                                $color = Constants::$reason_credit_color[$model->reason_action_id];
                                $text = Constants::$action_commission[$model->action_id];
                            } else if ($model->action_id === 9 || $model->action_id === 10) {
                                $color = Constants::$reason_credit_color[$model->reason_action_id];
                                $text = Constants::$action_credit[$model->action_id];
                            }else {
                                $color = Constants::$reason_credit_color[$model->reason_action_id];
                                $text = Constants::$reason_credit[$model->reason_action_id];
                            }
                            return '<label" style="padding:4px; color:#ffffff; background:'.$color.';">'.$text.'</label>';
                        }
                    ],
                    [
                        'label' => 'ผู้กระทำ',
                        'format' => 'html',
                        'value' => function ($model, $key, $index, $column) {
                            return $model->operator->username;
                        }
                    ],
                    [
                        'label' => 'เครดิต',
                        'format' => 'html',
                        'value' => function ($model, $key, $index, $column) {
                            if (in_array($model->action_id, [
                                Constants::action_credit_withdraw,
                                Constants::action_credit_withdraw_admin,
                                Constants::action_credit_withdraw_admin_special,
                            ])) {
                                return '<div style="color:#ff0000"> - ' . $model->amount . '</div>';
                            } else if (in_array($model->action_id, [
                                Constants::action_credit_top_up,
                                Constants::action_credit_top_up_admin,
                                Constants::action_commission_withdraw_direct,
                                Constants::action_credit_top_up_admin_special
                            ])) {
                                return '<div style="color:#5eba7d"> ' . $model->amount . '</div>';
                            }
                        }
                    ],
                    [
                        'label' => 'เครดิตคงเหลือ',
                        'value' => function ($model, $key, $index, $column) {
                            return number_format($model->balance, 2);
                        }
                    ],
                    [
                        'label' => 'วันที่ทำรายการ',
                        'value' => function ($model, $key, $index, $column) {
                            return date('d/m/Y H:i:s', strtotime($model->create_at));
                        }
                    ],
                    [
                        'label' => 'หมายเหตุ',
                        'value' => function ($model, $key, $index, $column) {
                            return (!empty($model->remark) ? $model->remark : '');
                        }
                    ],
                ],
            ]);
            ?>
        </div>
    </div>
    <?php Pjax::end() ?>
</div>
