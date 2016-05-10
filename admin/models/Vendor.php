<?php
namespace admin\models;

use Yii;

class Vendor extends \common\models\Vendor
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

        public function statusImageurl($img_status)
    {
        if($img_status == 'Active')     
        return \yii\helpers\Url::to('@web/uploads/app_img/active.png');
        return \yii\helpers\Url::to('@web/uploads/app_img/inactive.png');
    }

    // Status Image title
    public function statusTitle($status)
    {           
    if($status == 'Active')     
        return 'Activate';
        return 'Deactivate';
    }


}
