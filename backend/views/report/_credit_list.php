<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use common\libs\Constants;
use common\models\UserHasBankLog;
use common\models\UserHasBankSearch;
use common\models\PostCreditTransectionSearch;

/* @var $this yii\web\View */
/* @var $searchModel common\models\PostCreditTransectionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Post Credit Transections';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-md-12">


    <?php Pjax::begin(); ?>

    <?php echo $this->render('_credit_search', ['searchModel' => $searchModel, 'tab' => $tab]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            [
                'label' => 'เลขที่ทำรายการ',
                'attribute' => 'update_at',
                'value' => function ($model, $key, $index, $column) {
                    return $model->getOrderId();
                }
            ],
            [
                'label' => 'รายละเอียด',
                'format' => 'raw',
                'attribute' => 'poster_id',
                'value' => function ($model, $key, $index, $column) {
                    $color = Constants::$reason_credit_color[$model->reason_action_id];
                    $text = (in_array($model->action_id, [
                        Constants::action_credit_top_up_admin,
                        Constants::action_credit_master_top_up,
                        Constants::action_credit_master_withdraw,
                        Constants::action_credit_withdraw_admin_special,
                        Constants::action_credit_top_up_admin_special,
                    ]) ?
                        Constants::$action_credit[$model->action_id] :
                        Constants::$reason_credit[$model->reason_action_id]);
                    if ($model->action_id === 7){
                        $color = "#0000b3";
                    }elseif ($model->action_id === 6){
                        $color = "#ff6666";
                    }
                    return '<label" style="padding:4px; color:#ffffff; background:' . $color . ';">' . $text . '</label>';
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
                'label' => 'กระทำกับ',
                'format' => 'html',
                'value' => function ($model, $key, $index, $column) {
                    return $model->reciver->username;
                }
            ],
            [
                'label' => 'เครดิต',
                'format' => 'html',
                'value' => function ($model, $key, $index, $column) {
                    if (in_array($model->action_id, [
                        Constants::action_credit_withdraw,
                        Constants::action_credit_withdraw_admin,
                        Constants::action_credit_master_withdraw,
                        Constants::action_credit_withdraw_admin_special,
                    ])) {
                        return '<div style="color:#5eba7d"> + ' . number_format($model->amount, 2) . '</div>';
                    } else if (in_array($model->action_id, [
                        Constants::action_credit_top_up,
                        Constants::action_credit_top_up_admin,
                        Constants::action_credit_master_top_up,
                        Constants::action_credit_top_up_admin_special,
                    ])) {
                        return '<div style="color:#ff0000"> - ' . number_format($model->amount, 2) . '</div>';
                    }
                }
            ],
            [
                'label' => 'เครดิตคงเหลือ',
                'value' => function ($model, $key, $index, $column) {
                    return number_format($model->credit_master_balance, 2);
                }
            ],
            [
                'label' => 'วันที่ทำรายการ',
                'value' => function ($model, $key, $index, $column) {
                    return date('d/m/Y H:i', strtotime($model->create_at));
                }
            ],
            [
                'label' => 'หมายเหตุ',
                'value' => function ($model, $key, $index, $column) {
                    return (!empty($model->remark) ? $model->remark : '');
                }
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
