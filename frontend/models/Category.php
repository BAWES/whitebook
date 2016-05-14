<?php
namespace frontend\models;

use yii\helpers\ArrayHelper;
use Yii;
use yii\helpers\Url;

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

 /* BEGIN LOAD CATEGORY FOR EVENT DETAIL PAGE USED FRONTEND*/
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
 
   /* Function used frontend  */
   public static function category_slug($id)
   {
    $categories=Category::find()
    ->where(['category_allow_sale' => 'yes'])
    ->andwhere(['category_id' => $id])
    ->andwhere(['!=', 'trash', 'Deleted'])
    ->one();
    return $categories;
   }

  /* Function used frontend  */
  public static function category_value($slug)
  {
   $categoryid=Category::find()
   ->where(['slug' => $slug])
   ->andwhere(['!=', 'trash', 'Deleted'])
   ->asArray()
   ->one();
   return $categoryid;
  }

  /* Function used frontend  */
 public static function category_search_details($name)
 {
    $categories = Category::find()
     ->select(['category_id','category_name'])
     ->where(['trash' =>'Default'])
     ->andwhere(['category_allow_sale' => 'Yes'])
     ->andwhere(['like', 'category_name', $name])
     ->all();
 }

   /* Function used frontend  */
 public static function Vendorcategorylist($ids)
 { 
  $c = explode(",", $ids);
  $ids = implode("','", $c);
  $val = "'".$ids."'";
    $categories = Category::find()
     ->select(['category_id','category_name','slug'])
     ->where(['trash' =>'Default'])
     ->andwhere(['category_allow_sale' => 'Yes'])
     ->andwhere(['category_level' => '0'])
     ->andwhere(['IN', 'category_id', $val])
     ->all();
   return $categories; 
 }    
}
