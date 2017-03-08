<?php

namespace common\models;

use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "whitebook_vendor_payment".
 *
 * @property integer $payment_id
 * @property string $vendor_id
 * @property integer $booking_id
 * @property integer $type
 * @property string $amount
 * @property string $description
 * @property string $created_datetime
 * @property string $modified_datetime
 *
 * @property Booking $booking
 * @property Vendor $vendor
 */
class VendorPayment extends \yii\db\ActiveRecord
{
    public $vendorName;

    const TYPE_ORDER = 0;
    const TYPE_TRANSFER = 1;
    
    public static function typeList()
    {
        return [
            self::TYPE_TRANSFER => 'Transfer',
            self::TYPE_ORDER => 'Order'
        ];
    }

    public function typeName()
    {
        $a = self::typeList();

        if(isset($a[$this->type]))
            return $a[$this->type];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'whitebook_vendor_payment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vendor_id', 'booking_id', 'type'], 'integer'],
            [['amount'], 'required'],
            [['amount'], 'number'],
            [['description'], 'string'],
            [['vendorName', 'created_datetime', 'modified_datetime'], 'safe'],
            [['booking_id'], 'exist', 'skipOnError' => true, 'targetClass' => Booking::className(), 'targetAttribute' => ['booking_id' => 'booking_id']],
            [['vendor_id'], 'exist', 'skipOnError' => true, 'targetClass' => Vendor::className(), 'targetAttribute' => ['vendor_id' => 'vendor_id']],
        ];
    }

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
            'payment_id' => Yii::t('frontend', 'Payment ID'),
            'vendor_id' => Yii::t('frontend', 'Vendor ID'),
            'booking_id' => Yii::t('frontend', 'Booking ID'),
            'type' => Yii::t('frontend', 'Type'),
            'amount' => Yii::t('frontend', 'Amount'),
            'description' => Yii::t('frontend', 'Description'),
            'created_datetime' => Yii::t('frontend', 'Created Datetime'),
            'modified_datetime' => Yii::t('frontend', 'Modified Datetime'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBooking()
    {
        return $this->hasOne(Booking::className(), ['booking_id' => 'booking_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVendor()
    {
        return $this->hasOne(Vendor::className(), ['vendor_id' => 'vendor_id']);
    }
}
