<?php

set_time_limit(0);

use yii\db\Migration;
use common\models\Image;
use common\models\VendorItemPricing;
use common\models\VendorItemToCategory;
use common\models\VendorDraftItem;
use common\models\VendorDraftImage;
use common\models\VendorDraftItemPricing;
use common\models\VendorDraftItemToCategory;

class m170105_095654_populate_draft_tables extends Migration
{
    public function up()
    {
        $items = VendorDraftItem::find()->all();

        foreach ($items as $key => $value) 
        {
            $images = Image::findAll(['item_id' => $value->item_id]);

            foreach ($images as $key => $image) 
            {
                $di = new VendorDraftImage;
                $di->item_id = $image->item_id;
                $di->image_user_id = $image->image_user_id;
                $di->image_path = $image->image_path;
                $di->vendorimage_sort_order = $image->vendorimage_sort_order;
                $di->save();
            }
            
            $pricing = VendorItemPricing::findAll(['item_id' => $value->item_id]);

            foreach ($pricing as $key => $price) 
            {
                $dip = new VendorDraftItemPricing;
                $dip->attributes = $price->attributes;
                $dip->save();
            }

            $categories = VendorItemToCategory::findAll(['item_id' => $value->item_id]);
            
            foreach ($categories as $key => $category) 
            {
                $dic = new VendorDraftItemToCategory;
                $dic->attributes = $category->attributes;
                $dic->save();
            }
        }
    }
}
