<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "whitebook_booking_item_menu".
 *
 * @property integer $booking_item_menu_id
 * @property integer $booking_item_id
 * @property integer $menu_id
 * @property integer $menu_item_id
 * @property string $menu_name
 * @property string $menu_name_ar
 * @property string $menu_type
 * @property string $menu_item_name
 * @property string $menu_item_name_ar
 * @property integer $quantity
 * @property string $price
 * @property string $total
 *
 * @property BookingItem $bookingItem
 * @property VendorItemMenu $menu
 * @property VendorItemMenuItem $menuItem
 */
class BookingItemMenu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'whitebook_booking_item_menu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['booking_item_id', 'menu_id', 'menu_item_id', 'quantity'], 'integer'],
            [['menu_type'], 'string'],
            [['price', 'total'], 'number'],
            [['menu_name', 'menu_name_ar', 'menu_item_name', 'menu_item_name_ar'], 'string', 'max' => 100],
            [['booking_item_id'], 'exist', 'skipOnError' => true, 'targetClass' => BookingItem::className(), 'targetAttribute' => ['booking_item_id' => 'booking_item_id']],
            [['menu_id'], 'exist', 'skipOnError' => true, 'targetClass' => VendorItemMenu::className(), 'targetAttribute' => ['menu_id' => 'menu_id']],
            [['menu_item_id'], 'exist', 'skipOnError' => true, 'targetClass' => VendorItemMenuItem::className(), 'targetAttribute' => ['menu_item_id' => 'menu_item_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'booking_item_menu_id' => Yii::t('frontend', 'Booking Item Menu ID'),
            'booking_item_id' => Yii::t('frontend', 'Booking Item ID'),
            'menu_id' => Yii::t('frontend', 'Menu ID'),
            'menu_item_id' => Yii::t('frontend', 'Menu Item ID'),
            'menu_name' => Yii::t('frontend', 'Menu Name'),
            'menu_name_ar' => Yii::t('frontend', 'Menu Name Ar'),
            'menu_type' => Yii::t('frontend', 'Menu Type'),
            'menu_item_name' => Yii::t('frontend', 'Menu Item Name'),
            'menu_item_name_ar' => Yii::t('frontend', 'Menu Item Name Ar'),
            'quantity' => Yii::t('frontend', 'Quantity'),
            'price' => Yii::t('frontend', 'Price'),
            'total' => Yii::t('frontend', 'Total'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBookingItem()
    {
        return $this->hasOne(BookingItem::className(), ['booking_item_id' => 'booking_item_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenu()
    {
        return $this->hasOne(VendorItemMenu::className(), ['menu_id' => 'menu_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuItem()
    {
        return $this->hasOne(VendorItemMenuItem::className(), ['menu_item_id' => 'menu_item_id']);
    }
}
