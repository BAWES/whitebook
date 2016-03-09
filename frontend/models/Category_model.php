<?php
namespace frontend\models;

use yii\base\Model;
use Yii;
use yii\db\Query;

class Category_model extends Model
{
	public function get_products_based_category($category='',$limit,$offset)
	{
		$today=date('Y-m-d H:i:s');
		$command = Yii::$app->DB->createCommand(
		'SELECT wvi.item_id,wvi.item_name,wvi.item_description,wvi.item_price_per_unit,wv.vendor_name,wc.category_id FROM {{%vendor_item}} wvi 
		LEFT JOIN {{%vendor}} wv on wv.vendor_id=wvi.vendor_id
		LEFT JOIN {{%category}} wc on wc.category_id = wvi.category_id
			WHERE wvi.item_amount_in_stock>0 
			AND wvi.item_approved="yes"	
			AND wvi.item_archived="no" 
			AND wvi.trash="Default" 
			AND wvi.item_status="Active"
			AND wv.package_start_date<="'.$today.'"	
			AND wv.package_end_date>="'.$today.'" 
			AND wv.vendor_Status="Active"
			AND wv.trash="Default"
			AND wv.approve_status="Yes"
			AND wc.category_id ='.$category.'
			AND wc.trash="Default" 
			AND wc.category_allow_sale = "yes" LIMIT '.$offset.','.$limit.'');
		$result = $command->queryAll();
		return $result;
	}
	
	// get the category id based oin the category name
	public function get_category_id($category=''){
		$command = Yii::$app->DB->createCommand(
		'SELECT category_id,category_name FROM whitebook_category WHERE trash="Default" AND category_allow_sale = "yes" AND category_url="'.$category.'"');
		$result = $command->queryAll();
		return $result;
	}

    public function get_main_category()
   {
		$command = Yii::$app->DB->createCommand(
		'SELECT category_id,category_name,category_url FROM whitebook_category WHERE parent_category_id IS NULL and trash="Default" and category_allow_sale="yes"');
		$general = $command->queryAll();
		return $general;
   }
   
	public function get_category_top_ads()
	{
	   $command = Yii::$app->DB->createCommand(
		'SELECT advert_code FROM whitebook_advert_category where status="Active" and advert_position="top" limit 1');
		$ads = $command->queryAll();
		return $ads;
	}
	
	public function get_category_bottom_ads()
	{
	   $command = Yii::$app->DB->createCommand(
		'SELECT advert_code FROM whitebook_advert_category where status="Active" and advert_position="bottom" limit 1');
		$ads = $command->queryAll();
		return $ads;
	}
	
	 
   public function get_themes()
   {
		$command = Yii::$app->DB->createCommand(
		'SELECT theme_id,theme_name FROM whitebook_theme WHERE trash="Default" and theme_status="Active"');
		$general = $command->queryAll();
		return $general;
   }
   
	public function vendor_list()
	{
		$today=date('Y-m-d H:i:s');
		$command = Yii::$app->DB->createCommand(
		'SELECT vendor_id,vendor_name FROM whitebook_vendor
		WHERE whitebook_vendor.vendor_Status="Active"
		AND whitebook_vendor.trash="Default"
		AND whitebook_vendor.approve_status="Yes"
		AND whitebook_vendor.package_start_date<="'.$today.'"
		AND whitebook_vendor.package_end_date>="'.$today.'" 
		order by vendor_name asc');
		$vendor = $command->queryAll();
		return $vendor;
	}
	
	public function get_customer_events($customer_id)
	{
		$command = Yii::$app->DB->createCommand(
		'SELECT event_id,event_name,event_type,event_date FROM whitebook_events WHERE customer_id="'.$customer_id.'" order by event_date asc');
		$events = $command->queryAll();
		return $events;
	}
	
	public function get_event_types()
	{
		$command = Yii::$app->DB->createCommand(
		'SELECT type_name,type_id FROM whitebook_event_type');
		$event_type = $command->queryAll();
		return $event_type;
	}
	
}
