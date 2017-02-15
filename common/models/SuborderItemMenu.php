<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "whitebook_suborder_item_menu".
 *
 * @property integer $suborder_item_menu_id
 * @property string $purchase_id
 * @property integer $menu_id
 * @property integer $menu_item_id
 * @property string $menu_name
 * @property string $menu_name_ar
 * @property string $menu_item_name
 * @property string $menu_item_name_ar
 * @property integer $quantity
 * @property string $price
 * @property string $total
 *
 * @property VendorItemMenu $menu
 * @property VendorItemMenuItem $menuItem
 * @property SuborderItemPurchase $purchase
 */
class SuborderItemMenu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'whitebook_suborder_item_menu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['purchase_id', 'menu_id', 'menu_item_id', 'quantity'], 'integer'],
            [['price', 'total'], 'number'],
            [['menu_name', 'menu_name_ar', 'menu_item_name', 'menu_item_name_ar', 'menu_type'], 'string', 'max' => 100],
            [['menu_id'], 'exist', 'skipOnError' => true, 'targetClass' => VendorItemMenu::className(), 'targetAttribute' => ['menu_id' => 'menu_id']],
            [['menu_item_id'], 'exist', 'skipOnError' => true, 'targetClass' => VendorItemMenuItem::className(), 'targetAttribute' => ['menu_item_id' => 'menu_item_id']],
            [['purchase_id'], 'exist', 'skipOnError' => true, 'targetClass' => SuborderItemPurchase::className(), 'targetAttribute' => ['purchase_id' => 'purchase_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'suborder_item_menu_id' => 'Suborder Item Menu ID',
            'purchase_id' => 'Purchase ID',
            'menu_id' => 'Menu ID',
            'menu_item_id' => 'Menu Item ID',
            'menu_name' => 'Menu Name',
            'menu_name_ar' => 'Menu Name Ar',
            'menu_item_name' => 'Menu Item Name',
            'menu_item_name_ar' => 'Menu Item Name Ar',
            'quantity' => 'Quantity',
            'price' => 'Price',
            'total' => 'Total',
        ];
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPurchase()
    {
        return $this->hasOne(SuborderItemPurchase::className(), ['purchase_id' => 'purchase_id']);
    }
}
