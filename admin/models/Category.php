<?php

namespace admin\models;

use Yii;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\behaviors\BlameableBehavior;


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
class Category extends \common\models\Category
{

    public function behaviors()
    {
        return parent::behaviors();
    }
     /**
     * @inheritdoc
     */
    public function rules()
    {
           return array_merge(parent::rules(), [
            ['category_name','categoryvalidation','on' => 'insert',],   
            [['parent_category_id', 'created_by', 'modified_by',], 'integer'],
            [['trash', 'category_meta_title', 'category_meta_keywords', 'category_meta_description'], 'string'],
            [['category_name', 'category_meta_title', 'category_meta_keywords', 'category_meta_description'], 'required'],
            ['category_allow_sale', 'default', 'value' => true],
            [['category_name', 'category_meta_title', 'category_meta_keywords', 'category_meta_description'], 'required','on' => 'register'],
            [['parent_category_id','category_name',], 'required','on' => 'sub_update',],
            [['created_datetime', 'modified_datetime','top_ad','bottom_ad'], 'safe'],
            [['category_name'], 'string', 'max' => 128]
        ]);
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();      
        $scenarios['sub_update'] = ['parent_category_id','category_name',];//Scenario Values Only Accepted
        $scenarios['register'] = ['category_name', 'category_meta_title', 'category_meta_keywords', 'category_meta_description'];
        return $scenarios;
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
 
    public static function viewcategoryname($id)
    {
     $categories=Category::find()
     ->where(['category_allow_sale' => 'yes'])
     ->andwhere(['category_id' => $id])
     ->andwhere(['!=', 'trash', 'Deleted'])
     ->one();
     return $categories['category_name'];
    }


    public function statusImageurl($img_status)
    {
        if($img_status == 'Active')     
        return \yii\helpers\Url::to('@web/uploads/app_img/active.png');
        return \yii\helpers\Url::to('@web/uploads/app_img/inactive.png');
    }

    // Status Image title
    public function statusTitle($status)
    {           
    if($status == 'Active')     
        return 'Activate';
        return 'Deactivate';
    }


}