<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAssetRegister extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        //'css/site.css',
        //'theme/styles/bootstrap4/bootstrap.min.css',
        //'theme/plugins/font-awesome-4.7.0/css/font-awesome.min.css',
        //'theme/plugins/OwlCarousel2-2.2.1/owl.carousel.css',
        //'theme/plugins/OwlCarousel2-2.2.1/owl.theme.default.css',
        //'theme/plugins/OwlCarousel2-2.2.1/animate.css',
        //'theme/styles/main_styles.css',
        //'theme/styles/responsive.css',
        //'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'
        //'theme/plugins/bootstrap.min.css'
    ];
    public $js = [
        //'https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js',
        //'theme/plugins/jquery.min.js',
        //'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js',
    	//'theme/plugins/bootstrap.min.js',
        //'theme/js/jquery-3.2.1.min.js',
        //'theme/styles/bootstrap4/popper.js',
        //'theme/styles/bootstrap4/bootstrap.min.js',
        //'theme/plugins/OwlCarousel2-2.2.1/owl.carousel.js',
        //'theme/plugins/easing/easing.js',
        //'theme/plugins/parallax-js-master/parallax.min.js',
        //'theme/js/custom.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}

    