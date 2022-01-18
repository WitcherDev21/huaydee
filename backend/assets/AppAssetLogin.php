<?php

namespace backend\assets;

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
        'http://fonts.googleapis.com/css?family=Roboto:400,100,300,500',
        'backend_login/assets/bootstrap/css/bootstrap.min.css',
        'backend_login/assets/font-awesome/css/font-awesome.min.css',
		'backend_login/assets/css/form-elements.css',
        'backend_login/assets/css/style.css'
    ];
    public $js = [
    		//'backend_login/assets/js/jquery-1.11.1.min.js',
    		//'backend_login/assets/bootstrap/js/bootstrap.min.js',
    		'backend_login/assets/js/jquery.backstretch.min.js',
    		//'backend_login/assets/js/scripts.js'
    	
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}

    