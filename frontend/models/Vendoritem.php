<?php

namespace frontend\models;

use Yii;
use frontend\models\Vendor;
use common\models\VendorItemToCategory;

class Vendoritem extends \common\models\Vendoritem
{
   public static function vendoritem_search_details($name)
    {   
        return  $item= Vendoritem::find()
            ->joinWith(['category'])
            ->joinWith(['vendor'])
            ->select(['item_name','whitebook_vendor_item.category_id','whitebook_vendor_item.vendor_id','whitebook_vendor_item.item_id','whitebook_vendor_item.slug as wvislug','whitebook_category.category_name','whitebook_category.slug as wcslug','whitebook_vendor.vendor_name','whitebook_vendor.slug as wvslug'])
            ->where(['like', 'item_name',$name])
            ->orWhere(['like', 'category_name', $name])
            ->orWhere(['like', 'vendor_name', $name])
            ->andwhere(['whitebook_vendor_item.trash' =>'Default','whitebook_category.trash' =>'Default','item_for_sale' =>'Yes','item_status'=>'Active'])
            ->distinct()
            ->asArray()
            ->all();
    }

    public static function findvendoritem($slug)
    {       
        return Vendoritem::find()
            ->where(['slug' => $slug])
            ->one();
    }

    public static function get_category_itemlist($item_id)
    {
        return VendorItemToCategory::find()
            ->select('{{%category}}.category_id, {{%category}}.category_name, {{%category}}.category_name_ar') 
            ->joinWith('category')
            ->where(['IN', '{{%vendor_item_to_category}}.item_id', $item_id])
            ->asArray()
            ->all();
    }

    public static function get_vendor_itemlist($itemid)
    {
        if(!empty($itemid)){
            foreach($itemid as $i) {
                $vendorlist[]= Vendoritem::find()
                ->select(['vendor_id'])
                ->where(['item_id' => $i])
                ->one();
            }
            foreach($vendorlist as  $ven) {
                $k[]=$ven['vendor_id'];
            }
            $k1=(array_unique($k));
            foreach ($k1 as $v) {
                $vendor_result[]= Vendor::find()
                ->select(['vendor_id','vendor_name'])
                ->where(['vendor_id' => $v])
                ->one();
            }
            return $vendor_result;
        }
    }
}
