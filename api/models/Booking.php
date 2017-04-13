<?php
namespace api\models;

use Yii;

/**
 * This is the model class for table "Booking".
 * It extends from \common\models\Booking but with custom functionality for this application module
 */
class Booking extends \common\models\Booking {

    /**
     * @inheritdoc
     */
    public function fields()
    {
        $fields = parent::fields();

        // remove fields that contain sensitive information
        unset($fields['gateway_percentage'],
        $fields['gateway_fees'],
        $fields['gateway_total'],
        $fields['commission_percentage'],
        $fields['commission_total'],
        $fields['total_vendor']);

        return $fields;
    }
}