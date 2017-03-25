<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "whitebook_vendor_draft_phone_no".
 *
 * @property integer $draft_phone_no_id
 * @property integer $vendor_draft_id
 * @property string $phone_no
 * @property string $type
 *
 * @property VendorDraft $vendorDraft
 */
class VendorDraftPhoneNo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'whitebook_vendor_draft_phone_no';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vendor_draft_id'], 'integer'],
            [['type'], 'string'],
            [['phone_no'], 'string', 'max' => 15],
            [['vendor_draft_id'], 'exist', 'skipOnError' => true, 'targetClass' => VendorDraft::className(), 'targetAttribute' => ['vendor_draft_id' => 'vendor_draft_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'draft_phone_no_id' => 'Draft Phone No ID',
            'vendor_draft_id' => 'Vendor Draft ID',
            'phone_no' => 'Phone No',
            'type' => 'Type',
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
