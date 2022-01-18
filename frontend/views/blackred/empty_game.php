<?php
use yii\helpers\Url;
use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
use frontend\assets\AppAssetWinWheel;
use common\libs\Constants;

$uri = Yii::getAlias('@web');

$js = <<<EOT


EOT;
$this->registerJs ( $js );
$css = <<<EOT

EOT;
$this->registerCss ( $css );
?>

<div class="col-md-12 col-sm-12">
	<div class="row">
		<div class="col-xs-12">
			<div class="card">
				<h3>อยู่ในช่วงพัฒนา</h3><br>
				<a href="<?= Url::toRoute(['lotto/index'])?>" class="btn btn-danger col-md-2 col-sm-3">กลับ</a>
			</div>
		</div>
	</div>
</div>


