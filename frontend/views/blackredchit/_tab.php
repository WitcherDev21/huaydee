<?php
use yii\helpers\Url;

$uri = Yii::getAlias('@web');

$js = <<<EOT

EOT;
$this->registerJs ( $js );
$css = <<<EOT

EOT;
$this->registerCss ( $css );
?>

<ul class="nav nav-tabs">
	<li class="<?= ($active_tab == 'list-current')?'active':''?>">
		<a href="<?= Url::toRoute(['blackredchit/list-current'])?>" ><i class="fa fa-fw fa-globe"></i> รายการโพยดำแดงล่าสุด</a>
	</li>
	<li class="<?= ($active_tab == 'list-history')?'active':''?>">
		<a href="<?= Url::toRoute(['blackredchit/list-history'])?>" ><i class="fa fa-fw fa-user-circle"></i> รายการโพยจดำแดงย้อนหลัง</a>
	</li>
	
	<li class="<?= ($active_tab == 'summary')?'active':''?>">
		<a href="<?= Url::toRoute(['blackredchit/summary'])?>" ><i class="fa fa-fw fa-bell"></i> สรุปยอดแทง</a>
	</li>
</ul>

