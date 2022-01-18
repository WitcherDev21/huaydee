<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use frontend\assets\AppAssetRegister;
use yii\helpers\Url;


AppAssetRegister::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta property="og:url" content="<?= Url::base('https') ?>/frontend/web/user/login" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="RUAYDEE96" />
    <meta property="og:description" content="RUAYDEE96 เว็บหวยออนไลน์ที่ดีที่ ของเมืองไทย จ่ายเร็ว การเงินมั่นคง ลุ้นแทงหวยลาวชุด หวยรัฐบาล หวยเวียดนามและอื่นๆอีกมากมาย บริการ 24 ชม." />
    <meta property="og:image" content="<?= Url::base('https') ?>/img/fav.png" />
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <style type="text/css">
    	body {
		    background: #333 !important;
		    font-family: tahoma;
		    color: #ccc;
		}
    </style>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
	<div class="container">
		<div class="row">
    		<?=  $content ?>
		</div>
	</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
