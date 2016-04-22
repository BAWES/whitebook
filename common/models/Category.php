<?php

namespace common\models;

use yii\helpers\ArrayHelper;
use Yii;
use yii\helpers\Url;
use yii\behaviors\SluggableBehavior;

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
            ['category_icon', 'image', 'extensions' => 'png, jpg, jpeg','skipOnEmpty' => false,'minWidth' => 200, 'maxWidth' => 300,'minHeight' => 40, 'maxHeight' =>70,'on' => 'register'],            
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
	
	public  function categoryvalidation($attribute_name,$params)
	{
		if(!empty($this->category_name) ){
		$model = Category::find()
		->where(['category_name'=>$this->category_name])
		->andwhere(['parent_category_id'=>null])->one();
        if($model){
        $this->addError('category_name','Please enter a unique category name');
        }
		}
	}
	
	public static function loadcategoryname()
	{
			$category= Category::find()
			->where(['category_allow_sale'=>'yes'])
			->andwhere(['!=', 'trash', 'Deleted'])
			->andwhere(['=', 'category_level', '0'])
			->andwhere(['parent_category_id' => null])
			->all();
			$category=ArrayHelper::map($category,'category_id','category_name');
			return $category;
	}

	/* BEGIN LOAD CATEGORY FOR EVENT DETAIL PAGE*/
	public static function loadcategoryevents()
	{
			$category= Category::find()
			->where(['category_allow_sale'=>'yes'])
			->andwhere(['!=', 'trash', 'Deleted'])
			->andwhere(['=', 'category_level', '0'])
			->andwhere(['parent_category_id' => null])
			->all();			
			return $category;
	}
	/* END LOAD CATEGORY FOR EVENT DETAIL PAGE*/	
	
	
	public static function loadcategory()
	{
		$categories=Category::find()
		->where(['category_allow_sale' => 'yes'])
		->andwhere(['!=', 'trash', 'Deleted'])
		->andwhere(['parent_category_id' => null])
		->all();
		$category=ArrayHelper::map($categories,'category_id','category_name');
		return $category;
	}

	
		public static function loadgrandcategory()
	{
		$categories=Category::find()
		->where(['category_allow_sale' => 'yes'])
		->andwhere(['!=', 'trash', 'Deleted'])
		->andwhere(['=', 'category_level', '0'])
		->andwhere(['parent_category_id' => null])
		->all();
		$category=ArrayHelper::map($categories,'category_id','category_name');
		return $category;
	}
	
	public static function vendorcategoryname($id)
	{
		$categories=Category::find()
		->where(['category_allow_sale' => 'yes'])
		->andwhere(['vendor_id' => $id])
		->andwhere(['!=', 'trash', 'Deleted'])
		->all();
		$category=ArrayHelper::map($categories,'category_id','category_name');
		return $category;
	}
	
		public static function categorylevel($id)
	{
		
		$categories=Category::find()
		->select(['category_level'])
		->where(['category_allow_sale' => 'yes'])
		->andwhere(['=', 'category_id', $id])
		->andwhere(['!=', 'trash', 'Deleted'])
		->one();
		return  $categories['category_level']; 
	}
	
	// for advert category joi two array
	public static function loadcategoryset($cat)
	{
		$categ=array();
		if(count($cat)>2){
		foreach($cat as $c)
		{
		$categories=Category::find()
		->select(['category_id','category_name'])
		->where(['category_allow_sale' => 'yes'])
		->andwhere(['!=', 'trash', 'Deleted'])
		->andwhere(['=', 'category_id', $c])
		->all();
		
		 $categ[]=ArrayHelper::map($categories,'category_id','category_name');
		} 
		return ($categ); 
	}
		else
	{   
		$categories=Category::find()
		->select(['category_id','category_name'])
		->where(['category_allow_sale' => 'yes'])
		->andwhere(['!=', 'trash', 'Deleted'])
		->andwhere(['=', 'category_id', $cat[0]])
		->all();
		  $cat[]=ArrayHelper::map($categories,'category_id','category_name'); 
	} 
	}

    public static function vendorcategory($id)
    {      
         $vendor = Vendor::find()->select(['category_id'])->where(['vendor_id' => $id])->all();
         $vendor_id = $vendor[0]['category_id'];         
         $vendor_exp = explode(',',$vendor_id);
         $vendor_imp = implode('","',$vendor_exp);
		$categories = User::Category()
		->select(['category_id','category_name'])
		->where(['IN', 'category_id', $vendor_imp])
		->all();
         $category =ArrayHelper::map($categories,'category_id','category_name');
         return $category;
 
    }   
    
    public static function viewcategoryname($id)
	{
		$categories=Category::find()
		->where(['category_allow_sale' => 'yes'])
		->andwhere(['category_id' => $id])
		->andwhere(['!=', 'trash', 'Deleted'])
		->one();
		return $categories['category_name'];
	}
	
	// frontend function 
	public static function category_slug($id)
	{
		$categories=Category::find()
		->where(['category_allow_sale' => 'yes'])
		->andwhere(['category_id' => $id])
		->andwhere(['!=', 'trash', 'Deleted'])
		->one();
		return $categories;
	}
		// frontend function 
	public static function category_value($slug)
	{
		$categoryid=Category::find()
		->where(['slug' => $slug])
		->andwhere(['!=', 'trash', 'Deleted'])
		->one();
		return $categoryid;
	}

    public static function adsbottomupdate($id)
    {
        foreach ($id as $key ) {
             $c_id[] = '"'.$key.'"';               
        }
        $c_id = implode($c_id,',');
        $categories = User::Category()
		->select(['category_name'])
		->where(['IN', 'category_id', $c_id])
		->all();
        return $categories;
    }

    public function upload()
    {
        if ($this->validate()) {
            $this->category_icon->saveAs('uploads/' . $this->category_icon->baseName . '.' . $this->category_icon->extension);
            return true;
        } else {
            return false;
        }
    }
    
    public static function category_search_details($name)
	{
			        $categories = User::Category()
					->select(['category_id','category_name'])
					->where(['trash' =>'Default'])
					->andwhere(['category_allow_sale' => 'Yes'])
					->andwhere(['like', 'category_name', $name])
					->all();
	}

	public static function Vendorcategorylist($ids)
	{	
		$c = explode(",", $ids);
		$ids = implode("','", $c);
		$val = "'".$ids."'";
				$categories = User::Category()
					->select(['category_id','category_name','slug'])
					->where(['trash' =>'Default'])
					->andwhere(['category_allow_sale' => 'Yes'])
					->andwhere(['category_level' => '0'])
					->andwhere(['IN', 'category_id', $val])
					->all();
			return $categories; 
	}    
}
