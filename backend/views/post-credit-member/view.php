<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\libs\Constants;

/* @var $this yii\web\View */
/* @var $model common\models\PostCreditTransection */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Post Credit Transections', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-credit-transection-view" style="padding:6px;">

    <p>
        <?php //= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?php //= Html::a('Delete', ['delete', 'id' => $model->id], [
//             'class' => 'btn btn-danger',
//             'data' => [
//                 'confirm' => 'Are you sure you want to delete this item?',
//                 'method' => 'post',
//             ],
//         ]) ?>
    </p>
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>เลขที่ทำรายการ</th>
                    <th>รายละเอียด</th>
                    <th>ผู้กระทำ</th>
                    <th>กระทำกับ</th>
                    <th>เครดิต</th>
                    <!--<th>เครดิตคงเหลือ</th>-->
                    <th>วันที่ทำรายการ</th>
                    <th>หมายเหตุ</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo str_pad($model->id, 8, '0', STR_PAD_LEFT); ?></td>
                    <td>
                        <?php
                        $btn = 'btn-default';
                        if($model->action_id == Constants::action_credit_top_up || $model->action_id == Constants::action_credit_top_up_admin){
                                $btn = 'btn-success';
                        }else if($model->action_id == Constants::action_credit_withdraw || $model->action_id == Constants::action_credit_withdraw_admin){
                                $btn = 'btn-danger';
                        }
                        $text = '';
                        if(isset(Constants::$action_credit[$model->action_id])){
                                $text = Constants::$action_credit[$model->action_id];
                        }
                        $result = '<a class="btn btn-xs ' .$btn. '">'. $text .'</a>';
                        echo $result;
                        ?>
                    </td>
                    <td><?php echo $model->createBy->username; ?></td>
                    <td><?php echo $model->poster->username; ?></td>
                    <td>
                        <?php
                        if(in_array($model->action_id,[Constants::action_credit_withdraw,Constants::action_credit_withdraw_admin])){
                                echo '<div style="color:#ff0000"> - '.number_format($model->amount,2).'</div>';
                        }else if(in_array($model->action_id,[Constants::action_credit_top_up,Constants::action_credit_top_up_admin])){
                                echo '<div style="color:#5eba7d"> '.number_format($model->amount,2).'</div>';
                        }
                        ?>
                    </td>
                    <!--<td><?php // echo number_format($model->amount,2); ?></td>-->
                    <td><?php echo date('d/m/Y H:i',strtotime($model->create_at)); ?></td>
                    <td><?php echo (!empty($model->remark) ? $model->remark : ''); ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
