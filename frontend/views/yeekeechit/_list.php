<?php
use yii\helpers\Url;
use yii\bootstrap\Html;
use yii\base\Widget;
use yii\grid\GridView;
use yii\widgets\Pjax;
use common\libs\Constants;


$csrf = \yii::$app->request->csrfParam;
$token = \yii::$app->request->csrfToken;
$cancel_chit = Url::toRoute('yeekeechit/cancel-yeekeechit');
$js = <<<EOT

$('.btn-refun').on('click',function(e){
        e.preventDefault();
		btn = $(this);
		url = btn.attr('href');
        swal({
          width: '50%',
          title: '',
          html: "<div style='font-size:18px;'>ยืนยันการคืนโพย, <br>คุณต้องการยกเลิก ใช่ หรือ ไม่ใช่ ?</div>",
          type: 'info',
          showCancelButton: true,
          confirmButtonText: 'ใช่',
          cancelButtonText: 'ไม่ใช่'
        }).then((result) => {

          if (result.value) {
           var formData = {
					'$csrf':'$token'
			};
		
			$.ajax({
		        url: url,
		        type: 'post',
		        data: formData,
		        success: function (result) {
					if(result == 1){
                        swal({
                             width: '50%',
                             html: "<div style='font-size:18px;'>คืนโพยสำเร็จ</div>",
                        });
						btn.remove();
						location.reload();
					}else{
                        swal({
                             width: '50%',
                             html: "<div style='font-size:18px;'>ไม่สามารถทำรายการได้ ระบบจะทำการ reload ข้อมูลใหม่</div>",
                        });
						location.reload();
					}
		        },
		        error: function () {
		            swal({
                         width: '50%',
                         html: "<div style='font-size:18px;'>ไม่สามารถทำรายการได้ ระบบจะทำการ reload ข้อมูลใหม่</div>",
                    });
					location.reload();
		        }
				
		      }); 
          }
        });
						
});

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
		  
  <?php //Pjax::begin(); ?>
  <?php echo $this->render('_search', ['searchModel' => $searchModel]); ?>
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
									return '<div class="text-center">'.'งวดที่ '.$model->yeekee->round.' วันที่ '.date('d/m/Y',strtotime($model->yeekee->date_at)).'</div>';
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
								'footer' => '',
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
										    $result = '<div style="color:'.Constants::color_credit_in.'">'.$model->getTotalWinCredit().'</div>';
										}else{
											$result = '<div style="color:'.Constants::color_credit_out.'">'.'0'.'</div>';
										}										
									}else if($model->status == Constants::status_cancel){
										$result = '<div>'.'0'.'</div>';
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
						],
						[ 
								'label' => 'คืนโพย',
								'format'=>'html',
								'value' => function ($model, $key, $index, $column){
									$result = '';
									if($model->getCanReChit()){
										return Html::a(
												'คืนโพย',
												['/yeekeechit/cancel-yeekeechit', 'id' => $model->id],
												['class'=>'btn btn-xs btn-danger btn-refun',
												    'style'=>'color:#ffffff;'
												]
											);
									}	
									
									return $result;
								}
						],
				],
				 
		] );
		?>
<?php //yii\widgets\Pjax::end(); ?>
			<div style="color: #ff0000">อัพเดทล่าสุด <?= date('d/m/Y H:i:s')?></div>
			</div>
		</div>
	</div>
</div>
</div>