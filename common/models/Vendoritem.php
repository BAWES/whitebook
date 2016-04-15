<?php
namespace common\models;

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
class Vendoritem extends \yii\db\ActiveRecord
{
    const UPLOADFOLDER = "vendor_item_images_210/";
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

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type_id', 'vendor_id', 'category_id', 'item_name','subcategory_id',
             'child_category'], 'required'], 
            [['type_id', 'vendor_id', 'category_id','subcategory_id', 'item_amount_in_stock', 'item_default_capacity', 'item_how_long_to_make', 'item_minimum_quantity_to_order','child_category', 'created_by', 'modified_by'], 'integer'],
            [['item_description', 'item_additional_info', 'item_customization_description', 'item_price_description', 'item_for_sale', 'item_approved', 'trash'], 'string'],
            [['item_price_per_unit'], 'number'],
            [['created_datetime', 'modified_datetime','item_status','image_path'], 'safe'],
            [['item_name'], 'string', 'max' => 128],
            ['image_path','image', 'extensions' => 'png,jpg,jpeg', 'skipOnEmpty' => false],
            
            // set scenario for vendor item add functionality
            [['type_id', 'category_id',  'item_description', 'item_additional_info', 'item_amount_in_stock',
             'item_default_capacity', 'item_customization_description', 'item_price_description', 'item_how_long_to_make',
             'item_minimum_quantity_to_order','item_name','subcategory_id',
             'item_for_sale','item_price_per_unit'], 'required', 'on'=>'VendorItemAdd'], 
        ];
    }
    
    public function scenarios()
    {
		$scenarios = parent::scenarios();
		$scenarios['VendorItemAdd'] = ['type_id', 'category_id',  'item_description', 'item_additional_info', 'item_amount_in_stock',
             'item_default_capacity', 'item_customization_description', 'item_price_description', 'item_how_long_to_make',
             'item_minimum_quantity_to_order','item_name','subcategory_id','child_category',
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
            'item_description' => 'Item Description',
            'item_additional_info' => 'Item Additional Info',
            'item_amount_in_stock' => 'Item Number of Stock',
            'item_default_capacity' => 'Item Default Capacity',
            'item_price_per_unit' => 'Item Price per Unit',
            'item_customization_description' => 'Item Customization Description',
            'item_price_description' => 'Item Price Description',
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
        return $this->hasMany(VendorItemTheme::className(), ['item_id' => 'item_id']);
    }
    public static function getCategoryName($id)
    {		
		if($id){
		$model = Category::find()->where(['category_id'=>$id])->one();
        return $model->category_name;}
        else
        {return null;}
    }
    
        public static function getVendorName($id)
    {		
		$model = Vendor::find()->where(['vendor_id'=>$id])->one();
        return $model->vendor_name;
    }
            public static function getItemType($id)
    {		
		$model = Itemtype::find()->where(['type_id'=>$id])->one();
        return $model->type_name;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getThemes()
    {
        return $this->hasMany(Theme::className(), ['theme_id' => 'theme_id'])->viaTable('whitebook_vendor_item_theme', ['item_id' => 'item_id']);
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
	    public static function vendoritem_search_data($name)
	{       
			$item= Vendoritem::find()
			->select(['item_id','item_name','slug'])
			->where(['like', 'item_name',$name])
			->orwhere(['like', 'item_description',$name])
			->andwhere(['trash' =>'Default','item_for_sale' =>'Yes','item_status'=>'Active'])
			->all();
			return $item; 
	}
	    public static function vendoritem_search_details($name)
	{       /* 	SELECT `item_name`,`whitebook_vendor_item`.`slug` FROM `whitebook_vendor_item` LEFT JOIN `whitebook_category` ON `whitebook_vendor_item`.`category_id` = `whitebook_category`.`category_id` WHERE ((`item_name` LIKE '%food%') OR (`item_description` LIKE '%food%') AND (`whitebook_vendor_item`.`trash`='Default') AND (`whitebook_category`.`trash`='Default') AND (`item_for_sale`='Yes') AND (`item_status`='Active') OR (`category_name` LIKE '%food%'))*/
	
			$item= Vendoritem::find()
			->joinWith(['category'])
			->joinWith(['vendor'])
			->select(['item_name','whitebook_vendor_item.category_id','whitebook_vendor_item.vendor_id','whitebook_vendor_item.item_id','whitebook_vendor_item.slug as wvislug','whitebook_category.category_name','whitebook_category.slug as wcslug','whitebook_vendor.vendor_name','whitebook_vendor.slug as wvslug'])
			->where(['like', 'item_name',$name])
			->orWhere(['like', 'category_name', $name])
			->orWhere(['like', 'vendor_name', $name])
			->andwhere(['whitebook_vendor_item.trash' =>'Default','whitebook_category.trash' =>'Default','item_for_sale' =>'Yes','item_status'=>'Active'])
			->distinct()
			->asArray()			
			->all();
			
			return($item);
			
	}

     public static function vendoritemtitle($id)
    {       
        $item= Vendoritem::find()
        ->select(['item_name'])
        ->where(['=', 'item_id',$id])
        ->one();
        return $item['item_name']; 
    }

     public static function findvendoritem($slug)
    {       
        $item= Vendoritem::find()
        ->where(['=', 'slug',$slug])
        ->one();
        return $item; 
    }


		    public static function vendorpriorityitemitem($id)
	{       
			$item= Vendoritem::find()
			->select(['item_id','item_name'])
			->where(['=', 'item_id',$id])
			->andwhere(['trash' =>'Default','item_for_sale' =>'Yes'])
			->all();
			$item=ArrayHelper::map($item,'item_id','item_name');
			return $item;
	}
	    public static function loadsubcategoryvendoritem($subcategory)
	{       
			$item= Vendoritem::find()
			->where(['trash' =>'Default','item_for_sale' =>'Yes','subcategory_id'=>$subcategory])
			->all();
			$item=ArrayHelper::map($item,'item_id','item_name');
			return $item;
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
	
	 public static function statusImageurl($status)
	{			
		if($status == 'Active')		
		return \yii\helpers\Url::to('@web/uploads/app_img/active.png');
		return \yii\helpers\Url::to('@web/uploads/app_img/inactive.png');
	}	
	
		 public static function itemcount()
	{	
        return Vendoritem::find()->where(['trash' => 'Default'])->count();
	}
		 public static function itemmonthcount()
	{
		$month=date('m');
		$year=date('Y');
        return  Vendoritem::find()
        ->where(['MONTH(created_datetime)' => $month])
        ->andwhere(['YEAR(created_datetime)' => $year])
        ->count();
	}	
	 public static function itemdatecount()
	{
		$date=date('d');
		$month=date('m');
		$year=date('Y');
        return  Vendoritem::find()
        ->where(['MONTH(created_datetime)' => $month])
        ->andwhere(['YEAR(created_datetime)' => $year])
        ->andwhere(['DAYOFMONTH(created_datetime)' => $date])
        ->count();
	}
	
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
	public static function get_category_itemlist($itemid)
	{
		$k=array();
		if(!empty($itemid)){
		foreach($itemid as $i)
		{ 	 
		$categories[]= Vendoritem::find()
		->select(['category_id'])
        ->where(['item_id' => $i])
        ->one();
		}
		foreach($categories as  $cat)
		{
			$k[]=$cat['category_id'];
		}
		}
		if(!empty($k)){
		$k1=(array_unique($k));
		
		foreach($k1 as $c)
		{	
		$category_result[]= Category::find()
		->select(['category_id','category_name'])
        ->where(['category_id' => $c])
        ->one();
		} 
		return ($category_result);
		}
	
	}
	public static function get_vendor_itemlist($itemid)
	{
		if(!empty($itemid)){
		foreach($itemid as $i)
		{	
		$vendorlist[]= Vendoritem::find()
		->select(['vendor_id'])
        ->where(['item_id' => $i])
        ->one();
		}
		foreach($vendorlist as  $ven)
		{
			$k[]=$ven['vendor_id'];
		}
		$k1=(array_unique($k));
		foreach($k1 as $v)
		{
		$vendor_result[]= Vendor::find()
		->select(['vendor_id','vendor_name'])
        ->where(['vendor_id' => $v])
        ->one();
		}
		return $vendor_result;
		}
	}
	

    public static function vendoritemmonthcount()
	{
		$month=date('m');
		$year=date('Y');
		$id=Vendor::getVendor('vendor_id');
        return  Vendoritem::find()
        ->where(['MONTH(created_datetime)' => $month])
        ->andwhere(['vendor_id' => $id])
        ->andwhere(['YEAR(created_datetime)' => $year])
        ->count();
	}	
	public static function vendoritemdatecount()
	{
		$date=date('d');
		$month=date('m');
		$year=date('Y');
		$id=Vendor::getVendor('vendor_id');
        return  Vendoritem::find()
        ->where(['MONTH(created_datetime)' => $month])
        ->andwhere(['YEAR(created_datetime)' => $year])
        ->andwhere(['vendor_id' => $id])
        ->andwhere(['DAYOFMONTH(created_datetime)' => $date])
        ->count();
	}  
    
}
