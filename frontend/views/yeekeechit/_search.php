<?php
use yii\helpers\Url;
use yii\bootstrap\Html;
use yii\widgets\ActiveForm;

$js = <<<EOT
	
		 
EOT;
$this->registerJs ( $js );
$css = <<<EOT

EOT;
$this->registerCss ( $css );

$user_id = \Yii::$app->user->identity;
?>

<div class="row">
	<div class="col-sm-6 col-sm-offset-6">
		<br>
	    <?php $form = ActiveForm::begin([
	        'method' => 'get',
	        'options' => ['data-pjax' => true ,'class'=>'form-horizontal']
	    ]); 

	    ?>
	    <div class="row">
		    <div class="col-md-9">
				<div class="form-group">
				    <label class="col-md-3 col-sm-3 control-label">งวดที่</label>
				    <div class="col-md-9  col-sm-9">
				      <?= Html::activeInput('text',$searchModel, 'round',['class'=>'form-control','placeholder'=>'1 - 88']) ?>
				    </div>
				</div>
			</div>
			<div class="col-md-3">
				<button class="btn btn-info" type="submit"><i class="glyphicon glyphicon-search"></i> ค้นหา</button>
			</div>
		</div>
	    <?php ActiveForm::end(); ?>
	</div>
</div>