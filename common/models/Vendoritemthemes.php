<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\db\ActiveRecord;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use common\models\Vendoritemthemes;
use common\models\vendoritemthemesSearch;
use frontend\models\Themes as ItemTheme;

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
class Vendoritemthemes extends \yii\db\ActiveRecord
{
    /**
    * @inheritdoc
    */
    public static function tableName()
    {
        return 'whitebook_vendor_item_theme';
    }

    public function behaviors()
    {
        return [
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
            [['item_id', 'theme_id',], 'required'],
        ];
    }


    /**
    * @return \yii\db\ActiveQuery
    */
    public function getVendoritem()
    {
        return $this->hasOne(Vendoritem::className(), ['vendor_id' => 'vendor_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getThemeDetail()
    {
        return $this->hasOne(ItemTheme::className(), ['theme_id' => 'theme_id']);
    }

    /**
    * @inheritdoc
    */
    public function attributeLabels()
    {
        return [
            'item_id' => 'Item Name ',
            'theme_id' => 'Theme Name',
            'category_id' => 'Category Name',
            'subcategory_id' => 'subcategory Name',
        ];
    }


    public static function getthemelist($t)
    {
        $themeid= Vendoritemthemes::find()
        ->select(['theme_id','id'])
        ->where(['=', 'item_id', $t])
        ->one();
        return $themeid=$themeid['theme_id'];
    }



    public static function getthemeid($t)
    {
        $id= Vendoritemthemes::find()
        ->select(['id'])
        ->where(['=', 'item_id', $t])
        ->one();
        return $id=$id['id'];
    }

    public static function themedetails($model)
    {
        $string = [];
        if (isset($model->vendorItemThemes) && count($model->vendorItemThemes)>0) {
            foreach ($model->vendorItemThemes as $theme) {
                $string[] = ucfirst($theme->themeDetail->theme_name);
            }
        }
        return implode(', ',$string);
    }


    public function getThemeName($id)
    {
        $theme_name= ItemTheme::find()
        ->select('theme_name')
        ->where(['!=', 'theme_status', 'Deactive'])
        ->andwhere(['!=', 'trash', 'Deleted'])
        ->andwhere(['theme_id' => $id])
        ->one();
        return $theme_name['theme_name'];
    }
    /**
    * @return \yii\db\ActiveQuery
    */

}
