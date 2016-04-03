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
class Itemtype extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'whitebook_item_type';
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
            'type_name' => 'Item name',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'created_datetime' => 'Created Datetime',
            'modified_datetime' => 'Modified Datetime',
            'trash' => 'Trash',
        ];
    }

	public  function typenamevalidation($attribute_name)
	{
		if(!empty($this->type_name) ){
		$modelq = Itemtype::find()
		->where(['type_name'=>$this->type_name])
		->one();
        if($modelq){
        $this->addError('type_name','Please enter a unique Type name');
	}
		}
	}
	
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVendorItems()
    {
        return $this->hasMany(VendorItem::className(), ['type_id' => 'type_id']);
    }
    
    	    public static function loaditemtype()
	{       
			$itemtype= Itemtype::find()
			->where(['!=', 'trash', 'Deleted'])
			->all();
			$itemtype=ArrayHelper::map($itemtype,'type_id','type_name');
			return $itemtype;
	}	
	
	        public static function loadvendorname()
	{       
			$vendorname= Vendor::find()
			->where(['!=', 'trash', 'Deleted'])
			->all();
			$vendorname=ArrayHelper::map($vendorname,'vendor_id','vendor_name');
			return $vendorname;
	}	
	
	//Item type name should in vendor view tab
	    	public static function itemtypename($id)
	{       
			$itemtype= Itemtype::find()
			->where(['!=', 'trash', 'Deleted'])
			->andwhere(['=', 'type_id', $id])
			->one();
			return $itemtype['type_name'];
	}	
}

