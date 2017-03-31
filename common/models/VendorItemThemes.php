<?php

namespace common\models;

use Yii;
//use yii\helpers\ArrayHelper;
//use yii\db\ActiveRecord;
//use yii\behaviors\SluggableBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use common\models\vendoritemthemesSearch;
use frontend\models\Themes as ItemTheme;

/**
* This is the model class for table "whitebook_vendor_item_theme".
*
* @property integer $id
* @property integer $item_id
* @property integer $theme_id
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
class VendorItemThemes extends \yii\db\ActiveRecord
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
        return $this->hasOne(VendorItem::className(), ['vendor_id' => 'vendor_id']);
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

    public function getThemeName($id)
    {
        $theme_name = ItemTheme::find()
            ->select('theme_name')
            ->where(['!=', 'theme_status', 'Deactive'])
            ->andwhere(['!=', 'trash', 'Deleted'])
            ->andwhere(['theme_id' => $id])
            ->one();

        return $theme_name['theme_name'];
    }

    /**
     * @inheritdoc
     * @return VendorItemThemesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\VendorItemThemesQuery(get_called_class());
    }
}
