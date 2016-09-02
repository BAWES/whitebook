<?php

namespace common\models;

use Yii;
use common\models\Vendoritem;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "whitebook_suborder_item_purchase".
 *
 * @property string $purchase_id
 * @property string $suborder_id
 * @property string $timeslot_id
 * @property string $item_id
 * @property string $area_id
 * @property string $address_id
 * @property string $purchase_delivery_address
 * @property string $purchase_delivery_date
 * @property string $purchase_price_per_unit
 * @property string $purchase_customization_price_per_unit
 * @property integer $purchase_quantity
 * @property string $purchase_total_price
 * @property string $purchase_datetime
 * @property integer $created_by
 * @property integer $modified_by
 * @property string $created_datetime
 * @property string $modified_datetime
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
            [['suborder_id', 'timeslot_id', 'item_id', 'area_id', 'address_id', 'purchase_delivery_address', 'purchase_delivery_date', 'purchase_price_per_unit', 'purchase_quantity', 'purchase_total_price', 'purchase_datetime', 'created_by', 'modified_by', 'created_datetime', 'modified_datetime'], 'required'],
            [['suborder_id', 'timeslot_id', 'item_id', 'area_id', 'address_id', 'purchase_quantity', 'created_by', 'modified_by'], 'integer'],
            [['purchase_delivery_address', 'trash'], 'string'],
            [['purchase_delivery_date', 'purchase_datetime', 'created_datetime', 'modified_datetime'], 'safe'],
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
            'timeslot_id' => 'Timeslot ID',
            'item_id' => 'Item ID',
            'area_id' => 'Area ID',
            'address_id' => 'Address ID',
            'purchase_delivery_address' => 'Purchase Delivery Address',
            'purchase_delivery_date' => 'Purchase Delivery Date',
            'purchase_price_per_unit' => 'Purchase Price Per Unit',
            'purchase_customization_price_per_unit' => 'Purchase Customization Price Per Unit',
            'purchase_quantity' => 'Purchase Quantity',
            'purchase_total_price' => 'Purchase Total Price',
            'purchase_datetime' => 'Purchase Datetime',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'created_datetime' => 'Created Datetime',
            'modified_datetime' => 'Modified Datetime',
            'trash' => 'Trash',
        ];
    }
    
    public function getVendoritem()
    {
        return $this->hasOne(Vendoritem::className(), ['item_id' => 'item_id']);
    } 
}
