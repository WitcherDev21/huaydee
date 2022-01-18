<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use common\libs\Constants;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\PostCreditTransection */
$csrf = \yii::$app->request->csrfParam;
$token = \yii::$app->request->csrfToken;
$js = <<<EOT
	$('.btn-save').on('click',function(){
	
		var formData = {
				'$csrf':'$token',
				id : $('input[name="id"]').val(),
				status : $('select[name="status"]').val()
		};

		$.ajax({
	        url: $('#popup-update-status').attr('action'),
	        type: 'post',
	        data: formData,
	        success: function (result) {
				if(result == 1){
						alert('save success');
						$('#modal').modal('hide');
				}else{
					alert('save fail');		
				}
				location.reload();
	        },
	        error: function () {
	            alert("Something went wrong");
						$('#modal').modal('hide');
						location.reload()
	        }
			
	    });

	});
						
	$('.btn-close').on('click',function(){
		$('#modal').modal('hide');
	});
EOT;
$this->registerJs ( $js );
$css = <<<EOT

EOT;
$this->registerCss ( $css );

$this->title = $model->id;
$this->params ['breadcrumbs'] [] = [ 
		'label' => 'Post Credit Transections',
		'url' => [ 
				'index' 
		] 
];
$this->params ['breadcrumbs'] [] = $this->title;
?>
<div class="post-credit-transection-view" style="padding: 6px;">

	<h3>ปรับสถานะงาน : <?= Html::encode($this->title) ?></h3>
	<form id="popup-update-status" action="<?= Url::toRoute(['post-credit-member/popup-update-status'])?>">
		<div class="form-group">
			<label>วันที่ เวลา</label> 
			<input type="text" class="form-control" value="<?= date('d/m/Y H:i')?>" disabled>
		</div>
		<div class="form-group">
			<label >รายชื่อผู้อัพเดทงาน</label> 
			<input type="text" class="form-control" value="<?= Yii::$app->user->identity->username;?>" disabled>
		</div>
		<div class="form-group">
			<label >สถานะงาน</label>
			<?= Html::dropDownList('status', $model->status,Constants::$statusAction,['class'=>'form-control'])?>
		</div>
		<a class="btn btn-success btn-save">บันทึก</a>
		<a class="btn btn-danger btn-close">ปิด</a>
		<?= Html::input('hidden','id',$model->id)?>
	</form>

	<p>
        <?php //= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?php
								// = Html::a('Delete', ['delete', 'id' => $model->id], [
								// 'class' => 'btn btn-danger',
								// 'data' => [
								// 'confirm' => 'Are you sure you want to delete this item?',
								// 'method' => 'post',
								// ],
								// ]) 								?>
    </p>




</div>
