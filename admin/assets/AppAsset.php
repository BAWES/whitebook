<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */
namespace admin\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web/themes/default/';

    public $css = [
        'css/responsive.css',
        'css/custom-icon-set.css',
        'css/animate.min.css',
        'plugins/pace/pace-theme-flash.css',
        'plugins/boostrapv3/css/bootstrap.min.css',
        'plugins/boostrapv3/css/bootstrap-theme.min.css',
        'plugins/font-awesome/css/font-awesome.css',
        'plugins/jquery-scrollbar/jquery.scrollbar.css',
        'css/style.css?v=1.4',
    ];

    public $js = [
        'plugins/jquery-ui/jquery-ui-1.10.1.custom.min.js',
        'plugins/boostrapv3/js/bootstrap.min.js',
        'plugins/breakpoints.js',
        'plugins/jquery-unveil/jquery.unveil.min.js',
        'plugins/jquery-scrollbar/jquery.scrollbar.min.js',
        'plugins/jquery-numberAnimate/jquery.animateNumbers.js',
        'js/core.js'        
    ];

    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
    
    public $depends = [
        'yii\web\YiiAsset',
        'yii\web\jQueryAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
