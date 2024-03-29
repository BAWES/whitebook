<?php

namespace common\models;

use Yii;
use yii\db\Expression;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use common\models\VendorDraftItemVideo;
use common\models\VendorDraftItemThemes;
use common\models\VendorDraftItemMenuItem;
use common\models\VendorDraftItemMenu;

/**
 * This is the model class for table "whitebook_vendor_draft_item".
 *
 * @property integer $draft_item_id
 * @property string $item_id
 * @property string $type_id
 * @property string $vendor_id
 * @property string $item_name
 * @property string $item_name_ar
 * @property string $priority
 * @property string $item_description
 * @property string $item_description_ar
 * @property string $item_additional_info
 * @property string $item_additional_info_ar
 * @property integer $item_default_capacity
 * @property integer $included_quantity
 * @property string $item_price_per_unit
 * @property string $item_customization_description
 * @property string $item_customization_description_ar
 * @property string $item_base_price
 * @property integer $sort
 * @property integer $item_how_long_to_make
 * @property integer $item_minimum_quantity_to_order
 * @property string $item_archived
 * @property string $item_approved
 * @property string $item_status
 * @property integer $created_by
 * @property integer $modified_by
 * @property string $created_datetime
 * @property string $modified_datetime
 * @property string $trash
 * @property string $slug
 *
 * @property Vendor $vendor
 * @property ItemType $type
 */
