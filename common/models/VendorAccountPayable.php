<?php

namespace common\models;

use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "whitebook_vendor_account_payable".
 *
 * @property integer $payable_id
 * @property string $vendor_id
 * @property string $amount
 * @property string $description
 * @property string $created_datetime
 * @property string $modified_datetime
 *
 * @property Vendor $vendor
 */
class VendorAccountPayable extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'whitebook_vendor_account_payable';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vendor_id'], 'integer'],
            [['amount'], 'required'],
            [['amount'], 'number'],
            [['description'], 'string'],
            [['created_datetime', 'modified_datetime'], 'safe'],
            [['vendor_id'], 'exist', 'skipOnError' => true, 'targetClass' => Vendor::className(), 'targetAttribute' => ['vendor_id' => 'vendor_id']],
        ];
    }

    /**
     * To save created, modified date time
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_datetime',
                'updatedAtAttribute' => 'modified_datetime',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'payable_id' => Yii::t('frontend', 'Payable ID'),
            'vendor_id' => Yii::t('frontend', 'Vendor ID'),
            'amount' => Yii::t('frontend', 'Amount'),
            'description' => Yii::t('frontend', 'Description'),
            'created_datetime' => Yii::t('frontend', 'Created Datetime'),
            'modified_datetime' => Yii::t('frontend', 'Modified Datetime'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVendor()
    {
        return $this->hasOne(Vendor::className(), ['vendor_id' => 'vendor_id']);
    }

    public function getvendorName()
    {
        if($this->vendor) {
            return $this->vendor->vendor_name;
        }
    }
}
