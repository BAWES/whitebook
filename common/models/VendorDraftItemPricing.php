<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "whitebook_vendor_draft_item_pricing".
 *
 * @property integer $dp_id
 * @property string $item_id
 * @property integer $range_from
 * @property integer $range_to
 * @property integer $pricing_quantity_ordered
 * @property integer $pricing_price_per_unit
 * @property integer $created_by
 * @property integer $modified_by
 * @property string $created_datetime
 * @property string $modified_datetime
 * @property string $trash
 *
 * @property VendorItem $item
 */
class VendorDraftItemPricing extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'whitebook_vendor_draft_item_pricing';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_id', 'range_from', 'range_to', 'pricing_quantity_ordered', 'created_by', 'modified_by'], 'integer'],
            [['created_datetime', 'modified_datetime'], 'safe'],
            [['pricing_price_per_unit'], 'number'],
            [['trash'], 'string'],
            [['item_id'], 'exist', 'skipOnError' => true, 'targetClass' => VendorItem::className(), 'targetAttribute' => ['item_id' => 'item_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'dp_id' => Yii::t('frontend', 'Dp ID'),
            'item_id' => Yii::t('frontend', 'Item ID'),
            'range_from' => Yii::t('frontend', 'Range From'),
            'range_to' => Yii::t('frontend', 'Range To'),
            'pricing_quantity_ordered' => Yii::t('frontend', 'Pricing Quantity Ordered'),
            'pricing_price_per_unit' => Yii::t('frontend', 'Pricing Price Per Unit'),
            'created_by' => Yii::t('frontend', 'Created By'),
            'modified_by' => Yii::t('frontend', 'Modified By'),
            'created_datetime' => Yii::t('frontend', 'Created Datetime'),
            'modified_datetime' => Yii::t('frontend', 'Modified Datetime'),
            'trash' => Yii::t('frontend', 'Trash'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItem()
    {
        return $this->hasOne(VendorItem::className(), ['item_id' => 'item_id']);
    }
}
