<?php

/* @var $this View */

/* @var $content string */

use frontend\assets\AppAsset;
use frontend\component\Footer;
use frontend\component\Header;
use frontend\component\SubHeader;
use yii\web\View;
use yii\helpers\Html;

AppAsset::register($this);
$this->registerJsVar('url_thai_shared', \yii\helpers\Url::to(['yeekee/result']));
?>
<?php $this->beginPage() ?>
    <!doctype html>
    <html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
        <link rel="icon" href="<?= Yii::getAlias('@web/version6/images/logo-symbol.jpg') ?>" type="image/x-icon">
        <title>RUAYDEE96</title>
        <?php $this->head() ?>
        <?= Html::csrfMetaTags() ?>
    </head>
    <body id="top" ontouchstart="">
    <style>
        .number-is-duplicate.number-is-duplicate-active {
            background: #efda7e !important;
            color: #000 !important;
        }
        .multiply-change {
            background: #dc948e !important;
            color: #000 !important;
        }
        .multiply-close {
            background: #5e5e5d !important;
            color: #000 !important;
        }
    </style>
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
                <div id="section-content" class="container">
                    <?= $content ?>
                </div>
            </div>
            <div class="overlay"></div>
        </div>
        <?= $this->render('/yeekee/modal_poy_memo_number') ?>
        <?= $this->render('/yeekee/modal_price') ?>
        <?= $this->render('/yeekee/modal_send_poy') ?>
        <?= $this->render('/yeekee/modal_confirm_reset') ?>
        <?= $this->render('/yeekee/modal_confirm_del_one') ?>
        <?= $this->render('/yeekee/modal_delete_duplicate') ?>
        <?= $this->render('/yeekee/modal_print_poy') ?>

        <?= Footer::widget() ?>
    </div>
    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>