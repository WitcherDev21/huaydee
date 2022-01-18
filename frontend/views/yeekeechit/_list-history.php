<?php
use yii\helpers\Url;
use yii\bootstrap\Html;
use yii\base\Widget;
use yii\grid\GridView;
use yii\widgets\Pjax;
use common\libs\Constants;

$js = <<<EOT
// 	$('.popupModal').click(function(e) {
//      e.preventDefault();
//      $('#modal').modal('show').find('.modal-content').load($(this).attr('href'));
//    });
EOT;
$this->registerJs ( $js );
$css = <<<EOT
	
EOT;
$this->registerCss ( $css );

$today = date ( 'Y-m-d H:i:s' );
?>


<div class="col-xs-12">
	<div class="panel">

	<?= $this->render('_tab',['active_tab'=>$active_tab])?>
	<div style="overflow: auto;">
	<div class="tab-content">
			<div id="list-current-yeekee" class="tab-pane fade in active">
			
  <?php Pjax::begin(); ?>
  <?php echo $this->render('_search-history', ['searchModel' => $searchModel]); ?>
  <?php
    	//yii\bootstrap\Modal::begin(['id' =>'modal']);
    	//yii\bootstrap\Modal::end();
	?>
  <?php
		echo GridView::widget ( [ 
				'dataProvider' => $dataProvider,
				// 'filterModel' => $searchModel,
				'columns' => [
						// ['class' => 'yii\grid\SerialColumn'],
						[ 
								'label' => 'เลขที่รายการ',
								//'attribute' => 'id',
								'value' => function ($model, $key, $index, $column) {
									//return str_pad ( $model->id, 8, '0', STR_PAD_LEFT );
									return $model->getOrder();
								} 
						],
						[ 
								'header' => '<div class="text-center">งวดที่</div>',
								'format'=>'html',
								'value' => function ($model, $key, $index, $column) {
									return '<div class="text-center">'.$model->yeekee->round.'</div>';
								} 
						],
						[ 
								'label' => 'วันที่ / เวลา',
								'attribute' => 'create_at',
								'value' => function ($model, $key, $index, $column) {
									$date = '';
									if(empty($model->update_at)){
										$date = date('d/m/Y H:i:s',strtotime($model->create_at));
									}else{
										$date = date('d/m/Y H:i:s',strtotime($model->update_at));
									}
									return $date;
								} 
						],
// 						[ 
// 								'label' => 'วันที่สร้าง',
// 								'attribute' => 'create_at',
// 								'value' => function ($model, $key, $index, $column) {
// 									return date('d/m/Y H:i:s',strtotime($model->create_at));
// 								} 
// 						],
						[ 
								'label' => 'เงินเดิมพัน',
								'attribute' => 'total_amount',
								'value' => function ($model, $key, $index, $column) {
									return number_format ( $model->total_amount, 2 );
								} 
						],
						[
								'label' => 'ผลชนะ',
								'format'=>'html',
								'value' => function ($model, $key, $index, $column) {
									$result = 'รอผล';
									if($model->status == Constants::status_finish_show_result){
										if($model->getIsWin()){
											$result = '<a href="javascript:;" class="btn btn-xs btn-success" style="color:#ffffff;">'.'ชนะ'.'</a>';
										}else{
											$result = '<a href="javascript:;" class="btn btn-xs btn-danger" style="color:#ffffff;">'.'แพ้'.'</a>';
										}										
									}else if($model->status == Constants::status_cancel){
										$result = '<a href="javascript:;" class="btn btn-xs btn-danger" style="color:#ffffff;">'.'ยกเลิก'.'</a>';
									}
									return $result;
									
								} 
						],
						[ 
								'label' => 'สถานะ',
								'format' => 'html',
								'value' => function ($model, $key, $index, $column){
									return '<a href="javascript:;" class="btn btn-xs btn-'.Constants::$statusIcon[$model->status].'" style="color:#ffffff;">'
										.Constants::$status[$model->status].'</a>';
								}
						],
						[ 
								'label' => 'ดูโพย',
								'format'=>'html',
								'value' => function ($model, $key, $index, $column){
									$btn = 'btn-info';									
									$text = 'view';
									$result = Html::a(Yii::t('app', ' {modelClass}', [
										'modelClass' => $text,
									]), ['yeekeechit/detail','chit_id'=>$model->id,'layout'=>'none','from'=>'list-current'], ['class' => 'btn btn-xs '.$btn,'style'=>'color:#ffffff;']);
								
									return $result;
								}
						]
				]
				 
		] );
		?>
<?php yii\widgets\Pjax::end(); ?>
	<div style="color: #ff0000">อัพเดทล่าสุด <?= date('d/m/Y H:i:s')?></div>
	</div>
</div>
</div>
</div>
</div>
