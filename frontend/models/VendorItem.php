<?php

namespace frontend\models;

use Yii;
use frontend\models\Vendor;
use common\models\VendorItemToCategory;

class VendorItem extends \common\models\VendorItem
{
   public static function vendoritem_search_details($name)
    {   
        return  $item= VendorItem::find()
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
        return VendorItem::find()
            ->where(['slug' => $slug])
            ->one();
    }

    public static function more_from_vendor($model) {
        
        return VendorItem::find()
            ->where([
                'vendor_id' => $model->vendor_id,
                'item_status' => 'Active',
                'item_approved' => 'Yes',
                'trash' => 'Default'
            ])
            ->andWhere(['!=','item_id', $model->item_id])
            ->all();    
    }
    
    public static function get_category_from_itemlist($item_id)
    {
       
    }

    public static function get_vendor_itemlist($itemid)
    {
    }
}
