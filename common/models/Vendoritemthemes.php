<?php

namespace common\models;
use common\models\Vendoritemthemes;
use common\models\vendoritemthemesSearch;
use Yii;
use yii\helpers\ArrayHelper;
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
	
		public static function themedetails($t)
	{
			$id= Vendoritemthemes::find()
			->select(['theme_id'])
			->where(['=', 'item_id', $t])
			->one();
			 $id=$id['theme_id']; 
			//print_r ($id);die;
			 $k=explode(',',$id);
			 
			 foreach ($k as $key=>$value)
			 {
			 $theme_name[]= Themes::find()
			->select('theme_name')
			->where(['!=', 'theme_status', 'Deactive'])
			->andwhere(['!=', 'trash', 'Deleted'])
			->andwhere(['theme_id' => $value])
			->one();
			}
			
			$i=0;
			 foreach ($theme_name as $key=>$value)
			 {
				 $themelist[]=$theme_name[$i]['theme_name'];
				 $i++;
			 }
			 return implode(", ",$themelist);
	}
	
	
		public function getThemeName($id)
	{        
			$theme_name= Themes::find()
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
