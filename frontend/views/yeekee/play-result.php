<?php
use yii\helpers\Url;
use yii\bootstrap\Html;

$js = <<<EOT
	
		 
EOT;
$this->registerJs ( $js );
$css = <<<EOT

EOT;
$this->registerCss ( $css );

$today = date('Y-m-d H:i:s');
?>

<div class="card col-xs-12">
	<h4 class="card-header bg-transparent">
		<?= $result?>
	</h4>
	<div class="card-body">
		<?php if($pass==1 && $reason == 3){?>
		<a href="<?= Url::toRoute(['yeekeechit/list-current'])?>" class="btn btn-info">ดูรายการโพย</a>
		<a href="<?= Url::toRoute(['yeekee/play','yeekee_id'=>$yeekee_id])?>" class="btn btn-success">ซื้อหวยต่อ</a>
		<?php }else if($reason=='2'){?>
		<h4><?= $text?></h4>
		<a href="<?= Url::toRoute(['post-credit-transection/create-topup'])?>"  class="btn btn-danger">เติมเครดิต</a>
		<?php }else if($reason=='1'){?>
		<h4><?= $text?></h4>
		<a href="<?= Url::toRoute(['yeekee/index'])?>"  class="btn btn-danger">หมดเวลาเล่น</a>
		<?php }?>
	</div>
		
</div>
