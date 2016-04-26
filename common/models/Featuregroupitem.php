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
		$data=Featuregroupitem::find()->select(['{{%feature_group_item}}.item_id as gid','{{%vendor}}.vendor_name as vname','{{%vendor_item}}.item_name as iname','{{%vendor_item}}.slug as slug'])
		->leftJoin('{{%vendor}}', '{{%feature_group_item}}.vendor_id = {{%vendor}}.vendor_id')
		->leftJoin('{{%vendor}}', '{{%vendor_item}}.item_id = {{%feature_group_item}}.item_id')
		->where(['{{%feature_group_item}}.group_item_status'=>'Active'])
		->andwhere(['{{%feature_group_item}}.trash'=>'Default'])
		->asArray()
		->all();
    return ($data);
	}

    public static function get_featured_product_id() {
        $db = Yii::$app->db;
   //     return $p_id = $db->cache(function ($db) {
            $today = date('Y-m-d H:i:s');
            $today_date = date('Y-m-d');
            
            return $vendor = Featuregroupitem::find()
                    ->select('{{%feature_group_item}}.item_id')
                    ->leftJoin('{{%vendor}}', '{{%vendor}}.vendor_id = {{%feature_group_item}}.vendor_id')
                    ->where(['{{%feature_group_item}}.group_item_status' => 'Active','{{%vendor}}.trash' => 'Default','{{%vendor}}.approve_status' => 'Yes'])
                    ->andwhere(['<=','{{%vendor}}.package_start_date',$today])
                    ->andwhere(['>=','{{%vendor}}.package_end_date',$today])
                    ->andwhere(['<=','{{%feature_group_item}}.featured_start_date',$today_date])
                    ->andwhere(['>=','{{%feature_group_item}}.featured_end_date',$today_date])
                    ->all();
    }

    public static function get_featured_product() {
        $today = date('Y-m-d H:i:s');
        
        return $feature = Vendoritem::find()
                    ->select(['{{%vendor}}.vendor_name','{{%vendor_item}}.item_id','{{%vendor_item}}.slug as slug','{{%vendor_item}}.item_name','{{%vendor_item}}.item_price_per_unit'])
                    ->leftJoin('{{%vendor}}', '{{%vendor}}.vendor_id = {{%vendor_item}}.vendor_id')
                    ->leftJoin('{{%category}}', '{{%category}}.category_id = {{%vendor_item}}.category_id')
                    ->where(['{{%vendor_item}}.item_status' => 'Active'])
                    ->asArray()
                    ->all();
    }
	
}
