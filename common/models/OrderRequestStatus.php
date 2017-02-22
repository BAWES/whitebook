<?php

namespace common\models;

use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%order_request_status}}".
 *
 * @property integer $request_id
 * @property integer $order_id
 * @property integer $vendor_id
 * @property string $request_status
 * @property string $request_note
 * @property string $created_datetime
 * @property string $modified_datetime
 */
class OrderRequestStatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_request_status}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id','vendor_id'], 'required'],
            [['order_id','vendor_id'], 'integer'],
            [['request_status', 'request_note'], 'string'],
            [['created_datetime', 'modified_datetime'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'request_id' => Yii::t('app', 'Request ID'),
            'order_id' => Yii::t('app', 'Order ID'),
            'vendor_id' => Yii::t('app', 'Vendor ID'),
            'request_status' => Yii::t('app', 'Request Status'),
            'request_note' => Yii::t('app', 'Request Note'),
            'created_datetime' => Yii::t('app', 'Created On'),
            'modified_datetime' => Yii::t('app', 'Modified On'),
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

    /*
     * Get order detail
     */
    public function getOrderDetail()
    {
        return $this->hasOne(Order::className(),['order_id'=>'order_id']);
    }

    /*
     * get vendor detail
     */
    public function getVendorDetail()
    {
        return $this->hasOne(Vendor::className(),['vendor_id'=>'vendor_id']);
    }
}