<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "whitebook_vendor_phone_no".
 *
 * @property integer $phone_no_id
 * @property string $vendor_id
 * @property string $phone_no
 * @property string $type
 *
 * @property Vendor $vendor
 */
class VendorPhoneNo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'whitebook_vendor_phone_no';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vendor_id'], 'integer'],
            [['type'], 'string'],
            [['phone_no'], 'string', 'max' => 15],
            [['vendor_id'], 'exist', 'skipOnError' => true, 'targetClass' => Vendor::className(), 'targetAttribute' => ['vendor_id' => 'vendor_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'phone_no_id' => 'Phone No ID',
            'vendor_id' => 'Vendor ID',
            'phone_no' => 'Phone No',
            'type' => 'Type',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVendor()
    {
        return $this->hasOne(Vendor::className(), ['vendor_id' => 'vendor_id']);
    }
}
