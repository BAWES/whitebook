<?php
namespace backend\models;
use backend\models\Vendor;
use Yii;

class Vendoritem extends \common\models\Vendoritem
{

    public function behaviors()
    {
        return parent::behaviors();
    }

   public static function vendoritemmonthcount()
    {
        $month=date('m');
        $year=date('Y');
        $id=Vendor::getVendor('vendor_id');
        return  Vendoritem::find()
        ->where(['MONTH(created_datetime)' => $month])
        ->andwhere(['vendor_id' => $id])
        ->andwhere(['YEAR(created_datetime)' => $year])
        ->count();
    }

   public static function vendoritemdatecount()
    {
        $date=date('d');
        $month=date('m');
        $year=date('Y');
        $id=Vendor::getVendor('vendor_id');
        return  Vendoritem::find()
        ->where(['MONTH(created_datetime)' => $month])
        ->andwhere(['YEAR(created_datetime)' => $year])
        ->andwhere(['vendor_id' => $id])
        ->andwhere(['DAYOFMONTH(created_datetime)' => $date])
        ->count();
    }
}
