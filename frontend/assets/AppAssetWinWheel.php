<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAssetWinWheel extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
       	//'wheel/examples/basic_code_wheel/main.css'

    ];
    public $js = [    	
    	'https://cdnjs.cloudflare.com/ajax/libs/gsap/latest/TweenMax.min.js',
    	'wheel/Winwheel.js',
		'wheel/custom.js'
    ];
    public $depends = [
        //'yii\web\YiiAsset',
        //'yii\bootstrap\BootstrapAsset',
    ];
}

    