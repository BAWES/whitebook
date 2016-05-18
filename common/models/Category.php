<?php

namespace common\models;

use yii\helpers\ArrayHelper;
use Yii;
use yii\helpers\Url;
use yii\behaviors\SluggableBehavior;
use common\models\User;

/**
 * This is the model class for table "whitebook_category".
 *
 * @property string $category_id
 * @property string $parent_category_id
 * @property string $category_name
 * @property string $category_allow_sale
 * @property integer $created_by
 * @property integer $modified_by
 * @property string $created_datetime
 * @property string $modified_datetime
 * @property string $trash
 *
 * @property AdvertCategory[] $advertCategories
 * @property Category $parentCategory
 * @property Category[] $categories
 * @property VendorItem[] $vendorItems
 * @property VendorItemRequest[] $vendorItemRequests
 */
class Category extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = "Active";
    const STATUS_DEACTIVE = "Deactive";
    /**
     * @inheritdoc
     */
    public $subcategory_icon;
    public $category_icon;
    public static function tableName()
    {
     return '{{%category}}';        
    }

    public function behaviors()
    {
          return [
              [
                  'class' => SluggableBehavior::className(),
                  'attribute' => 'category_name',              
              ],
          ];
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
			         ['category_name','categoryvalidation','on' => 'insert',],			
            [['parent_category_id', 'created_by', 'modified_by',], 'integer'],
            [['trash', 'category_meta_title', 'category_meta_keywords', 'category_meta_description'], 'string'],
            [['category_name', 'category_meta_title', 'category_meta_keywords', 'category_meta_description'], 'required'],
            ['category_allow_sale', 'default', 'value' => true],
            [['parent_category_id','category_name',], 'required','on' => 'sub_update',],
            [['created_datetime', 'modified_datetime','top_ad','bottom_ad'], 'safe'],
            [['category_name'], 'string', 'max' => 128]
        ];
    }
    
    public function scenarios()
    {
		      $scenarios = parent::scenarios();      
        $scenarios['sub_update'] = ['parent_category_id','category_name',];//Scenario Values Only Accepted
        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'category_id' => 'Category name',
            'parent_category_id' => 'Parent category',
            'category_name' => 'Category name',
            'category_allow_sale' => 'Category allow status',
            'created_by' => 'Created by',
            'modified_by' => 'Modified by',
            'created_datetime' => 'Created datetime',
            'modified_datetime' => 'Modified datetime',
            'trash' => 'Trash',
            'category_meta_title' => 'Category meta title',
            'category_meta_keywords' => 'Category meta keywords',
            'category_meta_description' => 'Category meta description',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParentCategory()
    {
        return $this->hasOne(Category::className(), ['category_id' => 'parent_category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(Category::className(), ['parent_category_id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVendorItems()
    {
        return $this->hasMany(VendorItem::className(), ['category_id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVendorItemRequests()
    {
        return $this->hasMany(VendorItemRequest::className(), ['category_id' => 'category_id']);
    }
    

    public static function vendorcategory($id)
    {      
         $vendor = Vendor::find()->select(['category_id'])->where(['vendor_id' => $id])->all();
         $vendor_id = $vendor[0]['category_id'];         
         $vendor_exp = explode(',',$vendor_id);
         //$vendor_imp = implode('","',$vendor_exp);
         $categories = Category::find()
        ->select(['category_id','category_name'])
        ->where(['IN', 'category_id', $vendor_exp])
        ->all();
         $category =ArrayHelper::map($categories,'category_id','category_name');
         return $category;
 
    }
}
