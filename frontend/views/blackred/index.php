<?php
use yii\helpers\Url;
use yii\bootstrap\Html;
use common\component\ModalCommon;
use yii\base\Widget;
use yii\grid\GridView;
use yii\widgets\Pjax;
use frontend\assets\AppAssetWheel;
use yii\bootstrap\ActiveForm;

$uri = Yii::getAlias('@web');
AppAssetWheel::register($this);
$js = <<<EOT
window.onload = function () {

            var wheel = new wheelnav("wheelDiv");

            wheel.createWheel(["Thank you", "for", "download", icon.fave]);
            wheel.navigateWheel(3);

        };
EOT;
$this->registerJs ( $js );
$css = <<<EOT
	.btn-play-black:hover{
		background-color:#000000 !important;
		color: #ffffff;
	}

	.btn-play-red:hover{
		background-color:#ff0000 !important;
		color: #ffffff;
	}
EOT;
$this->registerCss ( $css );
?>

<div class="col-md-3">
	<h2>555</h2>
</div>
<div class="5" id="wheelDiv"></div>

<div class="col-md-3 col-sm-12">
	<div class="row">
		<div class="col-xs-12">
			<div class="card">
				<div class="card-body">
										
					<div class="col-xs-12 col-md-6 col-sm-6">
					<button type="button" class="btn btn-default btn-block btn-play-black">ดำ</button></div>
					<div class="col-xs-12 col-md-6 col-sm-6">
					<button type="button" class="btn btn-default btn-block btn-play-red">แดง</button></div>
					
					<?php ActiveForm::begin();?>
					
					<div class="form-group row">
					    <label class="col-sm-3">จำนวน</label>
					    <div class="col-sm-9">
					      <?= Html::input('text','aa','',['class'=>'form-control'])?>
					    </div>
					 </div>
					 <?php ActiveForm::end();?>
				</div>
			</div>
		</div>
	</div>
</div>

