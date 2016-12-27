<?php

namespace common\models;

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

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_id', 'item_description', 'item_additional_info', 'sort', 'created_by', 'modified_by', 'created_datetime', 'modified_datetime'], 'required'],

            [['item_id', 'type_id', 'vendor_id', 'item_amount_in_stock', 'item_default_capacity', 'sort', 'item_how_long_to_make', 'item_minimum_quantity_to_order', 'created_by', 'modified_by', 'is_ready'], 'integer'],
            [['item_name_ar', 'priority', 'item_description', 'item_description_ar', 'item_additional_info', 'item_additional_info_ar', 'item_customization_description', 'item_customization_description_ar', 'item_price_description', 'item_price_description_ar', 'item_for_sale', 'item_archived', 'item_approved', 'item_status', 'trash'], 'string'],

            [['item_price_per_unit'], 'number'],
            [['created_datetime', 'modified_datetime'], 'safe'],
            [['item_name'], 'string', 'max' => 128],
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
}
