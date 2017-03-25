<?php

namespace common\components;

class CFormatter
{
    public static function format($value)
    {
    	if($value == floor($value)) {
    		return 'KD ' . round($value);
    	}

    	return 'KD ' . number_format($value, 3);		
    }
}