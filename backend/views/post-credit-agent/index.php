<?php
use yii\helpers\Url;
use yii\bootstrap\Html;
use common\component\ModalCommon;
use yii\base\Widget;
use yii\grid\GridView;
use yii\widgets\Pjax;

$js = <<<EOT
		 
EOT;
$this->registerJs ( $js );
$css = <<<EOT
	
EOT;
$this->registerCss ( $css );

$today = date ( 'Y-m-d H:i:s' );
?>



<div class="col-xs-12">
	<div class="panel">
		<h3>ฝาก-ถอน เอเยนต์</h3>
		<ul class="nav nav-tabs">
		  	<li class="active"><a href="#list-current" data-toggle="tab"><i class="fa fa-fw fa-calendar"></i> รายการ แจ้ง ฝาก-ถอน</a></li>
		  	<li><a href="#list-history" data-toggle="tab"><i class="fa fa-history"></i> รายการ แจ้ง ฝาก-ถอนย้อนหลัง</a></li>
		  	<!-- 
		  	<li><a class="btn  btn-info" href="<?= Url::toRoute(['post-credit-agent/create-topup'])?>"><i class="fa fa-plus-circle"></i> แจ้งเติมเครดิต</a></li>
			<li><a class="btn  btn-danger" href="<?= Url::toRoute(['post-credit-agent/create-withdraw'])?>"><i class="fa fa-minus-circle"></i> แจ้งถอนเครดิต</a></li>
			-->
		</ul>		
		

    
		<div class="tab-content">
		  <div id="list-current" class="tab-pane fade in active">
		   		<iframe style="width: 100%; height: 750px; position: relative;" scrolling="no" src="<?= Url::to(['post-credit-agent/list-current','layout'=>'none'])?>" frameborder="0" allowfullscreen></iframe>
		  </div>
		  <div id="list-history" class="tab-pane fade">
			 	<iframe style="width: 100%; height: 750px; position: relative;" scrolling="no" src="<?= Url::to(['post-credit-agent/list-history','layout'=>'none'])?>" frameborder="0" allowfullscreen></iframe>
		  </div>
	 
		</div>
	</div>
</div>
