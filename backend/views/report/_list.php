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

<div class="col-xs-12">
	<div class="panel">
<div style="overflow: auto;">
	<?= $this->render('_tab',['active_tab'=>$active_tab])?>
	<div class="tab-content">
			<div id="list-current-yeekee" class="tab-pane fade in active">

    <?php Pjax::begin(); ?>
		
	<?php echo $this->render('_search', ['searchModel' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            [ 						
				'label' => 'เลขที่ทำรายการ',
				'value' => function ($model, $key, $index, $column) {
				    return '#'.$model->getOrderId();
				} 
			],
			[
				'label' => 'รายละเอียด',
				'format'=>'raw',
				'attribute' => 'poster_id',
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
				'label' => 'กระทำกับ',
				'format' => 'html',
				'value' => function ($model, $key, $index, $column) {
					return $model->reciver->username;
				}
			],
            [            		
            	'label' => 'เครดิต',
            	'format'=>'html',
            	'value' => function($model, $key, $index, $column){
            	if(in_array($model->action_id,[Constants::action_credit_withdraw,Constants::action_credit_withdraw_admin,Constants::action_commission_withdraw_direct])){
            			return '<div style="color:#ff0000"> - '.$model->amount.'</div>';
            		}else if(in_array($model->action_id,[Constants::action_credit_top_up,Constants::action_credit_top_up_admin])){
            			return '<div style="color:#5eba7d"> '.$model->amount.'</div>';
            		}
            		return $model->amount;
            		
				}
			],
			[
				'label' => 'เครดิตคงเหลือ',
				'value' => function($model, $key, $index, $column){
					return number_format($model->balance,2);
				}
			],
			[
				'label' => 'วันที่ทำรายการ',
				'value' => function($model, $key, $index, $column){
					return date('d/m/Y H:i:s',strtotime($model->create_at));
				}
			],
			[
				'label' => 'หมายเหตุ',
				'value' => function($model, $key, $index, $column){
				    return empty($model->remark)?'':$model->remark;
				}
			],
			
            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
    <div style="color: #ff0000">อัพเดทล่าสุด <?= date('d/m/Y H:i:s')?></div>
</div>
</div>
</div>
</div>
</div>
