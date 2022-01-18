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
	<li class="<?= ($active_tab == 'list-current')?'active':''?>">
		<a href="<?= Url::toRoute(['yeekeechit/list-current'])?>" ><i class="fa fa-fw fa-globe"></i> รายการโพยจับยี่กี่ล่าสุด</a>		
	</li>
	<li class="<?= ($active_tab == 'list-history')?'active':''?>">
		<a href="<?= Url::toRoute(['yeekeechit/list-history'])?>" ><i class="fa fa-fw fa-user-circle"></i> รายการโพยจับยี่กี่ย้อนหลัง</a>		
	</li>
	
	<li class="<?= ($active_tab == 'summary')?'active':''?>">
		<a href="<?= Url::toRoute(['yeekeechit/summary'])?>" ><i class="fa fa-fw fa-bell"></i> สรุปยอดแทง</a>		
	</li>
</ul>

