<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAssetGuest extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    		'homepage/assets/fonts/fontawesome/font-awesome.min.css',
    		'homepage/assets/fonts/pe-icon/pe-icon.css',
    		'homepage/assets/vendors/bootstrap/grid.css',
    		'homepage/assets/vendors/magnific-popup/magnific-popup.min.css',
    		'homepage/assets/vendors/swiper/swiper.css',
    		'https://fonts.googleapis.com/css?family=Open+Sans:400,700|Oswald:400,600|Playfair+Display:400i',
    		'homepage/assets/css/main.css',	
    		'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'

    ];
    public $js = [
    		'homepage/assets/vendors/jquery/jquery.min.js',
    		'homepage/assets/vendors/imagesloaded/imagesloaded.pkgd.js',
    		'homepage/assets/vendors/isotope-layout/isotope.pkgd.js',
    		'homepage/assets/vendors/jquery.matchHeight/jquery.matchHeight.min.js',
    		'homepage/assets/vendors/magnific-popup/jquery.magnific-popup.min.js',
    		'homepage/assets/vendors/masonry-layout/masonry.pkgd.js',
    		'homepage/assets/vendors/swiper/swiper.jquery.js',
    		'homepage/assets/vendors/jquery-one-page/jquery.nav.js',
    		'homepage/assets/vendors/menu/menu.js',
    		'homepage/assets/vendors/jquery.waypoints/jquery.waypoints.min.js',
    		'homepage/assets/js/main.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}

    