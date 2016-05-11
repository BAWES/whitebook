<?php
namespace backend\models;
use backend\models\Vendor;
use Yii;

class Vendoritem extends \common\models\Vendoritem
{
    /* 
    *
    *   To save created, modified user & date time 
    */
    public function beforeSave($insert)
    {
        if($this->isNewRecord)
        {
           $this->created_datetime = \yii\helpers\Setdateformat::convert(time(),'datetime');
           $this->created_by = \Yii::$app->user->identity->id;
        } 
        else {
           $this->modified_datetime = \yii\helpers\Setdateformat::convert(time(),'datetime');
           $this->modified_by = \Yii::$app->user->identity->id;
        }
           return parent::beforeSave($insert);
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
