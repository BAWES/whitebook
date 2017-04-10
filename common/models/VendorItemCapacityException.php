<?php

namespace common\models;
use common\models\VendorItem;
use yii\helpers\ArrayHelper;
use yii\db\ActiveRecord;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use Yii;

/**
 * This is the model class for table "{{%vendor_item_capacity_exception}}".
 *
 * @property string $exception_id
 * @property string $item_id
 * @property string $exception_date
 * @property integer $exception_capacity
 * @property integer $created_by
 * @property integer $modified_by
 * @property string $created_datetime
 * @property string $modified_datetime
 * @property string $trash
 */
class VendorItemCapacityException extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $default;
    public static function tableName()
    {
        return '{{%vendor_item_capacity_exception}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_id', 'exception_date', 'exception_capacity','default' ], 'required'],
            [['exception_capacity', 'created_by', 'modified_by'], 'integer'],
            [['created_by', 'modified_by', 'exception_date', 'created_datetime', 'modified_datetime'], 'safe'],
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
            'exception_id' => 'Exception ID',
            'item_id' => 'Item Name',
            'exception_date' => 'Exception Date',
            'exception_capacity' => 'Capacity',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'created_datetime' => 'Created Datetime',
            'modified_datetime' => 'Modified Datetime',
            'trash' => 'Trash',
        ];
    }

    public function getItemName($id)
    {
        $id=explode(',',$id);
        foreach($id as $i)
        {
            $model = VendorItem::find()->where(['item_id'=>$i])->one();
            $item[]=$model['item_name'];
        }
        return $item=implode(',',$item);
    }


    public function getVendoritem()
    {
        return $this->hasOne(VendorItem::className(), ['item_id' => 'item_id']);
    }

    /**
     * @inheritdoc
     * @return query\VendorItemCapacityExceptionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new query\VendorItemCapacityExceptionQuery(get_called_class());
    }


}
