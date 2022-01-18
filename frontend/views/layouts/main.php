<?php

/* @var $this View */

/* @var $content string */

use frontend\assets\AppAsset;
use frontend\component\Footer;
use frontend\component\Header;
use frontend\component\SubHeader;
use yii\web\View;
use yii\helpers\Html;
use common\libs\Constants;
use yii\helpers\Url;

AppAsset::register($this);

$this->registerJsVar('creditBalanceUrl', \yii\helpers\Url::to(['credit/get-credit-balance']));
//$this->registerJs('https://cdn.onesignal.com/sdks/OneSignalSDK.js');
//$appKey = Constants::app_key;
//$appCluster = Constants::app_cluster;
//$appChannel = Constants::app_channel;
//$appEvent = Constants::app_event;
//$js = <<<EOT
//var appKey = '$appKey';
//var appCluster = '$appCluster';
//var appEvent = '$appEvent';
//var appChannel = '$appChannel';
//var pusher = new Pusher(appKey, {
//    cluster: appCluster,
//    forceTLS: true
//});
//
//var channel = pusher.subscribe(appChannel);
//channel.bind(appEvent, function(data) {
//    var title = data.title;
//    var message = data.message;
//    swal({
//        title: title,
//        text: message,
//        type: 'warning',
//    }).catch(swal.noop);
//});
//
//var OneSignal = window.OneSignal || [];
//  OneSignal.push(function() {
//    OneSignal.init({
//      appId: "5cf35893-4a55-4eff-81c9-cd3405dc54d5",
//    });
//  });
//EOT;
//$this->registerJs($js);
?>
<?php $this->beginPage() ?>
<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta property="og:url" content="<?= Url::base('https') ?>/frontend/web/user/login" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="RUAYDEE96" />
    <meta property="og:description" content="RUAYDEE96 เว็บหวยออนไลน์ที่ดีที่สุด ของเมืองไทย จ่ายเร็ว การเงินมั่นคง ลุ้นแทงหวยลาวชุด หวยรัฐบาล หวยเวียดนามและอื่นๆอีกมากมาย บริการ 24 ชม." />
    <meta property="og:image" content="<?= Url::base('https') ?>/img/fav.png" />
    <link rel="icon" href="<?= Url::base('https') ?>/img/fav.png" type="image/x-icon">

    <link rel="icon" href="<?= Yii::getAlias('@web/version6/images/logo-symbol.jpg') ?>" type="image/x-icon">
    <title>RUAYDEE96</title>
    <?php $this->head() ?>
    <?= Html::csrfMetaTags() ?>
</head>
<body id="top" ontouchstart="">
<div id="fb-root"></div>
<!-- <script async defer crossorigin="anonymous" src="https://connect.facebook.net/th_TH/sdk.js#xfbml=1&version=v3.2&appId=173108416787211&autoLogAppEvents=1"></script> -->
<div id="loading"></div>
<!-- Google Tag Manager (noscript) -->
<noscript>
    <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-594D2GT"
            height="0" width="0" style="display:none;visibility:hidden"></iframe>
</noscript>
<!-- End Google Tag Manager (noscript) -->
<div id="app">
    <?php $this->beginBody() ?>
    <div class="fixed-bg"></div>
    <div id="wrapper">
        <?= Header::widget(); ?>
        <div id="content">
            <?= SubHeader::widget()?>
            <div id="section-content" class="container" style="margin-bottom: 50px;">
                <?= $content ?>
            </div>
        </div>
        <div class="overlay"></div>
    </div>
    <?= Footer::widget() ?>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
