<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAssetLogin extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        //'css/site.css',
        'https://fonts.googleapis.com/css?family=Roboto:400,100,300,500',
        'frontend_login/assets/bootstrap/css/bootstrap.min.css',
        'frontend_login/assets/font-awesome/css/font-awesome.min.css',
		'frontend_login/assets/css/form-elements.css',
        'frontend_login/assets/css/style.css'
    ];
    public $js = [
    		//'frontend_login/assets/js/jquery-1.11.1.min.js',
    		//'frontend_login/assets/bootstrap/js/bootstrap.min.js',
    		'frontend_login/assets/js/jquery.backstretch.min.js',
    		//'frontend_login/assets/js/scripts.js'
    	
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}

    