class VendorDraftItem extends \yii\db\ActiveRecord
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
        return 'whitebook_vendor_draft_item';
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
            [['item_id', 'item_description', 'sort', 'created_by', 'modified_by', 'created_datetime', 'modified_datetime'], 'required'],

            [['minimum_increment', 'hide_price_chart', 'item_id', 'type_id', 'vendor_id', 'item_default_capacity', 'sort', 'item_how_long_to_make', 'item_minimum_quantity_to_order', 'created_by', 'modified_by', 'is_ready','included_quantity'], 'integer'],
            
            [['notice_period_type', 'item_name_ar', 'priority', 'item_description', 'item_description_ar', 'item_additional_info', 'item_additional_info_ar', 'item_customization_description', 'item_customization_description_ar', 'item_archived', 'item_approved', 'item_status', 'trash', 'set_up_time', 'max_time', 'requirements', 'requirements_ar', 'max_time_ar', 'set_up_time_ar'], 'string'],

            [['item_price_per_unit', 'item_base_price', 'item_amount_in_stock', 'have_female_service', 'allow_special_request', 'min_order_amount'], 'number'],
            
            [['created_datetime', 'modified_datetime'], 'safe'],
            
            [['item_name', 'quantity_label'], 'string', 'max' => 128],
            
            [['slug'], 'string', 'max' => 255],

            [['vendor_id'], 'exist', 'skipOnError' => true, 'targetClass' => Vendor::className(), 'targetAttribute' => ['vendor_id' => 'vendor_id']],
            
            [['type_id'], 'exist', 'skipOnError' => true, 'targetClass' => ItemType::className(), 'targetAttribute' => ['type_id' => 'type_id']],


            //MenuItems

            [['allow_special_request', 'have_female_service'], 'number', 'on' => ['MenuItems']],

            //ItemPrice

            [['quantity_label', 'item_customization_description', 'item_customization_description_ar','included_quantity'], 'string', 'on' => ['ItemPrice']],

            [['item_default_capacity', 'item_minimum_quantity_to_order'], 'integer', 'on' => ['ItemPrice']],

            [['min_order_amount', 'item_price_per_unit','item_base_price'], 'number', 'on' => ['ItemPrice']],

            [['type_id', 'minimum_increment'], 'integer', 'on' => ['ItemPrice']],

            [['type_id','item_price_per_unit'], 'required', 'on' => ['ItemPrice']],

            //ItemDescription

            [['set_up_time', 'set_up_time_ar', 'max_time', 'max_time_ar', 'requirements','requirements_ar', 'item_how_long_to_make', 'notice_period_type', 'item_description', 'item_description_ar', 'item_additional_info', 'item_additional_info_ar'], 'string', 'on' => ['ItemDescription']],

            //ItemInfo

            [['item_name', 'item_name_ar'], 'required', 'on' => ['ItemInfo']]
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();

        $scenarios['MenuItems'] = ['allow_special_request', 'have_female_service'];

        $scenarios['ItemPrice'] = ['type_id','minimum_increment', 'min_order_amount', 'quantity_label', 'item_default_capacity', 'item_minimum_quantity_to_order', 'item_price_per_unit', 'item_base_price', 'item_customization_description', 'item_customization_description_ar','included_quantity'];

        $scenarios['ItemDescription'] = ['set_up_time', 'set_up_time_ar', 'max_time', 'max_time_ar', 'requirements', 'requirements_ar', 'item_how_long_to_make', 'notice_period_type', 'item_description', 'item_description_ar', 'item_additional_info', 'item_additional_info_ar'];

        $scenarios['ItemInfo'] = ['item_name', 'item_name_ar', 'item_status'];

        return $scenarios;
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'draft_item_id' => Yii::t('app', 'Draft Item ID'),
            'item_id' => Yii::t('app', 'Item ID'),
            'type_id' => Yii::t('app', 'Type ID'),
            'vendor_id' => Yii::t('app', 'Vendor ID'),
            'item_name' => Yii::t('app', 'Item Name'),
            'item_name_ar' => Yii::t('app', 'Item Name - Arabic'),
            'priority' => Yii::t('app', 'Priority'),
            'item_description' => Yii::t('app', 'Item Description'),
            'item_description_ar' => Yii::t('app', 'Item Description - Arabic'),
            'item_additional_info' => Yii::t('app', 'Item Additional Info'),
            'item_additional_info_ar' => Yii::t('app', 'Item Additional Info - Arabic'),
            'item_default_capacity' => Yii::t('app', 'Item Default Capacity'),
            'item_price_per_unit' => Yii::t('app', 'Increment Price'),
            'item_customization_description' => Yii::t('app', 'Item Customization Description'),
            'item_customization_description_ar' => Yii::t('app', 'Item Customization Description - Arabic'),
            'sort' => Yii::t('app', 'Sort'),
            'item_minimum_quantity_to_order' => Yii::t('app', 'Minimum Quantity To Order'),
            'item_archived' => Yii::t('app', 'Item Archived'),
            'item_approved' => Yii::t('app', 'Item Approved'),
            'item_status' => Yii::t('app', 'Item Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'modified_by' => Yii::t('app', 'Modified By'),
            'created_datetime' => Yii::t('app', 'Created Datetime'),
            'modified_datetime' => Yii::t('app', 'Modified Datetime'),
            'trash' => Yii::t('app', 'Trash'),
            'slug' => Yii::t('app', 'Slug'),
            'notice_period_type' => 'Notice Period Type',
            'hide_price_chart' => 'Hide price chart from customer',
            'max_time' => Yii::t('app', 'Duration'),
            'max_time_ar' => Yii::t('app', 'Duration - Arabic'),
            'set_up_time' => Yii::t('app', 'Setup Time'),
            'set_up_time_ar' => Yii::t('app', 'Setup Time - Arabic'),
            'requirements' => Yii::t('app', 'Requirements'),
            'requirements_ar' => Yii::t('app', 'Requirements - Arabic'),
            'min_order_amount' => Yii::t('app', 'Min. Order KD'),
            'included_quantity' => Yii::t('app', 'Included Quantity')
        ];
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
    public function getType()
    {
        return $this->hasOne(ItemType::className(), ['type_id' => 'type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVideos()
    {
        return $this->hasMany(VendorDraftItemVideo::className(), ['item_id' => 'item_id'])->orderBy(['video_sort_order' => SORT_ASC]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImages()
    {
        return $this->hasMany(Image::className(), ['item_id' => 'item_id'])->orderBy(['vendorimage_sort_order'=>SORT_ASC]);
    }
    
    /**
    * @return \yii\db\ActiveQuery
    */
    public function getVendorItemThemes()
    {
        return $this->hasMany(VendorDraftItemThemes::className(), ['item_id' => 'item_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getMenuItems()
    {
        return $this->hasMany(VendorDraftItemMenuItem::className(), ['item_id' => 'item_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getMenus()
    {
        return $this->hasMany(VendorDraftItemMenu::className(), ['item_id' => 'item_id']);
    }

    public function getThemeName() {

        $string = [];
        
        foreach ($this->vendorItemThemes as $theme) {
              $string[] = ucfirst($theme->themeDetail->theme_name);
        }
        
        return implode(', ',$string);
    }

    public function is_price_table_changed($item_id)
    {
        $item_pricing = VendorItemPricing::findAll(['item_id' => $item_id]);
        $draft_pricing = VendorDraftItemPricing::findAll(['item_id' => $item_id]);
        
        //check item item deleted in draft 

        foreach ($item_pricing as $key => $value) {
            
            $a = VendorDraftItemPricing::find()
                ->where([
                        'range_from' => $value->range_from,
                        'range_to' => $value->range_to,
                        'pricing_price_per_unit' => $value->pricing_price_per_unit,
                    ])
                ->count();

            if(!$a)
                return true;
        }

        //check item item added in draft 

        foreach ($draft_pricing as $key => $value) {
            
            $a = VendorItemPricing::find()
                ->where([
                        'range_from' => $value->range_from,
                        'range_to' => $value->range_to,
                        'pricing_price_per_unit' => $value->pricing_price_per_unit,
                    ])
                ->count();

            if(!$a)
                return true;
        }
    }
    
    public function is_images_changed($item_id)
    {
        $item_images = Image::findAll(['item_id' => $item_id]);
        $draft_images = VendorDraftImage::findAll(['item_id' => $item_id]);
        
        //check item item deleted in draft 

        foreach ($item_images as $key => $value) {
            
            $a = VendorDraftImage::find()
                ->where([
                        'image_path' => $value->image_path,
                        'vendorimage_sort_order' => $value->vendorimage_sort_order
                    ])
                ->count();

            if(!$a)
                return true;
        }

        //check item item added in draft 

        foreach ($draft_images as $key => $value) {
            
            $a = Image::find()
                ->where([
                        'image_path' => $value->image_path,
                        'vendorimage_sort_order' => $value->vendorimage_sort_order
                    ])
                ->count();

            if(!$a)
                return true;
        }
    }
    
    public function is_categories_changed($item_id)
    {
        $item_categories = VendorItemToCategory::findAll(['item_id' => $item_id]);
        $draft_categories = VendorDraftItemToCategory::findAll(['item_id' => $item_id]);
        
        //check item item deleted in draft 

        foreach ($item_categories as $key => $value) {
            
            $a = VendorDraftItemToCategory::find()
                ->where([
                        'category_id' => $value->category_id
                    ])
                ->count();

            if(!$a)
                return true;
        }

        //check item item added in draft 

        foreach ($draft_categories as $key => $value) {
            
            $a = VendorItemToCategory::find()
                ->where([
                        'category_id' => $value->category_id
                    ])
                ->count();

            if(!$a)
                return true;
        }
    }
    
    public function deleteAllFiles() {
        //item images 
        if (isset($this->images) && count($this->images)>0) {
            foreach ($this->images as $img) {
                Yii::$app->resourceManager->delete(VendorItem::UPLOADFOLDER_210. $img->image_path);
                Yii::$app->resourceManager->delete(VendorItem::UPLOADFOLDER_530. $img->image_path);
                Yii::$app->resourceManager->delete(VendorItem::UPLOADFOLDER_1000. $img->image_path);
            }
        }
        //menu images 
        foreach ($this->menuItems as $menuItem) {
            Yii::$app->resourceManager->delete(VendorItem::UPLOADFOLDER_MENUITEM_THUMBNAIL . $menuItem->image);
            Yii::$app->resourceManager->delete(VendorItem::UPLOADFOLDER_MENUITEM . $menuItem->image);            
        }
    }

    /**
     * Clear draft
     */
    public static function clear($model)
    {
        if(!$model)
            return true;

        //#Issue : Deleting images for live item 
        //$model->deleteAllFiles();

        $menues = VendorDraftItemMenu::findAll(['item_id' => $model->item_id]);

        foreach ($menues as $key => $menu) {
            VendorDraftItemMenuItem::deleteAll(['draft_menu_id' => $menu->draft_menu_id]);
        }

        VendorDraftItemMenu::deleteAll(['item_id' => $model->item_id]);
        
        //draft related 
        VendorDraftItemPricing::deleteAll(['item_id' => $model->item_id]);
        VendorDraftImage::deleteAll(['item_id' => $model->item_id]);
        VendorDraftItemToCategory::deleteAll(['item_id' => $model->item_id]);
        VendorDraftItemQuestion::deleteAll(['item_id' => $model->item_id]);
        VendorDraftItemToCategory::deleteAll(['item_id' => $model->item_id]);
        
        //draft 
        VendorDraftItem::deleteAll(['item_id' => $model->item_id]); 

        $model->delete();
    }

    /**
     * @inheritdoc
     * @return query\VendorDraftItemQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new query\VendorDraftItemQuery(get_called_class());
    }
}
