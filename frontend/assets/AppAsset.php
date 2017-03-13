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
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/style.css?v=1.38',
        'fonts/flaticon/flaticon.css',
        'css/demo.css?v=1.2',
        'css/media_style.css?v=1.9',
        'css/owl.carousel.css',
        'css/ma5-mobile-menu.css',
        'css/bootstrap-select.min.css',
        'css/datepicker_popup.css',
        'css/font-awesome.min.css'
    ];
    public $js = [
        'js/jquery.min.js',
        'js/jquery-ui.min.js',
        'js/modernizr.js',
        'js/bootstrap.min.js',
        'js/jquery.flexslider.js',
        'js/classie.js',
        'js/main.js?v=1.19',
        'js/owl.carousel.js',
        'js/bootstrap-datepicker.js',
        'js/bootstrap-select.js'
    ];
    public $jsOptions = ['position' => \yii\web\View::POS_BEGIN];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
