<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "whitebook_booking_item".
 *
 * @property integer $booking_item_id
 * @property integer $booking_id
 * @property string $item_id
 * @property string $item_name
 * @property string $item_name_ar
 * @property string $timeslot
 * @property integer $area_id
 * @property integer $address_id
 * @property string $delivery_address
 * @property string $delivery_date
 * @property string $price
 * @property string $item_base_price
 * @property integer $quantity
 * @property string $total
 * @property integer $female_service
 * @property string $special_request
 *
 * @property Booking $booking
 * @property VendorItem $item
 * @property BookingItemMenu[] $bookingItemMenus
 */
class BookingItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'whitebook_booking_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['booking_id', 'item_id', 'area_id', 'address_id', 'quantity', 'female_service'], 'integer'],
            [['delivery_address', 'special_request'], 'string'],
            [['delivery_date'], 'safe'],
            [['price', 'total','item_base_price'], 'number'],
            [['item_name', 'item_name_ar'], 'string', 'max' => 128],
            [['timeslot'], 'string', 'max' => 100],
            [['booking_id'], 'exist', 'skipOnError' => true, 'targetClass' => Booking::className(), 'targetAttribute' => ['booking_id' => 'booking_id']],
            [['item_id'], 'exist', 'skipOnError' => true, 'targetClass' => VendorItem::className(), 'targetAttribute' => ['item_id' => 'item_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'booking_item_id' => Yii::t('frontend', 'Booking Item ID'),
            'booking_id' => Yii::t('frontend', 'Booking ID'),
            'item_id' => Yii::t('frontend', 'Item ID'),
            'item_name' => Yii::t('frontend', 'Item Name'),
            'item_name_ar' => Yii::t('frontend', 'Item Name Ar'),
            'timeslot' => Yii::t('frontend', 'Timeslot'),
            'area_id' => Yii::t('frontend', 'Area ID'),
            'address_id' => Yii::t('frontend', 'Address ID'),
            'delivery_address' => Yii::t('frontend', 'Delivery Address'),
            'delivery_date' => Yii::t('frontend', 'Delivery Date'),
            'price' => Yii::t('frontend', 'Price'),
            'item_base_price' => Yii::t('frontend', 'Item Base Price'),
            'quantity' => Yii::t('frontend', 'Quantity'),
            'total' => Yii::t('frontend', 'Total'),
            'female_service' => Yii::t('frontend', 'Female Service'),
            'special_request' => Yii::t('frontend', 'Special Request'),
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
    public function getItem()
    {
        return $this->hasOne(VendorItem::className(), ['item_id' => 'item_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBookingItemMenus()
    {
        return $this->hasMany(BookingItemMenu::className(), ['booking_item_id' => 'booking_item_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBookingItemOptions()
    {
        return $this->hasMany(BookingItemMenu::className(), ['booking_item_id' => 'booking_item_id'])
            ->andWhere(['menu_type' => 'options']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBookingItemAddons()
    {
        return $this->hasMany(BookingItemMenu::className(), ['booking_item_id' => 'booking_item_id'])
            ->andWhere(['menu_type' => 'addons']);
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
