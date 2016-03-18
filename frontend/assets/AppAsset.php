<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $sourcePath = '@frontend/web/';
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/style.css',
        'fonts/flaticon/flaticon.css',
        'css/demo.css',
        'css/media_style.css',
        'css/owl.carousel.css',
        'css/ma5-mobile-menu.css',
        'css/bootstrap-select.min.css',
        'css/datepicker_popup.css'        
    ];
    public $js = [        
        'https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js',
        'https://code.jquery.com/ui/1.11.3/jquery-ui.min.js',
        'js/modernizr.js',
        'js/bootstrap.min.js',
        'js/jquery.flexslider.js',
        'js/classie.js',
        'js/main.js',
        'js/search.js',
        'js/owl.carousel.js',
        'js/bootstrap-datepicker.js',
        'js/bootstrap-select.js'
        
        
    ];
    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
