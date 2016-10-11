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
    /**
     * Returns category details
     * @param integer category_id
     * @return category active query object
     */
    public static function category_slug($id)
    {
      return $categories=Category::find()
        ->where(['category_allow_sale' => 'yes'])
        ->andwhere(['category_id' => $id])
        ->andwhere(['!=', 'trash', 'Deleted'])
        ->one();
    }
  
    /**
     * Returns category details
     * @param string slug
     * @return category active query object
     */
    public static function category_value($slug)
    {
      return Category::find()
         ->where(['slug' => $slug])
         ->andwhere(['!=', 'trash', 'Deleted'])
         ->asArray()
         ->one();
    }
}
