<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "whitebook_vendor_draft_item_menu_item".
 *
 * @property integer $draft_menu_item_id
 * @property integer $draft_menu_id
 * @property string $item_id
 * @property string $menu_item_name
 * @property string $menu_item_name_ar
 * @property string $price
 * @property string $hint
 * @property string $hint_ar
 * @property integer $sort_order
 *
 * @property VendorDraftItem $item
 * @property VendorDraftItemMenu $draftMenu
 */
class VendorDraftItemMenuItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'whitebook_vendor_draft_item_menu_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['draft_menu_id', 'menu_item_id', 'item_id', 'sort_order'], 'integer'],
            [['price'], 'number'],
            [['menu_item_name', 'menu_item_name_ar'], 'required'],
            [['menu_item_name', 'menu_item_name_ar'], 'string', 'max' => 100],
            [['hint', 'hint_ar'], 'string', 'max' => 250],
            [['item_id'], 'exist', 'skipOnError' => true, 'targetClass' => VendorDraftItem::className(), 'targetAttribute' => ['item_id' => 'item_id']],
            [['draft_menu_id'], 'exist', 'skipOnError' => true, 'targetClass' => VendorDraftItemMenu::className(), 'targetAttribute' => ['draft_menu_id' => 'draft_menu_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'draft_menu_item_id' => Yii::t('frontend', 'Draft Menu Item ID'),
            'draft_menu_id' => Yii::t('frontend', 'Draft Menu ID'),
            'item_id' => Yii::t('frontend', 'Item ID'),
            'menu_item_name' => Yii::t('frontend', 'Menu Item Name'),
            'menu_item_name_ar' => Yii::t('frontend', 'Menu Item Name Ar'),
            'price' => Yii::t('frontend', 'Price'),
            'hint' => Yii::t('frontend', 'Hint'),
            'hint_ar' => Yii::t('frontend', 'Hint Ar'),
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
    public function getDraftMenu()
    {
        return $this->hasOne(VendorDraftItemMenu::className(), ['draft_menu_id' => 'draft_menu_id']);
    }
}
