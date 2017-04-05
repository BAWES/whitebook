<?php

namespace common\models;

use yii\db\Expression;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;

use Yii;

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
 * @property string $item_price_per_unit
 * @property string $item_customization_description
 * @property string $item_customization_description_ar
 * @property string $item_price_description
 * @property string $item_price_description_ar
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
                'immutable' => true,
                'ensureUnique'=>true,
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

            [['minimum_increment', 'item_id', 'type_id', 'vendor_id', 'item_default_capacity', 'sort', 'item_how_long_to_make', 'item_minimum_quantity_to_order', 'created_by', 'modified_by', 'is_ready'], 'integer'],
            
            [['item_name_ar', 'priority', 'item_description', 'item_description_ar', 'item_additional_info', 'item_additional_info_ar', 'item_customization_description', 'item_customization_description_ar', 'item_price_description', 'item_price_description_ar', 'item_archived', 'item_approved', 'item_status', 'trash', 'set_up_time', 'max_time', 'requirements', 'requirements_ar', 'max_time_ar', 'set_up_time_ar'], 'string'],

            [['item_price_per_unit', 'item_base_price', 'item_amount_in_stock', 'have_female_service', 'allow_special_request', 'min_order_amount'], 'number'],
            
            [['created_datetime', 'modified_datetime'], 'safe'],
            
            [['item_name', 'quantity_label'], 'string', 'max' => 128],
            
            [['slug'], 'string', 'max' => 255],

            [['vendor_id'], 'exist', 'skipOnError' => true, 'targetClass' => Vendor::className(), 'targetAttribute' => ['vendor_id' => 'vendor_id']],
            
            [['type_id'], 'exist', 'skipOnError' => true, 'targetClass' => ItemType::className(), 'targetAttribute' => ['type_id' => 'type_id']],
        ];
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
            'item_price_description' => Yii::t('app', 'Price Description'),
            'item_price_description_ar' => Yii::t('app', 'Price Description - Arabic'),
            'sort' => Yii::t('app', 'Sort'),
            'item_minimum_quantity_to_order' => Yii::t('app', 'Included Quantity'),
            'item_archived' => Yii::t('app', 'Item Archived'),
            'item_approved' => Yii::t('app', 'Item Approved'),
            'item_status' => Yii::t('app', 'Item Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'modified_by' => Yii::t('app', 'Modified By'),
            'created_datetime' => Yii::t('app', 'Created Datetime'),
            'modified_datetime' => Yii::t('app', 'Modified Datetime'),
            'trash' => Yii::t('app', 'Trash'),
            'slug' => Yii::t('app', 'Slug'),
            'max_time' => Yii::t('app', 'Duration'),
            'max_time_ar' => Yii::t('app', 'Duration - Arabic'),
            'set_up_time' => Yii::t('app', 'Setup Time'),
            'set_up_time_ar' => Yii::t('app', 'Setup Time - Arabic'),
            'requirements' => Yii::t('app', 'Requirements'),
            'requirements_ar' => Yii::t('app', 'Requirements - Arabic'),
            'min_order_amount' => Yii::t('app', 'Min. Order KD')
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
    public function getImages()
    {
        return $this->hasMany(Image::className(), ['item_id' => 'item_id'])->orderBy(['vendorimage_sort_order'=>SORT_ASC]);
    }
    
    /**
    * @return \yii\db\ActiveQuery
    */
    public function getVendorItemThemes()
    {
        return $this->hasMany(VendorItemThemes::className(), ['item_id' => 'item_id']);
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
}
