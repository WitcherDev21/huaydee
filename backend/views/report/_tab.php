<?php
/* @var $active_tab */

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
	<li class="<?= ($active_tab == 'list-current') ? 'active' : '' ?>">
		<a href="<?= Url::toRoute(['list-current'])?>" ><i class="fa fa-fw fa-globe"></i> รายงานเครดิต</a>
	</li>
	<li class="<?= ($active_tab == 'list-history') ? 'active' : '' ?>">
		<a href="<?= Url::toRoute(['list-history'])?>" ><i class="fa fa-fw fa-user-circle"></i> รายงานเครดิตย้อนหลัง</a>
	</li>
    <li class="<?= ($active_tab == 'list-person') ? 'active' : '' ?>">
        <a href="<?= Url::toRoute(['list-person'])?>" ><i class="fa fa-fw fa-user-circle"></i> รายงานเครดิตที่ได้รับผลกระทบจาก Network</a>
    </li>
</ul>
