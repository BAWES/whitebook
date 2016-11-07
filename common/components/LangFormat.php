<?php

namespace common\components;

use Yii;
class LangFormat
{
    public static function format($english,$arabic)
    {
        return (Yii::$app->language == "ar" && $arabic !='') ? ucfirst($arabic) : ucfirst($english);
    }
}