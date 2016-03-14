<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */
namespace backend\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $sourcePath = '@backend/web/themes/default/';
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
    'css/style.css',    
    ];
    public $js = [    
     'plugins/jquery-1.8.3.min.js',
     'plugins/jquery-ui/jquery-ui-1.10.1.custom.min.js',
     'plugins/boostrapv3/js/bootstrap.min.js',
     'plugins/breakpoints.js',
     'plugins/jquery-unveil/jquery.unveil.min.js',
     'plugins/jquery-scrollbar/jquery.scrollbar.min.js',
     'plugins/jquery-numberAnimate/jquery.animateNumbers.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
