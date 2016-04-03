<?php

namespace admin\models;

use Yii;
use yii\helpers\ArrayHelper;
/**
 * This is the model class for table "whitebook_priority_item".
 *
 * @property string $priority_id
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
 */
class Priorityitem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */

    /* Attribute created for filter (create page). */
    public $filter_start;
    public $filter_end;
    public $item_status;

    public static function tableName()
    {
        return 'whitebook_priority_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            [['item_id',  'priority_start_date', 'priority_end_date', 'priority_level'], 'required'],
            [['category_id','subcategory_id','child_category'], 'default', 'value' => 0],
            [['created_by','category_id', 'subcategory_id','child_category', 'modified_by'], 'integer'],
            [['priority_level', 'trash'], 'string'],
            [['priority_start_date', 'priority_end_date', 'created_datetime', 'modified_datetime'], 'safe'],
            ['item_id', 'unique', 'targetAttribute' => ['priority_level', 'priority_start_date','priority_end_date'],'message' => 'Item name, Priority level, Start date, End date are already exists .' ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'priority_id' => 'Priority Name',
            'vendor_id'=>'Vendor Name',
            'item_id' => 'Item Name',
            'priority_level' => 'Priority level',
            'priority_start_date' => 'Priority Start Date',
            'priority_end_date' => 'Priority End Date',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'created_datetime' => 'Created Datetime',
            'modified_datetime' => 'Modified Datetime',
            'trash' => 'Trash',
            'subcategory_id'=>'Sub category',
            'category_id'=>'category',
            'child_category'=>'Child category'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItem()
    {
        return $this->hasOne(VendorItem::className(), ['item_id' => 'item_id']);
    }
    public function getpriorityitem()
    {
        return $this->hasOne(Vendoritem::className(), ['item_id' => 'item_id']);
    }

        public function getvendoritem()
    {
        return $this->hasOne(Vendoritem::className(), ['item_id' => 'item_id']);
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

    public static function grouppriorityitem($vendor_id,$categoryid,$subcategory)
	{
			$priority_item= Priorityitem::find()
			->where(['=', 'vendor_id',$vendor_id])
			->where(['=', 'category_id', $categoryid])
			->where(['=', 'subcategory_id',$subcategory])
			->all();
			$priority_item=ArrayHelper::map($priority_item,'item_id','item_name');
			return $priority_item;
	}
}
