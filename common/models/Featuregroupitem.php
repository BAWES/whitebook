<?php

namespace common\models;
use common\models\Featuregroup;
use yii\db\Query;
use Yii;

/**
 * This is the model class for table "whitebook_feature_group_item".
 *
 * @property string $featured_id
 * @property string $group_id
 * @property string $item_id
 * @property string $featured_start_date
 * @property string $featured_end_date
 * @property integer $featured_sort
 * @property string $group_item_status
 * @property integer $created_by
 * @property integer $modified_by
 * @property string $created_datetime
 * @property string $modified_datetime
 * @property string $trash
 *
 * @property FeatureGroup $group
 * @property VendorItem $item
 */
class Featuregroupitem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $themelist;
    public static function tableName()
    {
        return 'whitebook_feature_group_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['group_id', 'item_id'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'featured_id' => 'Featured',
            'group_id' => 'Group',
            'item_id' => 'Item ',
            'featured_start_date' => 'Featured Start Date',
            'featured_end_date' => 'Featured End Date',
            'featured_sort' => 'Featured Sort',
            'group_item_status' => 'Group Item Status',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'created_datetime' => 'Created Datetime',
            'modified_datetime' => 'Modified Datetime',
            'trash' => 'Trash',
            'subcategory_id'=>'Sub category',
            'category_id'=>'category',
            'vendor_id'=>'Vendor Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroup()
    {
        return $this->hasOne(FeatureGroup::className(), ['group_id' => 'group_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItem()
    {
        return $this->hasOne(VendorItem::className(), ['item_id' => 'item_id']);
    }
    
    public static function getGroupName($id)
    {		
		$model = Featuregroup::find()->where(['group_id'=>$id])->one();
        return $model->group_name;
    }
 
 		public static function groupdetails($t)
	{
			$id= Featuregroupitem::find()
			->select(['group_id'])
			->where(['=', 'item_id', $t])
			->one();
			 $id=$id['group_id']; 
			//print_r ($id);die;
			 $k=explode(',',$id);
			 foreach ($k as $key=>$value)
			 {
			 $group_name[]= Featuregroup::find()
			->select('group_name')
			->where(['!=', 'group_status', 'Deactive'])
			->andwhere(['!=', 'trash', 'Deleted'])
			->andwhere(['group_id' => $value])
			->one();
			}
			$i=0;
			 foreach ($group_name as $key=>$value)
			 {
				 $grouplist[]=$group_name[$i]['group_name'];
				 $i++;
			 }
			 return implode(", ",$grouplist);
	}
	
	
 	public static function loadcategoryname()
	{
			$output= Featuregroupitem::find()
			->where(['!=', 'category_status', 'Deactive'])
			->where(['!=', 'trash', 'Deleted'])
			->where(['parent_category_id' => null])
			->all();
			$category=ArrayHelper::map($category,'category_id','category_name');
			return $category;
	}
 	public static function similiar_details()
	{
		$query = new Query;
		$query->select([
		'whitebook_feature_group_item.item_id AS gid', 
		'whitebook_vendor.vendor_name AS vname', 
		'whitebook_vendor_item.item_name AS iname',
		'whitebook_vendor_item.slug AS slug', 
		'whitebook_vendor_item.item_price_per_unit AS price']
		)  
	->from('whitebook_feature_group_item')
	->join('LEFT OUTER JOIN', 'whitebook_vendor',
				'whitebook_vendor.vendor_id =whitebook_feature_group_item.vendor_id')		
	->join('LEFT OUTER JOIN', 'whitebook_vendor_item', 
				'whitebook_feature_group_item.item_id =whitebook_vendor_item.item_id')
	->where('whitebook_feature_group_item.group_item_status ="Active"')			
	->andwhere('whitebook_feature_group_item.trash ="Default"')			
	->LIMIT(50)	; 
		
$command = $query->createCommand();
$data = $command->queryAll();	
return ($data);

	}
	
}
