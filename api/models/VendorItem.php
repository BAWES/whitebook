<?php
namespace api\models;

use Yii;
use api\models\Vendor;
use api\models\Image;
use api\models\ItemType;

/**
 * This is the model class for table "VendorItem".
 * It extends from \common\models\VendorItem but with custom functionality for this application module
 */
class VendorItem extends \common\models\VendorItem {

    /**
     * @inheritdoc
     */
    public function fields()
    {
        $fields = parent::fields();

        // remove fields that contain sensitive information
        unset(
            $fields['sort'],
            $fields['item_archived'],
            $fields['item_approved'],
            $fields['item_status'],
            $fields['version'],
            $fields['hide_from_admin'],
            $fields['created_by'],      
            $fields['modified_by'],
            $fields['trash'],
            $fields['priority'],
            $fields['type_id'],
            $fields['vendor_id']);

        return $fields;
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getVendor()
    {
        return $this->hasOne(Vendor::className(), ['vendor_id' => 'vendor_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getImage()
    {
        return $this->hasOne(Image::className(), ['item_id' => 'item_id'])->where(['module_type'=>'vendor_item'])->orderBy(['vendorimage_sort_order'=>SORT_ASC]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImages()
    {
        return $this->hasMany(Image::className(), ['item_id' => 'item_id'])->orderBy(['vendorimage_sort_order'=>SORT_ASC]);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getType()
    {
        return $this->hasOne(ItemType::className(), ['type_id' => 'type_id']);
    }
}