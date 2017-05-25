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
class HomeAppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/home/stack-interface.css',
        'css/home/socicon.css',
        'css/home/bootstrap.css',
        'css/home/flickity.css',
        'css/home/iconsmind.css',
        'css/home/theme.css',
        'css/home/custom.css?v=1.3',
        'https://fonts.googleapis.com/css?family=Open+Sans:200,300,400,400i,500,600,700'
    ];
    public $js = [
        'js/home/jquery-3.1.1.min.js',
        'js/home/flickity.min.js',
        'js/home/parallax.js',
        'js/home/datepicker.js',
        'js/home/countdown.min.js',
        'js/home/smooth-scroll.min.js',
        'js/home/scripts.js'
    ];
    public $jsOptions = ['position' => \yii\web\View::POS_BEGIN];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
