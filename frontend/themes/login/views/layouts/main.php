<?php

/* @var $this \yii\web\View */

/* @var $content string */

use frontend\assets\AppAssetLoginV6;
use yii\helpers\Url;

AppAssetLoginV6::register($this);
?>
<?php $this->beginPage() ?>
<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta property="og:url" content="<?= Url::base('https') ?>/frontend/web/user/login" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="RUAYDEE96" />
    <meta property="og:description" content="RUAYDEE96 เว็บหวยออนไลน์ที่ดีที่สุด ของเมืองไทย จ่ายเร็ว การเงินมั่นคง ลุ้นแทงหวยลาวชุด หวยรัฐบาล หวยเวียดนามและอื่นๆอีกมากมาย บริการ 24 ชม." />
    <meta property="og:image" content="<?= Url::base('https') ?>/img/fav.png" />
    <link rel="icon" href="<?= Url::base('https') ?>/img/fav.png" type="image/x-icon">
    <?php $this->head() ?>
    <title>RUAYDEE96 เว็บหวยออนไลน์ที่ดีที่สุด ของเมืองไทย จ่ายเร็ว การเงินมั่นคง ลุ้นแทงหวยลาวชุด หวยรัฐบาล หวยเวียดนามและอื่นๆอีกมากมาย บริการ 24 ชม.</title>
</head>
<body id="top" ontouchstart="">
<?php $this->beginBody() ?>
<?= $content ?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
