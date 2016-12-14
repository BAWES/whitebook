<?php

namespace backend\models;

use Yii;
use common\models\VendorPackages;

class Vendor extends \common\models\Vendor
{
   public function behaviors()
   {
      return parent::behaviors();
   }

   public static function Vendorblockeddays($id)
   {
      $result = Vendor::find()
         ->select('blocked_days')
         ->where([
           'vendor_id' => $id,
           'approve_status' => 'Yes'
         ])
         ->one();

      if($result)
      {
         return $result;
      }  
      else 
      {
         return 0;
      }
   }

   public static function statusCheck($id)
   {
      $result = Vendor::find()
         ->select('vendor_id')
         ->where([
           'vendor_id' => $id,
           'vendor_status' => 'Active'
         ])
         ->one();

      if($result)
      {
         return 1;
      } 
      else
      {
         return 0;
      }
   }
}
