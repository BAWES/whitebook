<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
* This is the model class for table "{{%vendor_item_pricing}}".
*
* @property string $pricing_id
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
*/
class VendorItemPricing extends \yii\db\ActiveRecord
{
    /**
    * @inheritdoc
    */
    public static function tableName()
    {
        return '{{%vendor_item_pricing}}';
    }

    /**
    * @inheritdoc
    */
    public function rules()
    {
        return [
            [['range_from', 'range_to', 'pricing_price_per_unit'], 'required'],
            [['item_id', 'range_from', 'range_to', 'pricing_quantity_ordered', 'created_by', 'modified_by'], 'integer'],
            [['pricing_price_per_unit'], 'number'],
            [['created_datetime', 'modified_datetime'], 'safe'],
            [['trash'], 'string'],
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'modified_by',
            ],
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_datetime',
                'updatedAtAttribute' => 'modified_datetime',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
    * @inheritdoc
    */
    public function attributeLabels()
    {
        return [
            'pricing_id' => 'Pricing ID',
            'item_id' => 'Item ID',
            'range_from' => 'Range From',
            'range_to' => 'Range To',
            'pricing_quantity_ordered' => 'Pricing Quantity Ordered',
            'pricing_price_per_unit' => 'Pricing Price Per Unit',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'created_datetime' => 'Created Datetime',
            'modified_datetime' => 'Modified Datetime',
            'trash' => 'Trash',
        ];
    }

    /**
    * @inheritdoc
    * @return VendorItemPricingQuery the active query used by this AR class.
    */
    public static function find()
    {
        return new query\VendorItemPricingQuery(get_called_class());
    }

    public static function loadpricevalues($item_id)
    {
        $model = VendorItemPricing::find()->where(['item_id'=>$item_id])->all();
        return $model;
    }

    // this function is used in frontend and backend ...
    public static function loadviewprice($item_id,$type_id,$item_price_per_unit)
    {
        $model = VendorItemPricing::find()->where(['item_id'=>$item_id])->all();
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
        $model = VendorItemPricing::find()->where(['item_id'=>$item_id])->all();
        return (empty($model)) ? 0 : 1;
    }

}
