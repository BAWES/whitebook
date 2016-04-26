<?php

namespace common\models;


use Yii;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
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
class ChildCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
     
    public $childcategory_icon;
    public $subcategory_id;
    public $grand_category_id;    
    public static function tableName()
    {
        return '{{%category}}';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_category_id','category_level', 'created_by', 'modified_by'], 'integer'],
            [['category_allow_sale', 'trash','category_meta_title', 'category_meta_keywords', 'category_meta_description','top_ad','bottom_ad','slug'], 'string'],
            [['parent_category_id','category_name','category_meta_title', 'category_meta_keywords', 'category_meta_description'], 'required'],			
			['childcategory_icon', 'image', 'extensions' => 'png, jpg, jpeg','skipOnEmpty' => true,'minWidth' => 200, 'maxWidth' => 300,'minHeight' => 40, 'maxHeight' =>70],
            [['category_name'], 'string', 'max' => 128]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'category_id' => 'Category name',
            'parent_category_id' => 'Parent Category',
            'category_name' => 'Category name',
            'category_allow_sale' => 'Category Allow status',            
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
    public function getAdvertCategories()
    {
        return $this->hasMany(AdvertCategory::className(), ['category_id' => 'category_id']);
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
    
    
    public static function statusImageurl($sale)
	{			
		if($sale == 'yes')			
		return \yii\helpers\Url::to('@web/uploads/app_img/active.png');
		return \yii\helpers\Url::to('@web/uploads/app_img/inactive.png');
	}
		public static function statusTitle($sale)
	{			
		if($sale == 'yes')		
		return 'Active';
		return 'Deactive';
	}
		 public static function getCategoryName($id)
    {		
		$model = Category::find()->where(['category_id'=>$id])->one();
        return $model->category_name;
    }
		public static function getGrandCategoryName($id)
    {	
		$model = Category::find()
		->select(['parent_category_id'])
		->where(['category_id'=>$id])
		->one();
		  $parent=$model['parent_category_id']; 
		
		$model = Category::find()
		->select(['category_name'])
		->where(['category_id'=>$parent])
		->one();
		return $parent=$model['category_name'];
    }
    
		public static function subcategoryvalidation($attribute_name,$params)
	{
		if(!empty($this->category_name) ){
		$model = Category::find()
		->where(['category_name'=>$this->category_name])
		->andwhere(['!=', 'parent_category_id',null])
		->one();
        if($model){
        $this->addError('category_name','Please enter a unique Sub category name');
        }
		}
	}
	public static function loadsubcategoryname()
	{       
			$subcategoryname= SubCategory::find()
			->where(['!=', 'category_allow_sale', 'no'])
			->andwhere(['!=', 'trash', 'Deleted'])
			->andwhere(['!=', 'parent_category_id', 'null'])
			->all();
			$subcategoryname=ArrayHelper::map($subcategoryname,'category_id','category_name');
			return $subcategoryname;
	}	
	
	 public static function loadsubcategory($id)
	{       
			$subcategoryname= SubCategory::find()
			->where(['parent_category_id'=>$id])
			->andwhere(['!=', 'category_allow_sale', 'no'])
			->andwhere(['!=', 'trash', 'Deleted'])
			->andwhere(['!=', 'parent_category_id', 'null'])
			->all();
			$subcategoryname=ArrayHelper::map($subcategoryname,'category_id','category_name');
			return $subcategoryname;
	}
	
	public static function loadchildcategory($id)
   {       
         $childcategoryname= ChildCategory::find()
         ->where(['parent_category_id'=>$id])
         ->andwhere(['!=', 'category_allow_sale', 'no'])
         ->andwhere(['!=', 'trash', 'Deleted'])
         ->andwhere(['=', 'category_level', '2'])
         ->andwhere(['!=', 'parent_category_id', 'null'])
         ->all();
         $childcategory=ArrayHelper::map($childcategoryname,'category_id','category_name');
         return $childcategory;
     }
     
    public static function loadchild()
    {       
         $childcategoryname= ChildCategory::find()
         ->where(['!=', 'category_allow_sale', 'no'])
         ->andwhere(['!=', 'trash', 'Deleted'])
         ->andwhere(['=', 'category_level', '2'])
         ->andwhere(['!=', 'parent_category_id', 'null'])
         ->all();
         $childcategory=ArrayHelper::map($childcategoryname,'category_id','category_name');
         return $childcategory;
    }

    /* load child category used by frontend*/
    public static function loadchildcategoryslug($id)
   {
	   
	   $childcategory=Category::find()->select(['{{%category}}.category_id','{{%category}}.category_name'])
			->leftJoin('{{%vendor_item}}', '{{%category}}.category_id = {{%vendor_item}}.child_category')
			->where(['{{%category}}.category_allow_sale'=>'Yes'])
			->andwhere(['{{%category}}.trash'=>'Default'])
			->andwhere(['{{%category}}.category_level'=>'2'])
			->andwhere(['{{%vendor_item}}.item_for_sale'=>'Yes'])
			->andwhere(['{{%vendor_item}}.item_approved'=>'Yes'])
			->andwhere(['{{%vendor_item}}.item_status'=>'Active'])
			->andwhere(['{{%vendor_item}}.parent_category_id'=>$id])
			->groupby(['{{%vendor_item}}.child_category'])
			->asArray()
			->all();
			
        return $childcategory;
   }	

}
