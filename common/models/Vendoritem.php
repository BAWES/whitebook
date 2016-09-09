<?php
namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;
use common\models\Vendor;
use yii\db\ActiveRecord;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
* This is the model class for table "whitebook_vendor_item".
*
* @property string $item_id
* @property string $type_id
* @property string $vendor_id
* @property string $category_id
* @property string $item_name
* @property string $item_description
* @property string $item_additional_info
* @property integer $item_amount_in_stock
* @property integer $item_default_capacity
* @property string $item_price_per_unit
* @property string $item_customization_description
* @property string $item_price_description
* @property string $item_for_sale
* @property integer $item_how_long_to_make
* @property integer $item_minimum_quantity_to_order
* @property string $item_archived
* @property string $item_approved
* @property integer $created_by
* @property integer $modified_by
* @property string $created_datetime
* @property string $modified_datetime
* @property string $trash
*
* @property CustomerCart[] $customerCarts
* @property EventItemLink[] $eventItemLinks
* @property FeatureGroupItem[] $featureGroupItems
* @property PriorityItem[] $priorityItems
* @property SuborderItemPurchase[] $suborderItemPurchases
* @property ItemType $type
* @property Vendor $vendor
* @property Category $category
* @property VendorItemCapacityException[] $vendorItemCapacityExceptions
* @property VendorItemImage[] $vendorItemImages
* @property VendorItemPricing[] $vendorItemPricings
* @property VendorItemQuestion[] $vendorItemQuestions
* @property VendorItemRequest[] $vendorItemRequests
* @property VendorItemTheme[] $vendorItemThemes
* @property Theme[] $themes
*/
class Vendoritem extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = "Active";
    const STATUS_DEACTIVE = "Deactive";
    const UPLOADFOLDER_210 = "vendor_item_images_210/";
    const UPLOADFOLDER_530 = "vendor_item_images_530/";
    const UPLOADFOLDER_1000 = "vendor_item_images_1000/";
    const UPLOADSALESGUIDE = "sales_guide_images/";
    public $themes;
    public $groups;
    public $image_path;
    public $guide_image;
    /**
    * @inheritdoc
    */
    public static function tableName()
    {
        return 'whitebook_vendor_item';
    }

    public function behaviors()
    {
        return [
            [
                'class' => SluggableBehavior::className(),
                'slugAttribute' => 'slug',
                'attribute' => 'item_name',
                'immutable' => true,
                'ensureUnique'=>true,
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
            [['type_id', 'vendor_id', 'category_id', 'item_name', 'item_name_ar', 'subcategory_id',
            'child_category'], 'required'],
            [['type_id', 'vendor_id', 'category_id','subcategory_id', 'item_amount_in_stock', 'item_default_capacity', 'item_how_long_to_make', 'item_minimum_quantity_to_order','child_category', 'created_by', 'modified_by'], 'integer'],
            [['item_description','item_description_ar','item_additional_info','item_additional_info_ar', 'item_customization_description', 'item_price_description','item_price_description_ar', 'item_for_sale', 'item_approved', 'trash'], 'string'],
            [['item_price_per_unit'], 'number'],
            [['created_datetime', 'modified_datetime','item_status','image_path'], 'safe'],
            [['item_name', 'item_name_ar'], 'string', 'max' => 128],
            [['image_path'],'image', 'extensions' => 'png,jpg,jpeg','maxFiles'=>20],

            // set scenario for vendor item add functionality
            [['type_id', 'category_id',  'item_description','item_description_ar', 'item_additional_info','item_additional_info_ar', 'item_amount_in_stock',
            'item_default_capacity', 'item_customization_description', 'item_price_description','item_price_description_ar', 'item_how_long_to_make',
            'item_minimum_quantity_to_order','item_name', 'item_name_ar', 'subcategory_id',
            'item_for_sale','item_price_per_unit'], 'required', 'on'=>'VendorItemAdd'],
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['VendorItemAdd'] = ['type_id', 'category_id',  'item_description','item_description_ar', 'item_additional_info','item_additional_info_ar', 'item_amount_in_stock',
        'item_default_capacity', 'item_customization_description', 'item_price_description',
            'item_price_description_ar', 'item_how_long_to_make',
        'item_minimum_quantity_to_order','item_name','item_name_ar', 'subcategory_id','child_category',
        'item_for_sale','item_price_per_unit'];
        return $scenarios;
    }


    /**
    * @inheritdoc
    */
    public function attributeLabels()
    {
        return [
            'item_id' => 'Item Name',
            'type_id' => 'Item Type',
            'vendor_id' => 'Vendor Name',
            'category_id' => 'Category Name',
            'item_name' => 'Item Name',
            'item_name_ar' => 'Item Name - Arabic',
            'item_description' => 'Item Description',
            'item_description_ar' => 'Item Description - Arabic',
            'item_additional_info' => 'Item Additional Info',
            'item_additional_info_ar' => 'Item Additional Info - Arabic',
            'item_amount_in_stock' => 'Item Number of Stock',
            'item_default_capacity' => 'Item Default Capacity',
            'item_price_per_unit' => 'Item Price per Unit',
            'item_customization_description' => 'Item Customization Description',
            'item_customization_description_ar' => 'Item Customization Description - Arabic',
            'item_price_description' => 'Item Price Description',
            'item_price_description_ar' => 'Item Price Description - Arabic',
            'item_for_sale' => 'Shop - Available for sale',
            'item_how_long_to_make' => 'No of days delivery',
            'item_minimum_quantity_to_order' => 'Item Minimum Quantity to Order',
            'item_approved' => 'Item Approved',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'created_datetime' => 'Created Datetime',
            'modified_datetime' => 'Modified Datetime',
            'trash' => 'Trash',
            'subcategory_id'=>'Sub category',
            'item_status'=> 'Display on website',
            'child_category'=>'Third Level Category',
        ];
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getCustomerCarts()
    {
        return $this->hasMany(CustomerCart::className(), ['item_id' => 'item_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getEventItemLinks()
    {
        return $this->hasMany(EventItemLink::className(), ['item_id' => 'item_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getFeatureGroupItems()
    {
        return $this->hasMany(FeatureGroupItem::className(), ['item_id' => 'item_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getSuborderItemPurchases()
    {
        return $this->hasMany(SuborderItemPurchase::className(), ['item_id' => 'item_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getType()
    {
        return $this->hasOne(Itemtype::className(), ['type_id' => 'type_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getVendor()
    {
        return $this->hasOne(Vendor::className(), ['vendor_id' => 'vendor_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getImage()
    {
        //return $this->hasOne(Image::className(), ['item_id' => 'item_id'])->orderBy(['vendorimage_sort_order'=>SORT_ASC]);
        return $this->hasOne(Image::className(), ['item_id' => 'item_id'])->where(['module_type'=>'vendor_item'])->orderBy(['vendorimage_sort_order'=>SORT_ASC]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImages()
    {
        //return $this->hasMany(Image::className(), ['item_id' => 'item_id'])->orderBy(['vendorimage_sort_order'=>SORT_ASC]);
        return $this->hasMany(Image::className(), ['item_id' => 'item_id'])->orderBy(['vendorimage_sort_order'=>SORT_ASC]);
    }


    /**
    * @return \yii\db\ActiveQuery
    */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['category_id' => 'category_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getVendorItemCapacityExceptions()
    {
        return $this->hasMany(VendorItemCapacityException::className(), ['item_id' => 'item_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getVendorItemImages()
    {
        return $this->hasMany(VendorItemImage::className(), ['item_id' => 'item_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getVendorItemPricings()
    {
        return $this->hasMany(VendorItemPricing::className(), ['item_id' => 'item_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getVendorItemQuestions()
    {
        return $this->hasMany(VendorItemQuestion::className(), ['item_id' => 'item_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getVendorItemRequests()
    {
        return $this->hasMany(VendorItemRequest::className(), ['item_id' => 'item_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getVendorItemThemes()
    {
        return $this->hasMany(Vendoritemthemes::className(), ['item_id' => 'item_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */

//    public function getThemes()
//    {
//        //return $this->hasMany(Theme::className(), ['theme_id' => 'theme_id'])->viaTable('whitebook_vendor_item_theme', ['item_id' => 'item_id']);
//        return $this->hasMany(Vendoritemthemes::className(), ['item_id' => 'item_id']);
//    }

    public static function vendoritemcount($vid='')
    {
        if(!isset($vid) && $vid =='') {
            $vid=Vendor::getVendor('vendor_id');
        }
        return $s = Vendoritem::find()
        ->where(['trash' => 'Default'])
        ->where(['vendor_id' => $vid])
        ->count();
    }


    public static function getCategoryName($id)
    {
        if($id){
            $model = Category::find()->where(['category_id'=>$id])->one();
            return $model->category_name;}
            else
            {return null;}
        }


        public static function getItemType($id)
        {
            $model = Itemtype::find()->where(['type_id'=>$id])->one();
            return $model->type_name;
        }



        public static function loadvendoritem()
        {
            $item= Vendoritem::find()
            ->where(['trash' =>'Default','item_for_sale' =>'Yes'])
            ->all();
            $item=ArrayHelper::map($item,'item_id','item_name');
            return $item;
        }


        public static function vendoritemname($id)
        {
            $item= Vendoritem::find()
            ->select(['item_name'])
            ->where(['=', 'item_id',$id])
            ->andwhere(['trash' =>'Default'])
            ->one();
            return $item['item_name'];
        }

        public static function groupvendoritem($categoryid,$subcategory)
        {
            $vendor_item= Vendoritem::find()
            ->where(['=', 'category_id', $categoryid])
            ->andwhere(['=', 'subcategory_id',$subcategory])
            ->all();
            $vendor_item1=ArrayHelper::map($vendor_item,'item_id','item_name');
            return $vendor_item1;
        }


        /* Load vendor items for vendor capacity exception dates*/
        /* backend */
        public static function loaditems()
        {
            $item= Vendoritem::find()
            ->where(['!=', 'item_status', 'Deactive'])
            ->andwhere(['!=', 'trash', 'Deleted'])
            ->andwhere(['vendor_id'=> Vendor::getVendor('vendor_id')])
            ->all();
            $items=ArrayHelper::map($item,'item_id','item_name');
            return $items;
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

        /**
        * Deletes uploaded files related to this model from S3
        */
        public static function deleteFiles($image_key){
            Yii::$app->resourceManager->delete(self::UPLOADFOLDER_210. $image_key);
            Yii::$app->resourceManager->delete(self::UPLOADFOLDER_530. $image_key);
            Yii::$app->resourceManager->delete(self::UPLOADFOLDER_1000. $image_key);
        }

        public static function get_featured_product() {
          return $feature = \frontend\models\Vendoritem::find()
                      ->select(['{{%vendor_item}}.*'])
                      ->where(['item_status' => 'Active'])
                      ->with('vendor')
                      ->asArray()
                      ->all();
            }
}
