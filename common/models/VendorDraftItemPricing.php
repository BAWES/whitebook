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


    public static function loadpricevalues($item_id)
    {
        $model = VendorDraftItemPricing::find()->where(['item_id'=>$item_id])->all();
        return $model;
    }

    // this function is used in frontend and backend ...
    public static function loadviewprice($item_id,$type_id,$item_price_per_unit)
    {
        $model = VendorDraftItemPricing::find()->where(['item_id'=>$item_id])->all();
        if(empty($model))
        {echo 'No price chart data!';}
        else
        {
            echo '<table class="table table-striped table-bordered detail-view price_range"><tbody>';
            echo '<tr style="font-size: 16px;"><th colspan=3>Item price per unit range</th></tr>';
            echo '<tr><th>Range from<th>Range To<th>Price (KD) </th></tr>';
            foreach ($model as $key => $value) {
                echo '<tr><td>'.$value['range_from'].'<td>'.$value['range_to'].'<td>'.$value['pricing_price_per_unit'].'</td></tr>';
            }
            echo "</tbody></table>";
        }

    }

    // This function is used in frontend
    public static function checkprice($item_id,$type_id,$item_price_per_unit)
    {
        $model = VendorDraftItemPricing::find()->where(['item_id'=>$item_id])->all();
        return (empty($model)) ? 0 : 1;
    }


    /**
     * @inheritdoc
     * @return query\VendorDraftItemPricingQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new query\VendorDraftItemPricingQuery(get_called_class());
    }
}
