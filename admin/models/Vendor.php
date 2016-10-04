<?php
namespace admin\models;

use Yii;
use yii\db\ActiveRecord;


class Vendor extends \common\models\Vendor
{
    public $category_id;

    public function behaviors()
    {
        return parent::behaviors();
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

           //All Gridview Status Filter
    public static function Activestatus()
    {
        return $status = ['Active' => 'Activate', 'Deactive' => 'Deactivate'];
    }
}
