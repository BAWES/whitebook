<?php

namespace backend\models;

use Yii;

class Booking extends \common\models\Booking
{
	public static function getDayGrpah()
	{
		return Booking::find()
			->select('count(booking_id) as total, DATE(created_datetime) as date')
			->where(['vendor_id' => Yii::$app->user->getId()])
			->andWhere('YEAR(created_datetime) = YEAR(NOW()) AND MONTH(created_datetime) = MONTH(NOW())')			
			->groupBy('DAY(created_datetime)')
			->asArray()
			->all();
    }
    
    public static function getMonthGrpah()
    {
        return Booking::find()
			->select('count(booking_id) as total, MONTHNAME(created_datetime) as date')
			->where(['vendor_id' => Yii::$app->user->getId()])
			->andWhere('YEAR(created_datetime) = YEAR(NOW())')			
			->groupBy('MONTH(created_datetime)')
			->asArray()
			->all();
    }

    public static function getYearGrpah()
    {
        return Booking::find()
			->select('count(booking_id) as total, YEAR(created_datetime) as date')
			->where(['vendor_id' => Yii::$app->user->getId()])
			->groupBy('YEAR(created_datetime)')
			->asArray()
			->all();
    }
}