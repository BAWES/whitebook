<?php
namespace common\models;

use Yii;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

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
class SubCategory extends \yii\db\ActiveRecord
{
    /**
    * @inheritdoc
    */

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
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'modified_by',
            ],
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
            [['parent_category_id','category_level', 'created_by', 'modified_by'], 'integer'],
            [['trash','category_meta_title', 'category_meta_keywords', 'category_meta_description','top_ad','bottom_ad'], 'string'],
            [['parent_category_id','category_name','category_meta_title', 'category_meta_keywords', 'category_meta_description'], 'required'],
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
        return (isset($model->category_name)) ? $model->category_name : '-';
    }

    public static function loadsubcategoryname()
    {
        $subcategoryname= SubCategory::find()
        ->where(['!=', 'trash', 'Deleted'])
        ->andwhere(['!=', 'parent_category_id', 'null'])
        ->all();

        $subcategoryname = ArrayHelper::map($subcategoryname, 'category_id', 'category_name', 'category_name_ar');
        
        return $subcategoryname;
    }

    public static function loadsubcategory($id)
    {
        $subcategoryname= SubCategory::find()
            ->where(['parent_category_id' => $id])
            ->andwhere(['!=', 'trash', 'Deleted'])
            //->andwhere(['=', 'category_level', '1'])
            //->andwhere(['!=', 'parent_category_id', 'null'])
            ->all();

        return ArrayHelper::map($subcategoryname, 'category_id', 'category_name', 'category_name_ar');
    }

    // load sub category front-end plan page
    public static function loadsubcat($slug)
    {
        $category = SubCategory::find()->where(['slug' => $slug])->one();

        if($category){

            return SubCategory::find()
                ->select([
                    '{{%category}}.category_id',
                    '{{%category}}.category_name',
                    '{{%category}}.category_name_ar',
                    '{{%category}}.slug'])
                ->where([
                    '{{%category}}.parent_category_id' => $category['category_id'],
                    '{{%category}}.trash' => 'Default'
                ])
                ->asArray()
                ->all();
        }
        else
        {
            return SubCategory::find()
                ->select([
                    '{{%category}}.category_id',
                    '{{%category}}.category_name',
                    '{{%category}}.category_name_ar',
                    '{{%category}}.slug'])
                ->where('{{%category}}.parent_category_id IS NULL OR 0')
                ->andWhere(['{{%category}}.trash' => 'Default'])
                ->asArray()
                ->all();
        }
    }
}
