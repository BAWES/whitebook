<?php
namespace admin\models;

use Yii;

class Vendor extends \common\models\Vendor
{
    
   public static function vendorcount()
    {
        return Vendor::find()->where(['trash' => 'Default'])->count();
    }

    public static function vendormonthcount()
    {
        $month=date('m');
        $year=date('Y');
        return  Vendor::find()
        ->where(['MONTH(created_datetime)' => $month])
        ->andwhere(['YEAR(created_datetime)' => $year])
        ->count();
    }

     public static function vendordatecount()
    {
        $date=date('d');
        $month=date('m');
        $year=date('Y');
        return  Vendor::find()
        ->where(['MONTH(created_datetime)' => $month])
        ->andwhere(['YEAR(created_datetime)' => $year])
        ->andwhere(['DAYOFMONTH(created_datetime)' => $date])
        ->count();
    }

    public static function vendorperiod()
    {
        $contractDateBegin=date('Y-m-d');
        $date = strtotime(date("Y-m-d", strtotime($contractDateBegin)) . " +60 days");
        $contractDateEnd = date('Y-m-d',$date);
        $period= Vendor::find()
            ->where(['>=', 'package_end_date', $contractDateBegin])
            ->andwhere(['<=', 'package_end_date', $contractDateBegin])
            ->one();
        return  $period;
    }


    public static function getvendorname($id){
        $vendorname= Vendor::find()
            ->where(['vendor_id'=>$id])
            ->all();
            $vendorname= \yii\helpers\ArrayHelper::map($vendorname,'vendor_id','vendor_name');
            return $vendorname;
    }


}
