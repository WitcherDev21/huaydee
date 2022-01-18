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

