<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use common\libs\Constants;
use common\models\UserHasBankLog;
use common\models\UserHasBankSearch;
use common\models\PostCreditTransectionSearch;
use common\models\CreditSearch;

/* @var $this yii\web\View */
/* @var $searchModel common\models\PostCreditTransectionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->registerJs("$(function() {
//   $('.popupModal').click(function(e) {
//     e.preventDefault();
//     $('#modal').modal('show').find('.modal-content').load($(this).attr('href'));
//   });
});");

$this->registerCss('
		table{
			white-space: nowrap !important;
		}');

$this->title = 'Post Credit Transections';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php //Pjax::begin(); ?>

<div class="col-md-12" style="background-color: #fff;">
    <?php echo $this->render('_search', ['searchModel' => $searchModel, 'type' => $type]); ?>
    <?php if (count(Yii::$app->session->getAllFlashes()) > 0) { ?>
        <div>
            <?php foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
                if (in_array($key, ['success', 'error']))
                    echo yii\bootstrap\Alert::widget([
                        'options' => [
                            'class' => 'alert-' . $message['type'],
                        ],
                        'body' => $message['message'],
                    ]);
            } ?>
        </div>
    <?php } ?>
</div>
<div class="col-md-12" style="overflow-y:auto; background-color: #fff;">
    <?php Pjax::begin(['id' => 'transfer-money']) ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            [
                'label' => 'วันที่',
                'attribute' => 'update_at',
                'value' => function ($model) {
                    $date = date('d/m/Y', strtotime($model->create_at));
                    return $date;
                }
            ],
            [
                'label' => 'ผู้แจ้ง',
                'attribute' => 'poster_id',
                'value' => function ($model) {
                    return $model->poster->username;
                }
            ],
            [
                'label' => 'เครดิต',
                'value' => function ($model) {
                    return number_format(CreditSearch::getCredit($model->poster_id), 2);
                }
            ],
            [
                'label' => 'เบอร์โทร',
                'value' => function ($model) {
                    return (!empty($model->poster->tel) ? $model->poster->tel : '-');
                }
            ],
            [
                'label' => 'ประเภท',
                'format' => 'html',
                'attribute' => 'action_id',
                'value' => function ($model) {

                    $btn = 'btn-default';
                    if ($model->action_id == Constants::action_credit_top_up || $model->action_id == Constants::action_credit_top_up_admin) {
                        $btn = 'btn-success';
                    } else if ($model->action_id == Constants::action_credit_withdraw || $model->action_id == Constants::action_credit_withdraw_admin || $model->action_id == Constants::action_commission_withdraw_direct) {
                        $btn = 'btn-danger';
                    }
                    $text = '';
                    if ($model->action_id == Constants::action_commission_withdraw_direct) {
                        if (isset(Constants::$action_commission[$model->action_id])) {
                            $text = Constants::$action_commission[$model->action_id];
                        }
                    }else {
                        if (isset(Constants::$action_credit[$model->action_id])) {
                            $text = Constants::$action_credit[$model->action_id];
                        }
                    }

                    $result = '<a class="btn btn-xs ' . $btn . '">' . $text . '</a>';
                    return $result;
                }
            ],
            [
                'label' => 'ธนาคาร',
                'value' => function ($model) {
                    return isset($model->userHasBankUser->bank->title)
                        ?  $model->userHasBankUser->bank->title : $model->createBy->userHasBank->bank->title;
                }
            ],
            [
                'label' => 'ชื่อบัญชี',
                'value' => function ($model) {
                    return isset($model->userHasBankUser->bank_account_name) ?
                        $model->userHasBankUser->bank_account_name :
                        $model->createBy->userHasBank->bank_account_name;
                }
            ],
            [
                'label' => 'เลขที่บัญชี',
                'value' => function ($model) {
                    return isset($model->userHasBankUser->bank_account_no) ?
                        $model->userHasBankUser->bank_account_no :
                        $model->createBy->userHasBank->bank_account_no;
                }
            ],
            [
                'label' => 'แจ้งเมื่อ',
                'value' => function ($model) {
                    return date('d/m/Y H:i', strtotime($model->create_at));
                }
            ],
            [
                'label' => 'วันที่/เวลา  แจ้งฝาก',
                'value' => function ($model) {
                    return date('d/m/Y H:i', strtotime($model->post_requir_time));
                }
            ],
            [
                'label' => 'จำนวน',
                'format' => 'html',
                'value' => function ($model) {
                    return '<div class="text-right">' . number_format($model->amount, 2) . '</div>';
                }
            ],
            [
                'label' => 'ข้อความหมายเหตุ',
                'value' => function ($model) {
                    return !empty($model->remark) ? $model->remark : '';
                }
            ],
            [
                'label' => 'ช่องทางฝากเงิน',
                'value' => function ($model) {
                    return !empty($model->channel) ? $model->channel : '';
                }
            ],
            [
                'label' => 'Auto/Manual',
                'value' => function ($model) {
                    return $model->is_auto === Constants::status_active ? 'Auto' : 'Manual';
                }
            ],
            [
                'header' => 'สถานะ',
                'format' => 'raw',
                'value' => function ($model) {
                    $btn = 'btn-default';
//                                        $text = '';
                    if (Constants::status_waitting == $model->status) {
                        $btn = 'btn-warning';
                        $text = Constants::$status[Constants::status_waitting];
                    } elseif (Constants::status_cancel == $model->status) {
                        $btn = 'btn-danger';
                        $text = Constants::$status[Constants::status_cancel];
                    } else {
                        $btn = 'btn-success';
                        $text = Constants::$status[Constants::status_processed];
                    }
                    $result = '<a class="btn btn-xs ' . $btn . '">' . $text . '</a>';
                    return $result;
                },

            ],
            [
                'header' => 'การกระทำ',
                'value' => function ($model) {
                    $result = '';
                    if (Constants::status_waitting == $model->status) {
                        $result .= Html::a(Yii::t('app', ' {modelClass}', [
                            'modelClass' => 'ดำเนินการ', //Constants::$status[$model->status]
                        ]), ['post-credit-member/updatestatus', 'id' => $model->id, 'type' => 'approve', 'active' => 'History'],
                            ['onclick' => " $(this).attr('disabled', true)", 'class' => 'btn btn-xs btn-info']
                        );
                        $result .= ' ';
                    }

                    $url = Yii::$app->urlManager->createUrl(['post-credit-member']);

                    $result .= '<a href="' . $url . '/credit?id=' . $model['poster']['id'] . '" class="btn btn-xs btn-default" data-pjax="0"> รายงานเครดิต</a></li>';

                    if (Constants::status_waitting == $model->status) {
                        $result .= ' ';
                        $result .= Html::a(Yii::t('app', ' {modelClass}', [
                            'modelClass' => 'ลบ', //Constants::$status[$model->status]
                        ]), ['post-credit-member/updatestatus', 'id' => $model->id, 'type' => 'cancel', 'active' => 'History'],
                            ['onclick' => " $(this).attr('disabled', true)", 'class' => 'btn btn-xs btn-danger']
                        );
                    }

                    return $result;
                },
                'format' => 'raw'
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
