<?php

use yii\grid\GridView;
use common\models\PostCreditTransection;
use common\libs\Constants;
use common\models\Bank;
use common\models\UserHasBank;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SmsMessageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Sms Messages';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="widget-box">
    <div class="widget-title bg_lg">
        <span class="icon"><i class="icon-star"></i></span>
        <h5>SMS AUTO</h5>
    </div>
    <div class="widget-content">
        <div class="table-responsive">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'bank',
            'action',
            'amount',
            'date',
            'message',
            [
                'label' => 'Used',
                'value' => function ($model) {
                    return $model->is_used === Constants::status_active ? 'Yes' : 'No';
                }
            ],
            'createdAt',
            [
                'label' => '(หมายเลขบัญชี)/ธนาคาร',
                'value' => function ($model) {
                    $actionId = $model->action === 'ฝาก/โอนเงินเข้า' ? Constants::action_credit_top_up : Constants::action_credit_withdraw;
                    $title = '';
                    if ($model->bank === 'SCB') {
                        $title = 'ธนาคารไทยพาณิชย์';
                    }
                    $postCreditTransection = PostCreditTransection::find()->joinWith(['bank'])->where([
                        'action_id' => $actionId,
                        'title' => $title,
                        'post_requir_time' => $model->date,
                        'amount' => $model->amount
                    ])->one();
                    if ($postCreditTransection) {
                        return '('.$postCreditTransection->userHasBank->bank_account_no.')/'.$postCreditTransection->bank->title;
                    }
                    return '-';
                }
            ],

        ],
    ]); ?>
        </div>
    </div>
</div>
