<?php
namespace api\models;

use Yii;

/**
 * This is the model class for table "VendorReview".
 * It extends from \common\models\VendorReview but with custom functionality for this application module
 */
class VendorReview extends \common\models\VendorReview {

    /**
     * @inheritdoc
     */
    public function fields()
    {
        $fields = parent::fields();

        $fields['customer'] = function($model) {
        	return $model->customer->customer_name . ' ' . $model->customer->customer_last_name; 
        };

        return $fields;
    }
}