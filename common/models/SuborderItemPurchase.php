<?php

namespace common\models;

use Yii;
use yii\db\Expression;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use common\models\Location;
use common\models\VendorItem;
use common\models\DeliveryTimeSlot;
use common\models\CustomerAddress;

/**
 * This is the model class for table "whitebook_suborder_item_purchase".
 *
 * @property string $purchase_id
 * @property string $suborder_id
 * @property string $working_id
 * @property string $item_id
 * @property string $area_id
 * @property string $address_id
 * @property string $purchase_delivery_address
 * @property string $purchase_delivery_date
 * @property string $purchase_price_per_unit
 * @property string $purchase_customization_price_per_unit
 * @property integer $purchase_quantity
 * @property string $purchase_total_price
 * @property integer $created_by
 * @property integer $modified_by
 * @property string $created_datetime
 * @property string $modified_datetime
 * @property string $female_service
 * @property string $special_request
 * @property string $trash
 */
class SuborderItemPurchase extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'whitebook_suborder_item_purchase';
    }
    
    public function behaviors()
    {
        return [
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'modified_by',
            ],
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
    public function rules()
    {
        return [
            [['suborder_id', 'working_id', 'item_id', 'area_id', 'address_id', 'purchase_delivery_address', 'purchase_delivery_date', 'purchase_price_per_unit', 'purchase_quantity', 'purchase_total_price', 'created_by', 'modified_by', 'created_datetime', 'modified_datetime'], 'required'],
            [['suborder_id', 'working_id', 'item_id', 'area_id', 'address_id', 'purchase_quantity', 'created_by', 'modified_by'], 'integer'],
            [['purchase_delivery_address', 'trash'], 'string'],
            [['purchase_delivery_date', 'created_datetime', 'modified_datetime', 'female_service', 'special_request'], 'safe'],
            [['purchase_price_per_unit', 'purchase_customization_price_per_unit', 'purchase_total_price'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'purchase_id' => 'Purchase ID',
            'suborder_id' => 'Suborder ID',
            'working_id' => 'Timeslot ID',
            'item_id' => 'Item ID',
            'area_id' => 'Area ID',
            'address_id' => 'Address ID',
            'purchase_delivery_address' => 'Purchase Delivery Address',
            'purchase_delivery_date' => 'Purchase Delivery Date',
            'purchase_price_per_unit' => 'Purchase Price Per Unit',
            'purchase_customization_price_per_unit' => 'Purchase Customization Price Per Unit',
            'purchase_quantity' => 'Purchase Quantity',
            'purchase_total_price' => 'Purchase Total Price',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'created_datetime' => 'Created Datetime',
            'modified_datetime' => 'Modified Datetime',
            'trash' => 'Trash',
        ];
    }
    
    public function getVendoritem()
    {
        return $this->hasOne(VendorItem::className(), ['item_id' => 'item_id']);
    }

    public function getTimeslot()
    {
        return $this->hasOne(VendorWorkingTiming::className(), ['working_id' => 'working_id']);
    }

    public function getItem()
    {
        return $this->hasOne(VendorItem::className(), ['item_id' => 'item_id']);
    }

    public function getLocation()
    {
        return $this->hasOne(Location::className(), ['id' => 'area_id']);
    }

    public function getAddress()
    {
        return $this->hasOne(CustomerAddress::className(), ['address_id' => 'address_id']);
    }
}
