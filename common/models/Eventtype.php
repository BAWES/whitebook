<?php

namespace common\models;
use yii\helpers\ArrayHelper;
use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "whitebook_item_type".
 *
 * @property string $type_id
 * @property string $type_name
 * @property integer $created_by
 * @property integer $modified_by
 * @property string $created_datetime
 * @property string $modified_datetime
 * @property string $trash
 *
 * @property VendorItem[] $vendorItems
 */
class Eventtype extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'whitebook_event_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
        
			['type_name','typenamevalidation','on' => 'insert',],
            [['type_name'], 'required'],
            [['created_by', 'modified_by'], 'integer'],
            [['created_by', 'modified_by', 'created_datetime', 'modified_datetime'], 'safe'],
            [['trash'], 'string'],
            [['type_name'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'type_id' => 'Type ID',
            'type_name' => 'Event Type name',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'created_datetime' => 'Created Datetime',
            'modified_datetime' => 'Modified Datetime',
            'trash' => 'Trash',
        ];
    }
    
    	public  function typenamevalidation($attribute_name)
	{
		if(!empty($this->type_name) )
        {
		$modelq = Eventtype::find()
		->where(['type_name'=>$this->type_name])
		->one();
        if($modelq){
        $this->addError('type_name','Please enter a unique event name');
	}
		}
	}
}

