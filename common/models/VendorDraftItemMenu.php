<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "whitebook_vendor_draft_item_menu".
 *
 * @property integer $draft_menu_id
 * @property string $item_id
 * @property string $menu_name
 * @property string $menu_name_ar
 * @property string $menu_type
 * @property integer $min_quantity
 * @property integer $max_quantity
 * @property string $quantity_type
 * @property integer $sort_order
 *
 * @property VendorDraftItem $item
 * @property VendorDraftItemMenuItem[] $vendorDraftItemMenuItems
 */
class VendorDraftItemMenu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'whitebook_vendor_draft_item_menu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['menu_id', 'item_id', 'min_quantity', 'max_quantity', 'sort_order'], 'integer'],
            [['menu_type'], 'string'],            
            ['max_quantity', 'compare', 'compareAttribute' => 'min_quantity', 'operator' => '>='],
            [['menu_name', 'menu_name_ar', 'quantity_type'], 'required'],
            [['menu_name', 'menu_name_ar', 'quantity_type'], 'string', 'max' => 100],
            [['item_id'], 'exist', 'skipOnError' => true, 'targetClass' => VendorDraftItem::className(), 'targetAttribute' => ['item_id' => 'item_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'draft_menu_id' => Yii::t('frontend', 'Draft Menu ID'),
            'item_id' => Yii::t('frontend', 'Item ID'),
            'menu_name' => Yii::t('frontend', 'Menu Name'),
            'menu_name_ar' => Yii::t('frontend', 'Menu Name Ar'),
            'menu_type' => Yii::t('frontend', 'Menu Type'),
            'min_quantity' => Yii::t('frontend', 'Min Quantity'),
            'max_quantity' => Yii::t('frontend', 'Max Quantity'),
            'quantity_type' => Yii::t('frontend', 'Quantity Type'),
            'sort_order' => Yii::t('frontend', 'Sort Order'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItem()
    {
        return $this->hasOne(VendorDraftItem::className(), ['item_id' => 'item_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVendorDraftItemMenuItems()
    {
        return $this->hasMany(VendorDraftItemMenuItem::className(), ['draft_menu_id' => 'draft_menu_id']);
    }
}
