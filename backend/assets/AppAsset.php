<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        //'css/site.css',
//        'theme/css/bootstrap.min.css',
//        'theme/css/bootstrap-responsive.min.css',
        'theme/css/fullcalendar.css',
        'theme/css/matrix-style.css',
        'theme/css/matrix-media.css',
        'theme/font-awesome/css/font-awesome.css',
        'theme/css/jquery.gritter.css',
        'theme/css/app.css',
//    	'bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css',
//        'http://fonts.googleapis.com/css?family=Open+Sans:400,700,800'
    ];
    public $js = [
        'theme/js/excanvas.min.js', 
//        'theme/js/jquery.min.js', 
//        'theme/js/jquery.ui.custom.js', 
//        'theme/js/bootstrap.min.js', 
        //'theme/js/jquery.flot.min.js', 
        //'theme/js/jquery.flot.resize.min.js', 
        'theme/js/jquery.peity.min.js', 
        'theme/js/fullcalendar.min.js', 
        //'theme/js/matrix.js', 
        //'theme/js/matrix.dashboard.js', 
        'theme/js/jquery.gritter.min.js', 
        //'theme/js/matrix.interface.js', 
        //'theme/js/matrix.chat.js', 
        'theme/js/jquery.validate.js', 
        //'theme/js/matrix.form_validation.js', 
//        'theme/js/jquery.wizard.js', 
//        'theme/js/jquery.uniform.js', 
        'theme/js/select2.min.js', 
        //'theme/js/matrix.popover.js', 
        'theme/js/jquery.dataTables.min.js', 
//     	'bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js',
        //'theme/js/matrix.tables.js',
        'sweetalert2-7.24.4/dist/sweetalert2.all.min.js',
        'js/confirmSwal.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
