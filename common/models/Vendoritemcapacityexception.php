<?php

namespace common\models;
use common\models\Vendoritem;
use yii\helpers\ArrayHelper;

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
class Vendoritemcapacityexception extends \yii\db\ActiveRecord
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
        //print_r ($id);die;
        foreach($id as $i)
        {
        $model = Vendoritem::find()->where(['item_id'=>$i])->one();
        $item[]=$model['item_name'];
        }
        return $item=implode(',',$item);
    }


    public function getVendoritem()
    {
        return $this->hasOne(Vendoritem::className(), ['item_id' => 'item_id']);
    }
      
}
