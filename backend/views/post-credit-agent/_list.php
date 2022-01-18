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

$this->registerJs("$(function() {
   $('.popupModal').click(function(e) {
     e.preventDefault();
     $('#modal').modal('show').find('.modal-content').load($(this).attr('href'));
   });
});");

$this->registerCss('
		table{
			white-space: nowrap !important;
		}');

$this->title = 'Post Credit Transections';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php //Pjax::begin(); ?>

<div class="col-md-12">
	<?php echo $this->render('_search', ['searchModel' => $searchModel]); ?>
</div>
<div class="col-md-12" style="overflow-y:auto; height:100hv;">
	<?php
    	yii\bootstrap\Modal::begin(['id' =>'modal']);
    	yii\bootstrap\Modal::end();
	?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            [
				'label' => 'วันที่',
				'attribute' => 'update_at',
				'value' => function ($model, $key, $index, $column) {
					$date = '';
// 					if(empty($model->update_at)){
// 						$date = date('d/m/Y',strtotime($model->create_at));
// 					}else{
// 						$date = date('d/m/Y',strtotime($model->update_at));
// 					}
					$date = date('d/m/Y',strtotime($model->create_at));
					return $date;
				} 
			],
			[
				'label' => 'ผู้แจ้ง',
				'attribute' => 'poster_id',
				'value' => function ($model, $key, $index, $column) {
					return $model->poster->username;
				}
			],
			[
				'label' => 'เครดิต',
				'value' => function ($model, $key, $index, $column) {
					return '';
				}
			],
			[
				'label' => 'เบอร์โทร',
				'value' => function ($model, $key, $index, $column) {
					return '';
				}
			],
			[
				'label' => 'ประเภท',
				'format' => 'html',
				'attribute' => 'action_id',
				'value' => function ($model, $key, $index, $column) {
				
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
					return $result;
				}
			],
			
            [            		
            	'label' => 'ธนาคาร',
            	'value' => function($model, $key, $index, $column){
            		$bankLog = PostCreditTransectionSearch::getBankAccount($model->user_has_bank_version, $model->user_has_bank_id);
  
            		return $bankLog->bank->title;
            		
				}
			],
			[
				'label' => 'ชื่อบัญชี',
				'value' => function($model, $key, $index, $column){
					$bankLog = PostCreditTransectionSearch::getBankAccount($model->user_has_bank_version, $model->user_has_bank_id);

					return $bankLog->bank_account_name;
				}
			],
			[
				'label' => 'เลขที่บัญชี',
				'value' => function($model, $key, $index, $column){
					$bankLog = PostCreditTransectionSearch::getBankAccount($model->user_has_bank_version, $model->user_has_bank_id);
	
					return $bankLog->bank_account_no;
				}
			],
			[
			    'label' => 'แจ้งเมื่อ',
			    'value' => function($model, $key, $index, $column){
			     return date('d/m/Y H:i',strtotime($model->create_at));
			    }
			],
			[
		        'label' => 'วันที่/เวลา  แจ้งฝาก',
		        'value' => function($model, $key, $index, $column){
		          return date('d/m/Y H:i',strtotime($model->post_requir_time));
		        }
			],
			[
				'label' => 'จำนวน',
				'format' => 'html',
				'value' => function($model, $key, $index, $column){
					return '<div class="text-right">'.number_format($model->amount,2).'</div>';
				}
			],
			[
				'header'=>'สถานะ',
				'format' => 'raw',
				'value'=> function($model)
				{
					$btn = 'btn-default';
					if(Constants::status_waitting == $model->status){
						$btn = 'btn-danger';
					}else if(Constants::status_processed == $model->status){
						$btn = 'btn-default';
					}
					$result = Html::a(Yii::t('app', ' {modelClass}', [
							'modelClass' => 'ปรับสถานะ', //Constants::$status[$model->status]
					]), ['post-credit-agent/popup','id'=>$model->id,'layout'=>'none'], ['class' => 'btn btn-xs '.$btn.' popupModal']);
						
					return $result;
				},
			
			],
			[
				'header'=>'การกระทำ',
				'value'=> function($model)
				{
					$btn = 'btn-warning';
					$result = Html::a(Yii::t('app', ' {modelClass}', [
						'modelClass' => 'รายงานเครดิต', //Constants::$status[$model->status]
					]), ['post-credit-agent/view','id'=>$model->id,'layout'=>'none'], ['class' => 'btn btn-xs '.$btn.' popupModal']);
					
					return $result; 
				},
				'format' => 'raw'
			],
			[
				'label' => 'ข้อความหมายเหตุ',
				'value' => function($model, $key, $index, $column){
				return !empty($model->remark)?$model->remark:'';
				}
			],
			

            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
  
</div>
  <?php //Pjax::end(); ?>