<?php
use yii\widgets\Pjax;
use yii\grid\GridView;
?>
<style>
<!--
.h1, .h2, .h3, .h4, .h5, .h6, h1, h2, h3, h4, h5, h6 {
	font-family: inherit;
	font-weight: 500;
	line-height: 1.1;
	color: inherit;
}

h1 {
	font-size: 30px;
}

h3, h4, h5 {
	margin-top: 5px;
	font-weight: 600;
}

h1, h2, h3, h4, h5, h6 {
	font-weight: 100;
}

h3 {
	font-size: 16px;
}

.card {
	padding: 20px;
	margin-bottom: 20px;
}
-->
</style>
<div class="tab-0 animated">
	<h2 class="text-center ">จับยี่กี รอบที่ <?= $yeekee->round?></h2>

	<h2 class="text-danger text-center">ปิดรับการทายผลตัวเลขออกรางวัล</h2>

	<div class="row">
		<div class="col-md-4 col-md-offset-2">
			<div class="text-center card">
				<h3>ผลรวมยิงเลข</h3>
				<h1 class="text-center">
					<span class="text-center"><?= $yeekeePost ?></span>
				</h1>
			</div>
		</div>
		<div class="col-md-2 col-md-4">
			<div class="text-center card">
				<h3>เลขแถวที่ 16</h3>
				<h1 class="text-center">
					<span class="text-center"><?= str_pad($yeekee->yeekeePostId16->post_num,5,'0',STR_PAD_LEFT);?></span>
				</h1>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-2 col-md-4 col-md-offset-2">
			<div class="text-center card">
				<h3>สมาชิกยิงเลขได้ลำดับที่ 1</h3>
				<h1 class="text-info"><?= $yeekee->yeekeePostId1->post_name?></h1>
			</div>
		</div>
		<div class="col-md-2 col-md-4">
			<div class="text-center card">
				<h3>สมาชิกยิงเลขได้ลำดับที่ 16</h3>
				<h1 class="text-danger"><?= $yeekee->yeekeePostId16->post_name?></h1>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-2 col-md-offset-2">
			<div class="text-center card">
				<h3>ผลรางวัล</h3>
				<h1 class="">
					<?= $yeekee->getResults('other');?><span class="text-success"><?= $yeekee->getResults('two_under'); ?></span><span class="text-danger"><?= $yeekee->getResults('three_top');?></span>
				</h1>
			</div>
		</div>
		<div class="col-md-4">
			<div class="text-center card">
				<h3>สามตัวบน</h3>
				<h1 class="text-danger"><?= $yeekee->getResults('three_top');?></h1>
			</div>
		</div>
		<div class="col-md-2">
			<div class="text-center card">
				<h3>สองตัวล่าง</h3>
				<h1 class="text-success"><?= $yeekee->getResults('two_under'); ?></h1>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<?php Pjax::begin();?>
			<?php
			echo GridView::widget ( [ 
					'dataProvider' => $dataProvider,
					// 'filterModel' => $searchModel,
					'columns' => [ 
							[
									'class' => 'yii\grid\SerialColumn',
									'header' => 'ลำดับ',
							],
							
							[ 
									'label' => 'เลข',
									'value' => function ($model, $key, $index, $column) {
										return str_pad($model->post_num,5,'0',STR_PAD_LEFT);
									} 
							],
							[ 
									'label' => 'สมาชิก',
									'format' => 'html',
									'value' => function ($model, $key, $index, $column) use ($yeekee) {
										$text = substr_replace($model->post_name,'***',2,6);

										if($yeekee->yeekee_post_id_1 == $model->id){
											$text = '<span class="text-info"><strong>'.$model->post_name.'</strong></span>';
										}else if($yeekee->yeekee_post_id_16 == $model->id){
											$text = '<span class="text-danger"><strong>'.$model->post_name.'</strong></span>';
										}
																				
										return $text;
									} 
							],
							[ 
									'label' => 'เวลาส่ง',
									'value' => function ($model, $key, $index, $column) {
										return date('d/m/Y H:i:s',strtotime($model->create_at));
									} 
							],
							
					],
					'rowOptions' => function ($yeekeePost, $index, $widget, $grid) use ($yeekee){
						$class= '';
						if($yeekee->yeekee_post_id_1 == $yeekeePost->id){
							$class = 'info';
						}else if($yeekee->yeekee_post_id_16 == $yeekeePost->id){
							$class = 'danger';
						}
						return ['class'=>$class];
					},
			] );
			?>
		<?php yii\widgets\Pjax::end(); ?>
		</div>
	</div>
	
<!-- 	<h3 class="text-primary-new text-center">ระบบกำลังจ่ายเงินให้สมาชิกที่แทงเลขถูก</h3> -->
<!-- 	<div class="text-center"> -->
<!-- 		<div class="sk-spinner sk-spinner-wave"> -->
<!-- 			<div class="sk-rect1"></div> -->
<!-- 			<div class="sk-rect2"></div> -->
<!-- 			<div class="sk-rect3"></div> -->
<!-- 			<div class="sk-rect4"></div> -->
<!-- 			<div class="sk-rect5"></div> -->
<!-- 		</div> -->
<!-- 	</div> -->




</div>