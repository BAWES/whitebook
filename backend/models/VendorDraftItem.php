<?php

namespace backend\models;

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
 * @property integer $item_amount_in_stock
 * @property integer $item_default_capacity
 * @property string $item_price_per_unit
 * @property string $item_customization_description
 * @property string $item_customization_description_ar
 * @property string $item_price_description
 * @property string $item_price_description_ar
 * @property string $item_for_sale
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

    public function rules()
    {
        return [

            //ItemApproval

            [['item_approved'], 'required', 'on' => ['ItemApproval']],
           
            //MenuItems

            [['allow_special_request', 'have_female_service', 'min_order_amount'], 'number', 'on' => ['MenuItems']],

            [['quantity_label', 'set_up_time', 'set_up_time_ar', 'max_time', 'max_time_ar', 'requirements','requirements_ar'], 'string', 'max' => 256, 'on' => ['MenuItems']],

            //ItemPrice

            [['item_for_sale', 'item_price_description','item_price_description_ar', 'item_customization_description', 'item_customization_description_ar'], 'string', 'on' => ['ItemPrice']],
            
            [['item_amount_in_stock', 'item_default_capacity', 'item_how_long_to_make', 'item_minimum_quantity_to_order'], 'integer', 'on' => ['ItemPrice']],
            
            [['item_price_per_unit'], 'number', 'on' => ['ItemPrice']],

            //ItemDescription

            [['type_id'], 'integer', 'on' => ['ItemDescription']],
            
            [['item_description', 'item_description_ar', 'item_additional_info', 'item_additional_info_ar'], 'string', 'on' => ['ItemDescription']],

            //ItemInfo
            
            [['item_name', 'item_name_ar'], 'required', 'on' => ['ItemInfo']]    
        ];
    }

    public function scenarios()
    {
        return [
            'ItemApproval' => ['item_status, item_approved'],
            'MenuItems' => ['quantity_label', 'set_up_time', 'set_up_time_ar', 'max_time', 'max_time_ar', 'requirements', 'requirements_ar', 'min_order_amount', 'allow_special_request', 'have_female_service'],
            'ItemPrice' => ['item_for_sale', 'item_amount_in_stock', 'item_default_capacity', 'item_how_long_to_make', 'item_minimum_quantity_to_order', 'item_price_per_unit', 'item_price_description', 'item_price_description_ar', 'item_customization_description', 'item_customization_description_ar'],
            'ItemDescription' => ['type_id', 'item_description', 'item_description_ar', 'item_additional_info', 'item_additional_info_ar'],
            'ItemInfo' => ['item_name', 'item_name_ar', 'item_status']
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
            'item_name_ar' => Yii::t('app', 'Item Name Ar'),
            'priority' => Yii::t('app', 'Priority'),
            'item_description' => Yii::t('app', 'Item Description'),
            'item_description_ar' => Yii::t('app', 'Item Description Ar'),
            'item_additional_info' => Yii::t('app', 'Item Additional Info'),
            'item_additional_info_ar' => Yii::t('app', 'Item Additional Info Ar'),
            'item_amount_in_stock' => Yii::t('app', 'Item Amount In Stock'),
            'item_default_capacity' => Yii::t('app', 'Item Default Capacity'),
            'item_price_per_unit' => Yii::t('app', 'Item Price Per Unit'),
            'item_customization_description' => Yii::t('app', 'Item Customization Description'),
            'item_customization_description_ar' => Yii::t('app', 'Item Customization Description Ar'),
            'item_price_description' => Yii::t('app', 'Item Price Description'),
            'item_price_description_ar' => Yii::t('app', 'Item Price Description Ar'),
            'item_for_sale' => Yii::t('app', 'Item For Sale'),
            'sort' => Yii::t('app', 'Sort'),
            'item_how_long_to_make' => Yii::t('app', 'Item How Long To Make'),
            'item_minimum_quantity_to_order' => Yii::t('app', 'Item Minimum Quantity To Order'),
            'item_archived' => Yii::t('app', 'Item Archived'),
            'item_approved' => Yii::t('app', 'Item Approved'),
            'item_status' => Yii::t('app', 'Item Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'modified_by' => Yii::t('app', 'Modified By'),
            'created_datetime' => Yii::t('app', 'Created Datetime'),
            'modified_datetime' => Yii::t('app', 'Modified Datetime'),
            'trash' => Yii::t('app', 'Trash'),
            'slug' => Yii::t('app', 'Slug'),
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

    public function create_from_item($id) { 

        $model = VendorItem::findOne(['item_id' => $id, 'vendor_id'=>Yii::$app->user->getId()]);

        if (!$model) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $draft = new VendorDraftItem();
        $draft->attributes = $model->attributes;
        $draft->item_approved = 'Pending';
        $draft->save();

        //copy draft related data 

        $pricing = VendorItemPricing::loadpricevalues($model->item_id);

        foreach ($pricing as $key => $value) {
            $vdip = new VendorDraftItemPricing;
            $vdip->attributes = $value->attributes;
            $vdip->save();
        }
        
        $images = Image::findAll(['item_id' => $model->item_id]);

        foreach ($images as $key => $value) {
            $vdi = new VendorDraftImage;
            $vdi->attributes = $value->attributes;
            $vdi->save();
        }

        $categories = VendorItemToCategory::findAll(['item_id' => $model->item_id]);

        foreach ($categories as $key => $value) {
            $dic = new VendorDraftItemToCategory;
            $dic->attributes = $value->attributes;
            $dic->save();
        }

        //menu 
        $menues = VendorItemMenu::findAll(['item_id' => $model->item_id]);

        foreach ($menues as $key => $menu) {
            
            $dm = new VendorDraftItemMenu;
            $dm->attributes = $menu->attributes;
            $dm->save();

            $menu_items = VendorItemMenuItem::findAll(['menu_id' => $menu->menu_id]);

            foreach ($menu_items as $key => $menu_item) {
                $dmi = new VendorDraftItemMenuItem;
                $dmi->attributes = $menu_item->attributes;
                $dmi->draft_menu_id = $dm->draft_menu_id;
                $dmi->save();
            }
        }

        return $draft;
    }
}
