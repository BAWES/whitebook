<?php
namespace backend\models;
use backend\models\Vendor;
use Yii;

class VendorItem extends \common\models\VendorItem
{
    public function behaviors()
    {
        return parent::behaviors();
    }

    public static function vendoritemmonthcount()
    {
        $month = date('m');
        $year = date('Y');
        
        $id = Vendor::getVendor('vendor_id');

        return VendorItem::find()
            ->where(['MONTH(created_datetime)' => $month])
            ->andwhere(['vendor_id' => $id])
            ->andwhere(['YEAR(created_datetime)' => $year])
            ->count();
    }

    public static function vendoritemdatecount($vendor_id = '')
    {
        $date = date('d');
        $month = date('m');
        $year = date('Y');
        
        if(!$vendor_id) {
            $vendor_id = Vendor::getVendor('vendor_id');    
        }
        
        return VendorItem::find()
            ->where([
                'MONTH(created_datetime)' => $month,
                'YEAR(created_datetime)' => $year,
                'vendor_id' => $vendor_id,
                'DAYOFMONTH(created_datetime)' => $date
            ])
            ->count();
    }
}
