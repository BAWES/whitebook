<?php
namespace common\models;

use Yii;
use yii\db\Expression;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\helpers\Url;
use common\models\Vendor;
use common\models\VendorDraftItem;
use common\models\VendorItemToPackage;
use common\models\VendorItemVideo;

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
* @property integer $item_default_capacity
* @property integer $included_quantity
* @property string $item_price_per_unit
* @property string $item_base_price
* @property integer $item_how_long_to_make
* @property integer $item_minimum_quantity_to_order
* @property string $item_archived
* @property string $item_approved
* @property integer $version
* @property integer $hide_from_admin
* @property integer $created_by
* @property integer $modified_by
* @property string $created_datetime
* @property string $modified_datetime
* @property string $trash
* @property string $slug
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
class VendorItem extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = "Active";
    const STATUS_DEACTIVE = "Deactive";
    const UPLOADFOLDER_210 = "vendor_item_images_210/";
    const UPLOADFOLDER_530 = "vendor_item_images_530/";
    const UPLOADFOLDER_1000 = "vendor_item_images_1000/";
    const UPLOADSALESGUIDE = "sales_guide_images/";
    const UPLOADFOLDER_MENUITEM = "vendor_menu_item/";
    const UPLOADFOLDER_MENUITEM_THUMBNAIL = "vendor_menu_item/thumbnail/";

    public $themes;
    public $groups;
    public $packages;
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
                'immutable' => false,
                'ensureUnique' => true,
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
            [['type_id', 'vendor_id', 'item_name', 'item_name_ar'], 'required'],
            
            [['minimum_increment', 'hide_price_chart', 'type_id', 'vendor_id', 'item_default_capacity', 'item_how_long_to_make', 'item_minimum_quantity_to_order', 'created_by', 'modified_by'], 'integer'],
            
            [['notice_period_type', 'item_description','item_description_ar','item_additional_info','item_additional_info_ar', 'item_approved', 'trash', 'quantity_label','slug'], 'string'],
            
            [['item_price_per_unit', 'min_order_amount','item_base_price','included_quantity'], 'number'],
            
            [['created_datetime', 'modified_datetime','item_status','image_path', 'allow_special_request', 'have_female_service'], 'safe'],

            [['item_name', 'item_name_ar'], 'string', 'max' => 128],

            [['set_up_time', 'set_up_time_ar', 'max_time', 'max_time_ar', 'requirements','requirements_ar'], 'string', 'max' => 256],

            [['image_path'],'image', 'extensions' => 'png,jpg,jpeg','maxFiles'=>20],

            // set scenario for vendor item add functionality
            [['type_id', 'hide_price_chart', 'notice_period_type', 'item_description','item_description_ar', 'item_default_capacity', 'item_how_long_to_make', 'item_minimum_quantity_to_order','included_quantity','item_name', 'item_name_ar', 'item_price_per_unit'], 'required', 'on'=>'VendorItemAdd'],
        ];
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
            'item_default_capacity' => 'Maximum quantity ordered per day',
            'item_price_per_unit' => 'Increment Price',
            'item_base_price' => 'Base Price',
            'item_how_long_to_make' => 'Notice Period',
            'notice_period_type' => 'Notice Period Type',
            'hide_price_chart' => 'Hide price chart from customer',
            'item_minimum_quantity_to_order' => 'Minimum Quantity To Order',
            'item_approved' => 'Item Approved',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'created_datetime' => 'Created Datetime',
            'modified_datetime' => 'Modified Datetime',
            'trash' => 'Trash',
            'subcategory_id'=>'Sub category',
            'item_status'=> 'Display on website',
            'child_category'=>'Third Level Category',
            'set_up_time' => 'Setup time',
            'set_up_time_ar' => 'Setup time - Arabic',
            'max_time' => 'Duration',
            'max_time_ar' => 'Duration - Arabic',
            'requirements' => 'Requirements',
            'requirements_ar' => 'Requirements - Arabic',
            'whats_include' => 'What\'s include?', 
            'whats_include_ar' => 'What\'s include? - Arabic', 
            'min_order_amount' => 'Min. Order KD',
            'slug' => 'slug',
            'included_quantity' => 'Included Quantity'
        ];
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getDraftItem()
    {
        return $this->hasOne(VendorDraftItem::className(), ['item_id' => 'item_id']);
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
    public function getVendorItemToPackage()
    {
        return $this->hasMany(VendorItemToPackage::className(), ['item_id' => 'item_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getType()
    {
        return $this->hasOne(ItemType::className(), ['type_id' => 'type_id']);
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
    public function getVideos()
    {
        return $this->hasMany(VendorItemVideo::className(), ['item_id' => 'item_id'])->orderBy(['video_sort_order' => SORT_ASC]);
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
    public function getFeatureGroupItems()
    {
        return $this->hasMany(FeatureGroupItem::className(), ['item_id' => 'item_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getVendorItemThemes()
    {
        return $this->hasMany(VendorItemThemes::className(), ['item_id' => 'item_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getItemQuestions()
    {
        return $this->hasMany(VendorItemQuestion::className(), ['item_id' => 'item_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getMenuItems()
    {
        return $this->hasMany(VendorItemMenuItem::className(), ['item_id' => 'item_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getMenus()
    {
        return $this->hasMany(VendorItemMenu::className(), ['item_id' => 'item_id']);
    }

    public static function vendoritemcount($vid = '')
    {
        if(!$vid) {
            $vid = Vendor::getVendor('vendor_id');
        }

        return VendorItem::find()
            ->where(['trash' => 'Default', 'vendor_id' => $vid])
            ->count();
    }

    public static function getCategoryName($id)
    {
        $model = Category::find()->where(['category_id'=>$id])->one();
        
        if($model){
            return $model->category_name;
        }
    }

    public static function getItemType($id)
    {
        $model = ItemType::find()->where(['type_id'=>$id])->one();
        
        if($model) {                
            return $model->type_name;   
        }
    }

    public static function loadvendoritem()
    {
        $item= VendorItem::find()
            ->where(['trash' =>'Default'])
            ->all();
        
        return ArrayHelper::map($item,'item_id','item_name');
    }

    public static function vendoritemname($id)
    {
        $item = VendorItem::find()
            ->select(['item_name'])
            ->where(['=', 'item_id',$id])
            ->andwhere(['trash' =>'Default'])
            ->one();

        return $item['item_name'];
    }

    public static function groupvendoritem($categoryid, $subcategory)
    {
        $vendor_item = VendorItem::find()
            ->where(['=', 'category_id', $categoryid])
            ->andwhere(['=', 'subcategory_id',$subcategory])
            ->all();

        $vendor_item1 = ArrayHelper::map($vendor_item,'item_id','item_name');
        return $vendor_item1;
    }


    /* Load vendor items for vendor capacity exception dates*/
    /* backend */
    public static function loaditems()
    {
        $item= VendorItem::find()
            ->where(['!=', 'item_status', 'Deactive'])
            ->andwhere(['!=', 'trash', 'Deleted'])
            ->andwhere(['vendor_id'=> Vendor::getVendor('vendor_id')])
            ->all();
            
        $items = ArrayHelper::map($item,'item_id','item_name');
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
        return true;
    }

    public static function get_featured_product() {
        return $feature = \frontend\models\VendorItem::find()->select(['{{%vendor_item}}.*'])->where(['item_status' => 'Active'])->with('vendor')->asArray()->all();
    }

    /**
     * sanitise data for item before save 
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            
            // remove inline css 

            $this->item_description = str_replace('style="', 'inline-style-not-allowed="', $this->item_description);
            
            $this->item_description_ar = str_replace('style="', 'inline-style-not-allowed="', $this->item_description_ar);
            
            $this->item_additional_info = str_replace('style="', 'inline-style-not-allowed="', $this->item_additional_info);
            
            $this->item_additional_info_ar = str_replace('style="', 'inline-style-not-allowed="', $this->item_additional_info_ar);
                        
            return true;
        } else {
            return false;
        }
    }

    public function getSoldItems($item, $date) {

        $purchased_result = \common\models\Booking::totalPurchasedItem($item->item_id,$date);

        return (int)$purchased_result['purchased'];
    }

    public function getItemInStock($item, $date) {

        $capacity_exception = VendorItemCapacityException::findOne([
            'item_id' => $item->item_id,
            'exception_date' => date('Y-m-d', strtotime($date))
        ]);

        if($capacity_exception && $capacity_exception->exception_capacity) {
            $capacity = $capacity_exception->exception_capacity;
        } else {
            $capacity = $item->item_default_capacity;
        }
        //2) get no of item purchased for selected date

        $purchased_result = \common\models\Booking::totalPurchasedItem($item->item_id,$date);

        if($purchased_result) {
            $purchased = $purchased_result['purchased'];
        } else {
            $purchased = 0;
        }

        return $capacity - $purchased_result['purchased'];
    }

    /** 
     * Notify admin on item update 
     * @param $item_id 
     */
    public static function notifyAdmin($id)
    {
        Yii::$app->mailer->htmlLayout = 'layouts/empty';
        
        Yii::$app->mailer->compose("admin/item-updated",
            [
                "model" => self::findOne($id),
                "image_1" => Url::to("@web/twb-logo-trans.png", true),
                "image_2" => Url::to("@web/twb-logo-horiz-white.png", true)
            ])
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->params['SITE_NAME']])
            ->setTo(Yii::$app->params['adminEmail'])
            ->setSubject('Vendor Updated Item #'.$id)
            ->send();
    }

    public static function itemFinalPrice($item_id,$quantity,$menu_items = [])
    {
        $item = self::findOne($item_id);

        if (empty($item)) {
            throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
        }

        $total = ($item->item_base_price) ? $item->item_base_price : 0;

        $price_chart = \common\models\VendorItemPricing::find()
            ->where(['item_id' => $item['item_id'], 'trash' => 'Default'])
            ->andWhere(['<=', 'range_from', $quantity])
            ->andWhere(['>=', 'range_to', $quantity])
            ->orderBy('pricing_price_per_unit DESC')
            ->one();

        if ($item->included_quantity > 0) {
            $included_quantity = $item->included_quantity;
        } else {
            $included_quantity = 1;
        }

        if ($price_chart) {
            $increment_value = $price_chart->pricing_price_per_unit;
        } else {
            $increment_value = $item->item_price_per_unit;
        }

        if($item->minimum_increment < 1)
        {
            $item->minimum_increment = 1;
        }

        // by this price will get increase by "price per increment" for each "min increment" qty added to cart  

        $total += (($quantity - $included_quantity) / $item->minimum_increment) * $increment_value;

        if(!is_array($menu_items)) {
            $menu_items = [];
        }

        // menu item can be manu_item => quantity pair or array of menu items 

        foreach ($menu_items as $key => $value) 
        {
            if(empty($value))
                continue;

            if(is_array($value))
            {
                $total += $value['price'] * $value['quantity'];
            }
            else
            {
                $menu_item = VendorItemMenuItem::findOne($key);

                $total += $menu_item->price * $value;
            }
        }

        return $total;
    }

    public function deleteAllFiles() {
        //item images 
        if (isset($this->images) && count($this->images)>0) {
            foreach ($this->images as $img) {
                Yii::$app->resourceManager->delete(self::UPLOADFOLDER_210. $img->image_path);
                Yii::$app->resourceManager->delete(self::UPLOADFOLDER_530. $img->image_path);
                Yii::$app->resourceManager->delete(self::UPLOADFOLDER_1000. $img->image_path);
            }
        }
        //menu images 
        foreach ($this->menuItems as $menuItem) {
            Yii::$app->resourceManager->delete(self::UPLOADFOLDER_MENUITEM_THUMBNAIL . $menuItem->image);
            Yii::$app->resourceManager->delete(self::UPLOADFOLDER_MENUITEM . $menuItem->image);            
        }
    }

    public static function clear($model)
    {
        $model->deleteAllFiles();
        VendorItemCapacityException::deleteAll(['item_id' => $model->item_id]);
        Image::deleteAll(['item_id' => $model->item_id]);
        VendorItemPricing::deleteAll(['item_id' => $model->item_id]);
        VendorItemThemes::deleteAll(['item_id' => $model->item_id]);
        VendorItemToCategory::deleteAll(['item_id' => $model->item_id]);
        CustomerCart::deleteAll(['item_id' => $model->item_id]);
        PriorityItem::deleteAll(['item_id' => $model->item_id]);
        EventItemlink::deleteAll(['item_id' => $model->item_id]);
        FeatureGroupItem::deleteAll(['item_id' => $model->item_id]);
        VendorItemQuestion::deleteAll(['item_id' => $model->item_id]);
        //menu 

        $menues = VendorItemMenu::findAll(['item_id' => $model->item_id]);

        foreach ($menues as $key => $menu) {
            VendorItemMenuItem::deleteAll(['menu_id' => $menu->menu_id]);
        }

        VendorItemMenu::deleteAll(['item_id' => $model->item_id]);

        $model->delete();
    }

    public static function more_from_vendor($model, $limit = 4) 
    {        
        $result = VendorItem::find()
            ->where([
                'vendor_id' => $model->vendor_id,
                'item_status' => 'Active',
                'item_approved' => 'Yes',
                'trash' => 'Default'
            ])
            ->andWhere(['!=','item_id', $model->item_id])
            ->limit(4)
            ->asArray()
            ->all();    

        $products = [];
            
        foreach ($result as $item) 
        {
            $image = \common\models\Image::find()
                ->where(['item_id' => $item['item_id']])
                ->orderBy(['vendorimage_sort_order' => SORT_ASC])
                ->one();
                
            if ($image) {
                $item['image'] = $image->image_path;
            } else {
                $item['image'] = '';
            }

            // for notice period
            $value = $item;
            $notice = '';
            if (isset($value['item_how_long_to_make']) && $value['item_how_long_to_make'] > 0) {
                if (isset($value['notice_period_type']) && $value['notice_period_type'] == 'Day') {
                    if ($value['item_how_long_to_make'] >= 7) {
                        $notice = Yii::t('api', '{count} week(s)', [
                            'count' => substr(($value['item_how_long_to_make'] / 7), 0, 3)
                        ]);
                    } else {
                        $notice = Yii::t('api', '{count} day(s)', [
                            'count' => $value['item_how_long_to_make']
                        ]);
                    }
                } else {
                    if ($value['item_how_long_to_make'] >= 24) {
                        $notice = Yii::t('api', '{count} day(s)', [
                            'count' => substr(($value['item_how_long_to_make'] / 24), 0, 3)
                        ]);
                    } else {
                        $notice = Yii::t('api', '{count} hours', [
                            'count' => $value['item_how_long_to_make']
                        ]);
                    }
                }
            }
            $item['notice'] = $notice;

            $products[] = $item;
        }

        return $products;
    }

    /**
     * @inheritdoc
     * @return query\VendorItemQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new query\VendorItemQuery(get_called_class());
    }
}
