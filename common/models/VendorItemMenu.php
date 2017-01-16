<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "whitebook_vendor_item_menu".
 *
 * @property integer $menu_id
 * @property string $item_id
 * @property string $menu_name
 * @property string $menu_name_ar
 *
 * @property CustomerCartMenuItem[] $customerCartMenuItems
 * @property SuborderItemMenu[] $suborderItemMenus
 * @property VendorItem $item
 * @property VendorItemMenuItem[] $vendorItemMenuItems
 */
class VendorItemMenu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'whitebook_vendor_item_menu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_id', 'min_quantity', 'max_quantity', 'sort_order'], 'integer'],
            [['menu_name', 'menu_name_ar'], 'string', 'max' => 100],
            [['item_id'], 'exist', 'skipOnError' => true, 'targetClass' => VendorItem::className(), 'targetAttribute' => ['item_id' => 'item_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'menu_id' => 'Menu ID',
            'item_id' => 'Item ID',
            'menu_name' => 'Menu Name',
            'menu_name_ar' => 'Menu Name Ar',
            'min_quantity' => 'Min Quantity',
            'max_quantity' => 'Max Quantity',
            'sort_order' => 'Sort Order'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomerCartMenuItems()
    {
        return $this->hasMany(CustomerCartMenuItem::className(), ['menu_id' => 'menu_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSuborderItemMenus()
    {
        return $this->hasMany(SuborderItemMenu::className(), ['menu_id' => 'menu_id']);
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
    public function getVendorItemMenuItems()
    {
        return $this->hasMany(VendorItemMenuItem::className(), ['menu_id' => 'menu_id']);
    }
}
