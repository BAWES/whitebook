<?php

namespace common\models;
use common\models\FeatureGroup;
use common\models\VendorItem;
use yii\db\Query;
use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
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
class FeatureGroupItem extends \yii\db\ActiveRecord
{
    /**
    * @inheritdoc
    */
    public $themelist;
    public static function tableName()
    {
        return 'whitebook_feature_group_item';
    }


    public function behaviors()
    {
        return [
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
            'group_item_status' => 'Group Item Status',
            'created_datetime' => 'Created Datetime',
            'modified_datetime' => 'Modified Datetime',
            'trash' => 'Trash',
            'vendor_id'=>'Vendor Name',
        ];
    }

    public function getItemName() {
        return $this->item->item_name;
    }

    public function getVendorName() {
        return $this->vendor->vendor_name;
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

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getVendor()
    {
        return $this->hasOne(Vendor::className(), ['vendor_id' => 'vendor_id']);
    }

    public static function getGroupName($id)
    {
        $model = FeatureGroup::find()->where(['group_id'=>$id])->one();
        return $model->group_name;
    }

    public static function groupdetails($t)
    {
        $id= FeatureGroupItem::find()
        ->select(['group_id'])
        ->where(['=', 'item_id', $t])
        ->one();
        $id=$id['group_id'];
        //print_r ($id);die;
        $k=explode(',',$id);
        foreach ($k as $key=>$value)
        {
            $group_name[]= FeatureGroup::find()
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


    public static function groupList($model){
        $string = [];
        if (isset($model->featureGroupItems) && count($model->featureGroupItems)>0) {
            foreach ($model->featureGroupItems as $theme) {
                $string[] = ucfirst($theme->group->group_name);
            }
        }
        return implode(', ',$string);
    }

    public static function loadcategoryname()
    {
        $output= FeatureGroupItem::find()
        ->where(['!=', 'category_status', 'Deactive'])
        ->where(['!=', 'trash', 'Deleted'])
        ->where(['parent_category_id' => null])
        ->all();
        $category=ArrayHelper::map($category,'category_id','category_name');
        return $category;
    }
    
    public static function get_featured_product_id() {
        $db = Yii::$app->db;
        $today = date('Y-m-d H:i:s');
        $today_date = date('Y-m-d');

        return $vendor = FeatureGroupItem::find()
            ->select('{{%feature_group_item}}.item_id')
            ->joinWith('vendor')
            ->where(['{{%feature_group_item}}.group_item_status' => 'Active','{{%vendor}}.trash' => 'Default','{{%vendor}}.approve_status' => 'Yes'])
            ->all();
    }
}
