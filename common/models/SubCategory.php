<?php
namespace common\models;

use Yii;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
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
class SubCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */

    public $subcategory_icon;
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
			      ['category_name','subcategoryvalidation'],
            [['parent_category_id','category_level', 'created_by', 'modified_by'], 'integer'],
            [['category_allow_sale', 'trash','category_meta_title', 'category_meta_keywords', 'category_meta_description','top_ad','bottom_ad'], 'string'],
            [['parent_category_id','category_name','category_meta_title', 'category_meta_keywords', 'category_meta_description'], 'required'],
            ['category_icon', 'image', 'extensions' => 'png, jpg, jpeg'],
			      ['subcategory_icon', 'image', 'extensions' => 'png, jpg, jpeg','skipOnEmpty' => true,'minWidth' => 200, 'maxWidth' => 300,'minHeight' => 40, 'maxHeight' =>70],
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
			->andwhere(['=', 'category_level', '1'])
			->andwhere(['!=', 'parent_category_id', 'null'])
			->all();
			$subcategoryname=ArrayHelper::map($subcategoryname,'category_id','category_name');
			return $subcategoryname;
	}

  // load sub category front-end plan page
  public static function loadsubcat($slug)
  {
      $subcategory_slug= SubCategory::find()->where(['slug'=>$slug])->one();
      $subcategory = Yii::$app->db->createCommand('SELECT wvi.subcategory_id as category_id ,wc.category_name,wc.slug FROM whitebook_vendor_item as wvi  INNER JOIN whitebook_category as wc ON wc.category_allow_sale= "yes" and wc.trash="Default" and wc.category_level = 1 and wvi.subcategory_id = wc.category_id and wvi.item_for_sale="Yes" and wvi.item_approved="Yes" and wvi.item_status = "Active" and parent_category_id = '.$subcategory_slug['category_id'].' group by wvi.subcategory_id')->queryAll(); 
     return $subcategory;
  }
}
