<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * AssetBundle for css class containing layout adjustments for RTL / Arabic language
 * @author Khalid Al-Mutawa <khalid@bawes.net>
 */
class ArabicAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    //CSS will be added before closing </head> tag
    public $css = [
        'css/style_arabic.css?v=1.4',
    ];

    //This arabic asset is an adjustment for TemplateAsset in the case of Arabic language
    public $depends = [
        'frontend\assets\AppAsset',
    ];
}
