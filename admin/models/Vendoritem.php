<?php
namespace admin\models;

use Yii;
use yii\helpers\ArrayHelper;
use admin\models\Vendor;

class Vendoritem extends \common\models\Vendoritem
{

    public static function getVendorName($id)
    {       
        $model = Vendor::find()->where(['vendor_id'=>$id])->one();
        return $model->vendor_name;
    }

    public static function vendorpriorityitemitem($id)
    {       
            $item= Vendoritem::find()
            ->select(['item_id','item_name'])
            ->where(['=', 'item_id',$id])
            ->andwhere(['trash' =>'Default','item_for_sale' =>'Yes'])
            ->all();
            $item=ArrayHelper::map($item,'item_id','item_name');
            return $item;
    }


    public static function loadsubcategoryvendoritem($subcategory)
    {       
            $item= Vendoritem::find()
            ->where(['trash' =>'Default','item_for_sale' =>'Yes','subcategory_id'=>$subcategory])
            ->all();
            $item=ArrayHelper::map($item,'item_id','item_name');
            return $item;
    }

    public static function itemcount()
    {   
        return Vendoritem::find()->where(['trash' => 'Default'])->count();
    }

    public static function itemmonthcount()
    {
        $month=date('m');
        $year=date('Y');
        return  Vendoritem::find()
        ->where(['MONTH(created_datetime)' => $month])
        ->andwhere(['YEAR(created_datetime)' => $year])
        ->count();
    } 

    public static function itemdatecount()
    {
        $date=date('d');
        $month=date('m');
        $year=date('Y');
        return  Vendoritem::find()
        ->where(['MONTH(created_datetime)' => $month])
        ->andwhere(['YEAR(created_datetime)' => $year])
        ->andwhere(['DAYOFMONTH(created_datetime)' => $date])
        ->count();
    }  
}
