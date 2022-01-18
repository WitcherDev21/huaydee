<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAssetBreakingNews extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'https://fonts.googleapis.com/css?family=Roboto+Condensed',
        'https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/themes/ui-darkness/jquery-ui.css',
        'https://www.jqueryscript.net/css/jquerysctipttop.css'
    ];
    public $js = [
        'https://code.jquery.com/jquery-1.11.3.min.js',
        'https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js',
        'Small-News-Scroller-Plugin-with-jQuery-jQuery-UI-Telex/dist/telex.js'
    	
    ];
    public $depends = [
        //'yii\web\YiiAsset',
        //'yii\bootstrap\BootstrapAsset',
    ];
}

    