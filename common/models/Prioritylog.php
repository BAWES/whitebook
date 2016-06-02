<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "{{%priority_log}}".
 *
 * @property integer $log_id
 * @property string $vendor_id
 * @property string $item_id
 * @property string $priority_level
 * @property string $priority_start_date
 * @property string $priority_end_date
 * @property integer $created_by
 * @property integer $modified_by
 * @property string $created_datetime
 * @property string $modified_datetime
 * @property string $trash
 *
 * @property VendorItem $item
 * @property Vendor $vendor
 */
class Prioritylog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%priority_log}}';
    }


    public function behaviors()
    {
          return [
              [
                      'class' => BlameableBehavior::className(),
                      'createdByAttribute' => 'created_by',
                      'updatedByAttribute' => 'modified_by',
                  ],
                  'timestamp' => 
                  [
                      'class' => 'yii\behaviors\TimestampBehavior',
                      'attributes' => [
                       ActiveRecord::EVENT_BEFORE_INSERT => ['created_datetime'],
                       ActiveRecord::EVENT_BEFORE_UPDATE => ['modified_datetime'],
                         
                      ],
                     'value' => new Expression('NOW()'),
                  ],
          ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vendor_id', 'item_id', 'priority_level', 'priority_start_date', 'priority_end_date'], 'required'],
            [['vendor_id', 'item_id', 'created_by', 'modified_by'], 'integer'],
            [['priority_level', 'trash'], 'string'],
            [['priority_start_date', 'priority_end_date', 'created_datetime', 'modified_datetime'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'log_id' => 'Log ID',
            'vendor_id' => 'Vendor ID',
            'item_id' => 'Item ID',
            'priority_level' => 'Priority Level',
            'priority_start_date' => 'Priority Start Date',
            'priority_end_date' => 'Priority End Date',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'created_datetime' => 'Created Datetime',
            'modified_datetime' => 'Modified Datetime',
            'trash' => 'Trash',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItem()
    {
        return $this->hasOne(Vendoritem::className(), ['item_id' => 'item_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVendor()
    {
        return $this->hasOne(Vendor::className(), ['vendor_id' => 'vendor_id']);
    }

    public function getEndDate($id)
    {
			$enddate= Prioritylog::find()
			->select ('priority_end_date')
			->where(['=', 'log_id', $id])
			->one(); 
			if($enddate['priority_end_date']=='0000-00-00 00:00:00')
			{
				return null;
				}
				else {
					
					return date("d/m/Y", strtotime($enddate['priority_end_date']));
			}
			
    }

}
