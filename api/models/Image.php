<?php
namespace api\models;

use Yii;

/**
 * This is the model class for table "Image".
 * It extends from \common\models\Image but with custom functionality for this application module
 */
class Image extends \common\models\Image {

    /**
     * @inheritdoc
     */
    public function fields()
    {
        $fields = parent::fields();
      
        // remove fields that contain sensitive information
        unset(
            $fields['image_id'],
            $fields['item_id'],
            $fields['image_user_id'],
            $fields['image_user_type'],
            $fields['image_file_size'],
            $fields['image_width'],      
            $fields['image_height'],
            $fields['image_datetime'],
            $fields['image_ip_address'],
            $fields['vendorimage_sort_order'],
            $fields['created_by'],
            $fields['modified_by'],
            $fields['created_datetime'],
            $fields['modified_datetime'],
            $fields['image_status'],
            $fields['trash']);

        return $fields;
    }
}