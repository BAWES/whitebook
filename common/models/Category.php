<?php

namespace common\models;

use yii\helpers\ArrayHelper;
use Yii;
use yii\helpers\Url;
use yii\db\ActiveRecord;
use yii\db\Expression;
use common\models\User;
use common\models\VendorCategory;
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
class Category extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = "Active";
    const STATUS_DEACTIVE = "Deactive";
    const FIRST_LEVEL = 0;
    const SECOND_LEVEL = 1;
    const THIRD_LEVEL = 2;

    /* CATEGORY LISTS */
    const VENUES = 125;
    const INVITATIONS = 103;
    const FOOD_BEVERAGES = 85;
    const DECOR = 86;
    const SUPPLIES = 101;
    const ENTERTAINMENT = 87;
    const SERVICES = 102;
    const OTHERS = 126;
    const GIFT_FAVORS = 127;

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
            [['parent_category_id', 'created_by', 'modified_by',], 'integer'],
            [['trash', 'category_meta_title', 'category_meta_keywords', 'category_meta_description'], 'string'],
            [['category_name', 'category_meta_title', 'category_meta_keywords', 'category_meta_description'], 'required'],
            [['category_title', 'created_datetime', 'modified_datetime','top_ad','bottom_ad'], 'safe'],
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
            'category_name_ar' => 'Category name - Arabic',
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

    public function getCategory_title() 
    { 
        $result = CategoryPath::find()
            ->select("GROUP_CONCAT(c1.category_name ORDER BY {{%category_path}}.level SEPARATOR '&nbsp;&nbsp;&gt;&nbsp;&nbsp;') AS category_name, {{%category_path}}.category_id")
            ->leftJoin('whitebook_category c1', 'c1.category_id = whitebook_category_path.path_id')
            ->leftJoin('whitebook_category c2', 'c2.category_id = whitebook_category_path.category_id')
            ->where(['{{%category_path}}.category_id' => $this->category_id])
            ->groupBy('{{%category_path}}.category_id')
            ->orderBy('category_name')
            ->asArray()
            ->one();

        if($result) {
            return $result['category_name'];
        }else{
            return $this->category_name;
        }
    }

    public static function vendorcategory($id)
    {
        $categories = VendorCategory::find()
            ->select('{{%category}}.category_name, {{%category}}.category_id')
            ->innerJoin('{{%category}}', '{{%category}}.category_id = {{%vendor_category}}.category_id')
            ->where([
                '{{%vendor_category}}.vendor_id' => $id,
                '{{%category}}.trash' => 'default'
            ])
            ->asArray()
            ->all();

        $category = ArrayHelper::map($categories, 'category_id', 'category_name');

        return $category;
    }


    /**
     * @inheritdoc
     * @return VendorQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\CategoryQuery(get_called_class());
    }
}
