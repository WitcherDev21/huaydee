<?php
use yii\helpers\Url;
use yii\bootstrap\Html;
use yii\base\Widget;
use yii\grid\GridView;
use yii\widgets\Pjax;

$csrf = \yii::$app->request->csrfParam;
$token = \yii::$app->request->csrfToken;
$js = <<<EOT
	$('.btn-confirm').on('click',function(){
	
		var formData = {
				'$csrf':'$token',
				yeekeechit_id : $('input[name="yeekeechit_id"]').val()
		};

		$.ajax({
	        url: $('#popup-update').attr('action'),
	        type: 'post',
	        data: formData,
	        success: function (result) {
				if(result == 1){
						alert('save success');
						$('#modal').modal('hide');
				}
	        },
	        error: function () {
	            alert("Something went wrong");
						$('#modal').modal('hide');
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

$today = date ( 'Y-m-d H:i:s' );
?>



<div class="col-xs-12">
	<div class="panel">
		<form id="popup-update" action="<?= Url::toRoute(['yeekeechit/cancel-yeekeechit'])?>">
			<h3 class="text-center">ยืนยันการคืนโพย</h3><br>
			<a class="btn btn-danger btn-confirm">คืนโพย</a>
			<a class="btn btn-default btn-close">ปิด</a>
			<?= Html::input('hidden','yeekeechit_id',$yeekeechit_id);?>
		</form>
	</div>
</div>
