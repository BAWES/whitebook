<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "whitebook_customer_cart_menu_item".
 *
 * @property integer $cart_menu_item_id
 * @property string $cart_id
 * @property integer $menu_id
 * @property integer $menu_item_id
 * @property integer $quantity
 *
 * @property CustomerCart $cart
 * @property VendorItemMenu $menu
 * @property VendorItemMenuItem $menuItem
 */
class CustomerCartMenuItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'whitebook_customer_cart_menu_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cart_id', 'menu_id', 'menu_item_id', 'quantity'], 'integer'],
            [['cart_id'], 'exist', 'skipOnError' => true, 'targetClass' => CustomerCart::className(), 'targetAttribute' => ['cart_id' => 'cart_id']],
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
            'cart_menu_item_id' => 'Cart Menu Item ID',
            'cart_id' => 'Cart ID',
            'menu_id' => 'Menu ID',
            'menu_item_id' => 'Menu Item ID',
            'quantity' => 'Quantity',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCart()
    {
        return $this->hasOne(CustomerCart::className(), ['cart_id' => 'cart_id']);
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
