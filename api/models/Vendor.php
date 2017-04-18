<?php
namespace api\models;

use Yii;

/**
 * This is the model class for table "Vendor".
 * It extends from \common\models\Vendor but with custom functionality for this application module
 */
class Vendor extends \common\models\Vendor {

    /**
     * @inheritdoc
     */
    public function fields()
    {
        $fields = parent::fields();

        // remove fields that contain sensitive information
        unset(
            $fields['commision'],
            $fields['vendor_password'],
            $fields['vendor_status'],
            $fields['approve_status'],
            $fields['expire_notification'],
            $fields['created_by'],      
            $fields['modified_by'],
            $fields['trash'],
            $fields['vendor_bank_name'],
            $fields['vendor_bank_branch'],
            $fields['vendor_account_no'],
            $fields['vendor_payable'],
            $fields['vendor_booking_managed_by'],
            $fields['auth_token']);

        return $fields;
    }
}