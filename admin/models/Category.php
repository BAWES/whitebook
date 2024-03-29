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
            ['category_name', 'categoryvalidation'],               
            [['parent_category_id', 'created_by', 'modified_by'], 'integer'],
            [['trash', 'category_meta_title', 'category_meta_keywords', 'category_meta_description'], 'string'],
            [['category_name', 'category_name_ar', 'category_meta_title', 'category_meta_keywords', 'category_meta_description'], 'required'],
            [['created_datetime', 'modified_datetime','top_ad','bottom_ad','parent_category_id'], 'safe'],
            [['category_name','category_name_ar'], 'string', 'max' => 128]
        ]);
    }

    public  function categoryvalidation($attribute_name, $params)
    {
        $query = Category::find()
            ->where([
                'category_name' => $this->category_name,
                'parent_category_id' => $this->parent_category_id,
                'trash' => 'Default'
            ]);

        if($this->category_id)
        {
            $model = $query
                ->andWhere(['!=', 'category_id', $this->category_id])
                ->one();
        }       
        else
        {
            $model = $query->one();
        }            

        if($model)
        {
            $this->addError('category_name', 'Please enter a unique category name');
            return false;
        }

        return true;
    } 

    public static function loadcategory()
    {
        $categories = Category::find()
            ->where(['!=', 'trash', 'Deleted'])
            ->andwhere(['parent_category_id' => null])
            ->all();

        return ArrayHelper::map($categories, 'category_id', 'category_name');
    }

    public static function loadcategoryname()
    {
        $category = Category::find()
            ->where(['!=', 'trash', 'Deleted'])
            ->andwhere(['=', 'category_level', '0'])
            ->andwhere(['parent_category_id' => null])
            ->all();

        return ArrayHelper::map($category,'category_id','category_name');
    }
 
    public static function viewcategoryname($id)
    {
        $categories=Category::find()
            ->where(['category_id' => $id])
            ->andwhere(['!=', 'trash', 'Deleted'])
            ->one();

        return $categories['category_name'];
    }

    public static function statusImageurl($img_status)
    {
        if($img_status == 'yes')
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