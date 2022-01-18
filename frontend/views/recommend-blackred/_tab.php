<?php
use yii\helpers\Url;
use yii\bootstrap\Html;
use yii\base\Widget;
use yii\grid\GridView;
use yii\widgets\Pjax;

use yii\bootstrap\ActiveForm;

$uri = Yii::getAlias('@web');

$js = <<<EOT

EOT;
$this->registerJs ( $js );
$css = <<<EOT

EOT;
$this->registerCss ( $css );
?>

<ul class="nav nav-tabs">
	<li class="<?= ($active_tab == 'index')?'active':''?>">
		<a href="<?= Url::toRoute(['recommend-blackred/index'])?>" ><i class="fa fa-fw fa-globe"></i> ภาพรวม</a>
	</li>
	<li class="<?= ($active_tab == 'member')?'active':''?>">
		<a href="<?= Url::toRoute(['recommend-blackred/member'])?>" ><i class="fa fa-fw fa-user-circle"></i> สมาชิก</a>
	</li>
	<li class="<?= ($active_tab == 'income')?'active':''?>">
		<a href="<?= Url::toRoute(['recommend-blackred/income'])?>" ><i class="fa fa-fw fa-money"></i> รายได้</a>
	</li>
	<li class="<?= ($active_tab == 'withdraw')?'active':''?>">
		<a href="<?= Url::toRoute(['recommend-blackred/withdraw'])?>" ><i class="fa fa-fw fa-bell"></i> แจ้งถอนรายได้</a>
	</li>
</ul>


