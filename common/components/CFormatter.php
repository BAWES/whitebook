<?php

namespace common\components;

class CFormatter
{
    public static function format($value)
    {
        return 'KD ' . number_format($value, 3);
    }
}