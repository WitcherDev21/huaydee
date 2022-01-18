<?php
use yii\helpers\Url;
use yii\helpers\Html;
use backend\component\CoreFunctions;

$identity = \Yii::$app->user->getIdentity ();
$baseUri = \Yii::getAlias ( '@web' );

$user = \Yii::$app->user;
$uri = Yii::$app->controller->getRoute ();
?>
<div id="sidebar">
	<ul>
<?php

foreach ( $arrMenu as $menu ) {
	$ch = in_array ( $uri, $menu ['group'] );
	$active = '';
	$select = '';
	if ($ch) {
		$active = 'active';
		$select = 'selected';
	}
	?>
<li class="<?= $active?>">
    <a href="<?= Url::toRoute([$menu['uri']]);?>">
        <i class="<?= $menu['icon']?>"></i>
        <span><?= $menu['title']?> </span>
        <?php echo (!empty($menu['call_function']) ? CoreFunctions::_call($menu['call_function']) : ''); ?>
    </a>
</li>
                
<?php
}
?>
<!--		<li class="content"><span>แบนด์วิดท์การใช้งานรายเดือน</span>
			<div
				class="progress progress-mini progress-danger active progress-striped">
				<div style="width: 77%;" class="bar"></div>
			</div> <span class="percent">77%</span>
			<div class="stat">21419.94 / 14000 MB</div></li>
		<li class="content"><span>การใช้พื้นที่ดิสก์</span>
			<div class="progress progress-mini active progress-striped">
				<div style="width: 87%;" class="bar"></div>
			</div> <span class="percent">87%</span>
			<div class="stat">604.44 / 4000 MB</div></li>-->
	</ul>
</div>