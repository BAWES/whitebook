<?php
namespace backend\models;

use Yii;
use backend\models\Vendorpackages;

class Vendor extends \common\models\Vendor
{

    public static function Vendorblockeddays($id)
    {
        $result= Vendor::find()->select('blocked_days')->where(['vendor_id' => $id,'approve_status' => 'Yes'])->one();
        if($result){
            return $result;
        }
        else{
            return 0;
        }
    }

    public static function statusCheck($id){
    $result= Vendor::find()->select('vendor_id')->where(['vendor_id' => $id,'vendor_status' => 'Active'])->one();
        if($result){
            return 1;
        }
        else{
            return 0;
           }
   }

    public static function packageCheck($id, $check_vendor = false){

        // $check_vendor variable for frontend filter IMPORATNT
        $today=date('Y-m-d');
        
        $datetime = Vendorpackages::find()->select(['DATE_FORMAT(package_start_date,"%Y-%m-%d") as package_start_date','DATE_FORMAT(package_end_date,"%Y-%m-%d") as package_end_date','vendor_id'])
          ->where(['vendor_id' => $id])
          ->asArray()
          ->all();
          

        $blocked_dates=array();
         if(!empty($datetime)){
         foreach ($datetime as $d)
         {
           $date = $date1 = $d['package_start_date'];
           $end_date = $end_date1 =$d['package_end_date'];

           while (strtotime($date) <= strtotime($end_date)) {
            $blocked_dates[]=$date;
            $date = date ("Y-m-d", strtotime("+1 day", strtotime($date)));
            }
        }

        $available = in_array($today, $blocked_dates);
        if($available)
        {
            /* if vendor package not expired */
            if($check_vendor !="")
            {
                return $datetime[0]['vendor_id'];die;
            }
            return "1"; die;
        }
        else
        {

            return "0";die;
        }

        }
    }

    public static function getVendor_packagedate($id)
    {
        $id = 1;  // id for testing // check while dynamic
        $datetime = Vendorpackages::find()->select(['DATE_FORMAT(package_start_date,"%Y-%m-%d") as package_start_date','DATE_FORMAT(package_end_date,"%Y-%m-%d") as package_end_date'])
          ->where(['vendor_id' => $id])
          ->asArray()
          ->all();
          
        $blocked_dates=array();
         if(!empty($datetime)){
         foreach ($datetime as $d)
         {
           $date = $date1 = $d['package_start_date'];
           $end_date = $end_date1 =$d['package_end_date'];

           while (strtotime($date) <= strtotime($end_date)) {
            $blocked_dates[]=$date;
        $date = date ("Y-m-d", strtotime("+1 day", strtotime($date)));

            }
        }
    }
        $max = max(array_map('strtotime', $blocked_dates));
        return date('d-m-Y', $max);die;
        die;
    }
}
