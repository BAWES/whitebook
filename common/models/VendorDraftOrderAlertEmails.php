<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "whitebook_vendor_draft_order_alert_emails".
 *
 * @property integer $vdoae_id
 * @property integer $vendor_draft_id
 * @property string $email_address
 *
 * @property VendorDraft $vendorDraft
 */
class VendorDraftOrderAlertEmails extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'whitebook_vendor_draft_order_alert_emails';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vendor_draft_id'], 'integer'],
            [['email_address'], 'string', 'max' => 100],
            [['vendor_draft_id'], 'exist', 'skipOnError' => true, 'targetClass' => VendorDraft::className(), 'targetAttribute' => ['vendor_draft_id' => 'vendor_draft_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'vdoae_id' => 'Vdoae ID',
            'vendor_draft_id' => 'Vendor Draft ID',
            'email_address' => 'Email Address',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVendorDraft()
    {
        return $this->hasOne(VendorDraft::className(), ['vendor_draft_id' => 'vendor_draft_id']);
    }
}
