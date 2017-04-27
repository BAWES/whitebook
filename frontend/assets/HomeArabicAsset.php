<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * AssetBundle for css class containing layout adjustments for RTL / Arabic language
 * @author Khalid Al-Mutawa <khalid@bawes.net>
 */
class HomeArabicAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    //CSS will be added before closing </head> tag
    public $css = [
        'css/home/theme-rtl.css',
    ];

    //This arabic asset is an adjustment for TemplateAsset in the case of Arabic language
    public $depends = [
        'frontend\assets\HomeAppAsset',
    ];
}
