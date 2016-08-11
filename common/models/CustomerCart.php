<?php

namespace common\models;

use Yii;
use yii\db\Expression;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use common\models\Vendorlocation;
use common\models\Vendoritem;

/**
 * This is the model class for table "whitebook_customer_cart".
 *
 * @property string $cart_id
 * @property string $customer_id
 * @property string $item_id
 * @property string $area_id
 * @property string $timeslot_id
 * @property string $cart_delivery_date
 * @property string $cart_customization_price_per_unit
 * @property integer $cart_quantity
 * @property string $cart_datetime_added
 * @property string $cart_valid
 * @property integer $created_by
 * @property integer $modified_by
 * @property string $created_datetime
 * @property string $modified_datetime
 * @property string $trash
 */
class CustomerCart extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'whitebook_customer_cart';
    }

    public function behaviors()
    {
        return [
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'modified_by'
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
            [['customer_id', 'item_id', 'area_id', 'timeslot_id', 'cart_delivery_date', 'cart_customization_price_per_unit', 'cart_quantity', 'cart_datetime_added'], 'required'],
            [['customer_id', 'item_id', 'area_id', 'timeslot_id', 'cart_quantity', 'created_by', 'modified_by'], 'integer'],
            [['cart_delivery_date', 'cart_datetime_added', 'created_datetime', 'modified_datetime'], 'safe'],
            [['cart_customization_price_per_unit'], 'number'],
            ['cart_quantity', 'compare', 'compareValue' => 0, 'operator' => '>'],
            [['cart_valid', 'trash'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cart_id' => 'Cart ID',
            'customer_id' => Yii::t('frontend', 'Customer'),
            'item_id' => Yii::t('frontend', 'Item'),
            'area_id' => Yii::t('frontend', 'Area'),
            'timeslot_id' => Yii::t('frontend', 'Delivery timeslot'),
            'cart_delivery_date' => Yii::t('frontend', 'Cart Delivery Date'),
            'cart_customization_price_per_unit' => Yii::t('frontend', 'Cart Customization Price Per Unit'),
            'cart_quantity' => Yii::t('frontend', 'Quantity'),
            'cart_datetime_added' => Yii::t('frontend', 'Cart Datetime Added'),
            'cart_valid' => Yii::t('frontend', 'Cart Valid'),
            'created_by' => Yii::t('frontend', 'Created By'),
            'modified_by' => Yii::t('frontend', 'Modified By'),
            'created_datetime' => Yii::t('frontend', 'Created Datetime'),
            'modified_datetime' => Yii::t('frontend', 'Modified Datetime'),
            'trash' => Yii::t('frontend', 'Trash')
        ];
    }

    public function getItem()
    {
        return $this->hasOne(Vendoritem::className(), ['item_id' => 'item_id']);
    }

    public function getTimeslot()
    {
        return $this->hasOne(Deliverytimeslot::className(), ['timeslot_id' => 'timeslot_id']);
    }

    //return customer items 
    public static function items() {

        $items = CustomerCart::find()
            ->select('
                {{%customer_cart}}.*, 
                {{%vendor_item}}.item_price_per_unit,
                {{%vendor_item}}.slug,
                {{%vendor_item}}.vendor_id,
                {{%vendor_item}}.item_name,
                {{%vendor_item}}.item_name_ar,
                {{%vendor_delivery_timeslot}}.timeslot_start_time, 
                {{%vendor_delivery_timeslot}}.timeslot_end_time'
            )
            ->joinWith('item')
            ->joinWith('timeslot')
            ->where([
                '{{%customer_cart}}.customer_id' => Yii::$app->user->getId(),
                '{{%customer_cart}}.cart_valid' => 'yes',
                '{{%customer_cart}}.trash' => 'Default',
                '{{%vendor_item}}.trash' => 'Default',
                '{{%vendor_item}}.item_for_sale' => 'Yes',
                '{{%vendor_item}}.item_status' => 'Active',
                '{{%vendor_item}}.item_approved' => 'Yes',
            ])
            ->asArray()
            ->all();

        return $items;    
    }

    public static function item_count() {

        $items = CustomerCart::find()
            ->joinWith('item')
            ->joinWith('timeslot')
            ->where([
                '{{%customer_cart}}.customer_id' => Yii::$app->user->getId(),
                '{{%customer_cart}}.cart_valid' => 'yes',
                '{{%customer_cart}}.trash' => 'Default',
                '{{%vendor_item}}.trash' => 'Default',
                '{{%vendor_item}}.item_for_sale' => 'Yes',
                '{{%vendor_item}}.item_status' => 'Active',
                '{{%vendor_item}}.item_approved' => 'Yes',
            ])
            ->count();

        return $items;   
    }

    /*
        Get location info 
    */
    public static function geLocation($area_id, $vendor_id){
        
        $result = Vendorlocation::find()
            ->joinWith('location')
            ->where([
                '{{%location}}.status' => 'Active',
                '{{%location}}.trash' => 'Default',
                '{{%vendor_location}}.vendor_id' => $vendor_id, 
                '{{%vendor_location}}.area_id' => $area_id])
            ->one();

        return $result;
    }

    /*
        Check if delivery availble on selected area 
    */
    public static function checkLocation($area_id, $vendor_id){
        
        $result = Vendorlocation::find()
            ->joinWith('location')
            ->where([
                '{{%location}}.status' => 'Active',
                '{{%location}}.trash' => 'Default',
                '{{%vendor_location}}.vendor_id' => $vendor_id, 
                '{{%vendor_location}}.area_id' => $area_id])
            ->count();

        return $result;
    }
}
