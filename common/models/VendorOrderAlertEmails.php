<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "whitebook_vendor_order_alert_emails".
 *
 * @property integer $voae_id
 * @property integer $vendor_id
 * @property string $email_address
 */
class VendorOrderAlertEmails extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'whitebook_vendor_order_alert_emails';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vendor_id'], 'integer'],
            [['email_address'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'voae_id' => 'Voae ID',
            'vendor_id' => 'Vendor ID',
            'email_address' => 'Email Address',
        ];
    }
}
