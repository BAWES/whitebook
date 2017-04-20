<?php
namespace api\models;

use Yii;

/**
 * This is the model class for table "ItemType".
 * It extends from \common\models\ItemType but with custom functionality for this application module
 */
class ItemType extends \common\models\ItemType {

    /**
     * @inheritdoc
     */
    public function fields()
    {
        $fields = parent::fields();
      
        // remove fields that contain sensitive information
        unset(
            $fields['created_by'],
            $fields['modified_by'],
            $fields['created_datetime'],
            $fields['modified_datetime'],
            $fields['image_status'],
            $fields['trash']);

        return $fields;
    }
}