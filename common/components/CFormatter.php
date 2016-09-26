<?php

namespace common\components;

class CFormatter extends \yii\i18n\Formatter
{
    public function asCurrency($value, $currency = NULL, $options = [], $textOptions = [])
    {
        return 'KD ' . number_format($value, 3);
    }
}