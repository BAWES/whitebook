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
use common\models\CustomerAddress;

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

        $q = SubCategory::find()
                ->select([
                    '{{%category}}.category_id',
                    '{{%category}}.category_name',
                    '{{%category}}.category_name_ar',
                    '{{%category}}.slug'
                ])
                ->where(['{{%category}}.trash' => 'Default']);                  
                
        if($category)
        {
            $q->andWhere(['{{%category}}.parent_category_id' => $category['category_id']]);            
        }
        else
        {
            $q->andWhere('{{%category}}.parent_category_id IS NULL OR 0');                
        }

        $rows = $q->asArray()
                ->all();

        $result = [];

        foreach ($rows as $key => $value) 
        {
            if(SubCategory::have_item($value['category_id']))
            {
                $result[] = $value;
            }
        }

        return $result;
    }

    private static function have_item($category_id)
    {        
        $session = Yii::$app->session;
        
        $data = Yii::$app->request->get();

        $subQuery = CategoryPath::find()
            ->select('{{%vendor_item}}.item_id')
            ->leftJoin(
                '{{%vendor_item_to_category}}',
                '{{%vendor_item_to_category}}.category_id = {{%category_path}}.category_id'
            )
            ->leftJoin(
                '{{%vendor_item}}',
                '{{%vendor_item}}.item_id = {{%vendor_item_to_category}}.item_id'
            )
            ->leftJoin('{{%vendor}}', '{{%vendor_item}}.vendor_id = {{%vendor}}.vendor_id')
            ->where([
                '{{%vendor_item}}.trash' => 'Default',
                '{{%vendor_item}}.item_status' => 'Active',
                '{{%vendor_item}}.item_approved' => 'Yes',
                '{{%vendor}}.vendor_status' => 'Active',
                '{{%vendor}}.approve_status' => 'Yes',
                '{{%vendor}}.trash' => 'Default',
                '{{%category_path}}.path_id' => $category_id
            ])
            ->groupBy('{{%vendor_item}}.item_id');

        //theme filter
        if (!empty($data['themes'])) 
        {
            $subQuery->leftJoin('{{%vendor_item_theme}}', '{{%vendor_item}}.item_id = {{%vendor_item_theme}}.item_id');
            $subQuery->leftJoin('{{%theme}}', '{{%theme}}.theme_id = {{%vendor_item_theme}}.theme_id');

            $subQuery->andWhere(['IN', '{{%theme}}.slug', $data['themes']]);
        }
        
        if (isset($data['price']) && $data['price'] != '') 
        {
            $arr_min_max = explode('-', $data['price']);    

            $subQuery->andWhere('{{%vendor_item}}.item_price_per_unit IS NULL OR {{%vendor_item}}.item_price_per_unit between '.$arr_min_max[0].' and '.$arr_min_max[1]);
        }

        if (!empty($data['vendor'])) 
        {
            if(is_array($data['vendor']))
            {               
                $subQuery->andWhere('{{%vendor}}.slug IN ("'.implode('","', $data['vendor']).'")');    
            }
            else
            {
                $subQuery->andWhere('{{%vendor}}.slug = "'.$data['vendor'].'"');    
            }
        }

        if ($session->has('deliver-location')) {

            if (is_numeric($session->get('deliver-location'))) {
                $location = $session->get('deliver-location');
            } else {
                $end = strlen($session->get('deliver-location'));
                $from = strpos($session->get('deliver-location'), '_') + 1;
                $address_id = substr($session->get('deliver-location'), $from, $end);

                $location = CustomerAddress::findOne($address_id)->area_id;
            }

            $subQuery->andWhere('EXISTS (SELECT 1 FROM {{%vendor_location}} WHERE {{%vendor_location}}.area_id="'.$location.'" AND {{%vendor_item}}.vendor_id = {{%vendor_location}}.vendor_id)');
        }

        if ($session->has('deliver-date')) {
            $date = date('Y-m-d', strtotime($session->get('deliver-date')));
            $subQuery->andWhere("({{%vendor}}.vendor_id NOT IN(SELECT vendor_id FROM `whitebook_vendor_blocked_date` where block_date = '".$date."'))");
        }

        return $subQuery->one();
    }
}
