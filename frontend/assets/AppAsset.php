<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'version6/css/style.css?1561989826',
        'version6/css/pageup.css?1549581359',
        'version6/css/bootstrap.css',
        'version6/css/bootstrap-select.min.css',
        'version6/css/all.css',
        'version6/css/bs-stepper.css',
        'version6/css/odometer-theme-default.css',
        'version6/css/flag-icon.css',
        'version6/css/sn-icon.css',
        'version6/css/stepper.css',
        'version6/css/jquery.mCustomScrollbar.min.css',
    	'version6/css/style.css?1562253390',
        'version6/css/toastr.css',
        'version6/css/pageup.css?1562253390',
        'version6/css/jquery.marquee.css',
        'version6/js/sweetalert2/sweetalert2.min.css',
    ];
    public $js = [
     	'version6/js/popper.min.js',
        'version6/js/bootstrap.min.js',
     	'version6/js/bootstrap-select.min.js',
     	'version6/js/loadingoverlay.min.js',
     	'version6/js/loadingoverlay_progress.min.js',
    	'version6/js/odometer.min.js',
        'version6/js/bs-stepper.min.js',
        'version6/js/jquery.mCustomScrollbar.concat.min.js',
        'version6/js/numeral.min.js',
        'version6/js/jquery.countdown.min.js',
        'version6/js/countdown.js',
        'version6/js/html2canvas-1.0.0-alpha.12.min.js',
        'version6/js/download2.js',
        'version6/js/bs-breakpoints.min.js',
        'version6/js/jquery.marquee.js',
        'version6/js/index.js',
        'version6/js/toastr.min.js',
        'version6/js/signalr.min.js',
        'version6/js/pusher-4.1.min.js',
        'version6/js/sweetalert2/sweetalert2.min.js',
        'version6/js/member.js?1562253392',
        'version6/js/nodoubletabzoom.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}

    