<?php
/* @var $searchModel
 * @var $dataProvider
 */
/**/
use common\libs\Constants;
use common\models\User;
use yii\bootstrap\Modal;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

?>

<div class="widget-box">
    <div class="widget-title">
        <ul class="nav nav-tabs">
            <li class="<?= Yii::$app->controller->action->id === 'list' ? 'active' : '' ?>">
                <a class="tab1" href="<?= Url::to(['members/list'])?>">รายการสมาชิก</a>
            </li>
            <li class="<?= Yii::$app->controller->action->id === 'waiting-approve' ? 'active' : '' ?>">
                <a class="tab1" href="<?= Url::to(['members/waiting-approve'])?>">รายการบัญชีรออนุมัติ</a>
            </li>
        </ul>
    </div>

    <?php if (count(Yii::$app->session->getAllFlashes()) > 0) { ?>
        <div style="padding: 20px 20px 0 20px;">
            <?php foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
                echo yii\bootstrap\Alert::widget([
                    'options' => [
                        'class' => 'alert-' . $message['type'],
                    ],
                    'body' => $message['message'],
                ]);
            } ?>
        </div>
    <?php } ?>

    <?php Pjax::begin(['id' => 'countries-tab1', 'timeout' => false, 'enablePushState' => false, 'clientOptions' => ['method' => 'GET']]) ?>

    <div class="widget-content ">

        <div class="table-responsive">
            <?php
            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                     ['class' => 'yii\grid\SerialColumn'],
                    [
                        'label' => 'Username',
                        'value' => function ($model) {
                            return $model->user->username;
                        }
                    ],
                    [
                        'label' => 'ชื่อบัญชี',
                        'attribute' => 'bank_account_name',
                    ],
                    [
                        'label' => 'ชื่อธนาคาร',
                        'value' => function ($model) {
                            return $model->bank->title;
                        }
                    ],
                    [
                        'label' => 'เลขที่บัญชี',
                        'value' => 'bank_account_no',
                    ],
                    [
                        'label' => 'วันที่สมัคร',
                        'value' => function ($model) {
                            return (!empty($model->user->created_at) ? date('d/m/Y H:i:s', $model->user->created_at) : '');
                        }
                    ],
                    [
                        'label' => 'สถานะบัญชี',
                        'format' => 'html',
                        'value' => function ($model) {
                            if ($model->status == Constants::status_cancel) {
                                $message = 'ยกเลิก';
                            } elseif ($model->status == Constants::status_waitting) {
                                $message = 'รอดำเนินการ';
                            } else {
                                $message = 'อนุมัติ';
                            }
                            return $message;
                        }
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'buttonOptions' => ['class' => 'btn btn-default'],
                        'template' => '<div class="btn-group btn-group-sm text-center" role="group">{approve}{cancel}</div>',
                        'visibleButtons' => [
                            'approve' => function ($model) {
                                return $model->status === Constants::status_waitting;
                            },
                            'cancel' => function ($model) {
                                return $model->status === Constants::status_waitting;                            },
                        ],
                        'buttons'=>[
                            'approve' => function($url,$model){
                                return Html::a('Approve', [
                                    'members/change-status-bank',
                                    'id' => $model->id,
                                    'status' => Constants::status_active
                                ],['class'=>'btn btn-default']);
                            },
                            'cancel' => function($url,$model){
                                return Html::a('Cancel', [
                                    'members/change-status-bank',
                                    'id' => $model->id,
                                    'status' => Constants::status_cancel
                                ],
                                    [
                                        'class'=>'btn btn-default',
                                    ]
                                );
                            },
                        ]
                    ]
                ],
            ]);
            ?>
        </div>
    </div>
</div>