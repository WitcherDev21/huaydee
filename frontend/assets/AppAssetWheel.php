<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAssetWheel extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
       	//'wheel/examples/pins_and_sound_wheel/main.css'
   		'wheelnav\main.css'
    ];
    public $js = [    	
    	//'wheel/Winwheel.js',
    	//'http://cdnjs.cloudflare.com/ajax/libs/gsap/latest/TweenMax.min.js',
    		'wheelnav\raphael.min.js',
    		'wheelnav\raphael.icons.min.js',
    		'wheelnav\wheelnav.min.js'
    ];
    public $depends = [
        //'yii\web\YiiAsset',
        //'yii\bootstrap\BootstrapAsset',
    ];
}

    