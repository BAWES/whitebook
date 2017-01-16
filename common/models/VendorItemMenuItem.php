<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "whitebook_vendor_item_menu_item".
 *
 * @property integer $menu_item_id
 * @property string $item_id
 * @property integer $menu_id
 * @property string $menu_item_name
 * @property string $menu_item_name_ar
 * @property integer $min_quantity
 * @property integer $max_quantity
 * @property string $price
 * @property string $hint
 *
 * @property CustomerCartMenuItem[] $customerCartMenuItems
 * @property SuborderItemMenu[] $suborderItemMenus
 * @property VendorItem $item
 * @property VendorItemMenu $menu
 */
class VendorItemMenuItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'whitebook_vendor_item_menu_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_id', 'menu_id', 'min_quantity', 'max_quantity', 'sort_order'], 'integer'],
            [['price'], 'number'],
            [['menu_item_name', 'menu_item_name_ar'], 'string', 'max' => 100],
            [['hint'], 'string', 'max' => 250],
            [['item_id'], 'exist', 'skipOnError' => true, 'targetClass' => VendorItem::className(), 'targetAttribute' => ['item_id' => 'item_id']],
            [['menu_id'], 'exist', 'skipOnError' => true, 'targetClass' => VendorItemMenu::className(), 'targetAttribute' => ['menu_id' => 'menu_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'menu_item_id' => 'Menu Item ID',
            'item_id' => 'Item ID',
            'menu_id' => 'Menu ID',
            'menu_item_name' => 'Menu Item Name',
            'menu_item_name_ar' => 'Menu Item Name Ar',
            'min_quantity' => 'Min Quantity',
            'max_quantity' => 'Max Quantity',
            'price' => 'Price',
            'hint' => 'Hint',
            'sort_order' => 'Sort Order'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomerCartMenuItems()
    {
        return $this->hasMany(CustomerCartMenuItem::className(), ['menu_item_id' => 'menu_item_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSuborderItemMenus()
    {
        return $this->hasMany(SuborderItemMenu::className(), ['menu_item_id' => 'menu_item_id']);
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
    public function getMenu()
    {
        return $this->hasOne(VendorItemMenu::className(), ['menu_id' => 'menu_id']);
    }
}
