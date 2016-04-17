<?php

namespace frontend\models;

use common\models\User;
use common\models\Siteinfo;
use common\models\Socialinfo;
use common\models\Category;
use common\models\Adverthome;
use common\models\Slide;
use common\models\Events;
use common\models\Eventtype;
use yii\base\Model;
use Yii;
use yii\db\Query;

class Website extends Model {

    public static function get_general_settings() {
        return $general = Siteinfo::find()->where(['id'=>1])->asArray()->all();
    }

    public static function get_social_network() {
         return $social = Socialinfo::find()->where(['store_social_id'=>1])->asArray()->all();
    }

    public static function get_main_category() {
        return $general = Category::find()->select('category_id,category_icon,category_name,category_url')->where(['parent_category_id'=>'IS NULL', 'trash'=>"Default",'category_allow_sale'=>"yes"])->asArray()->all();
    }

    public static function get_home_ads() {
        return $ads = Adverthome::find()->select('advert_code')->where(['trash'=>'Default'])->limit(1)->asArray()->one();
    }

    public static function get_banner_details() {
        return $ads = Slide::find()->select('*')->where(['trash'=>'Default','slide_status'=>"Active"])->asArray()->all();
    }

    public static function get_search_directory_list($categoryid) {
        $today = date('Y-m-d H:i:s');
        $query = new Query();
        $query->select([
                    'whitebook_vendor.vendor_id AS vid',
                    'whitebook_vendor.vendor_name AS vname',
                    'whitebook_vendor.slug AS slug',
                ])
                ->from('whitebook_vendor')
                ->join('LEFT OUTER JOIN', 'whitebook_vendor_packages', 'whitebook_vendor.vendor_id =whitebook_vendor_packages.vendor_id')
                ->where('whitebook_vendor_packages.package_start_date <="' . $today . '"')
                ->andwhere('whitebook_vendor_packages.package_start_date <="' . $today . '"')
                ->andwhere('whitebook_vendor.vendor_status ="Active"')
                ->andwhere('whitebook_vendor.trash ="Default"')
                ->andwhere('whitebook_vendor.approve_status ="yes"')
                ->andwhere('FIND_IN_SET(' . $categoryid . ', whitebook_vendor.category_id)')
                ->orderBy('whitebook_vendor.vendor_name ASC')
                ->groupBy('whitebook_vendor.vendor_id')
                ->LIMIT(50);
        $command = $query->createCommand();
        $data = $command->queryAll();

        return $data;
    }

    public static function get_search_directory_all_list() {
        $today = date('Y-m-d H:i:s');
        $query = new Query();
        $query->select([
                    'whitebook_vendor.vendor_id AS vid',
                    'whitebook_vendor.vendor_name AS vname',
                    'whitebook_vendor.slug AS slug',
                ])
                ->from('whitebook_vendor')
                ->join('LEFT OUTER JOIN', 'whitebook_vendor_packages', 'whitebook_vendor.vendor_id =whitebook_vendor_packages.vendor_id')
                ->where('whitebook_vendor_packages.package_start_date <="' . $today . '"')
                ->andwhere('whitebook_vendor_packages.package_start_date <="' . $today . '"')
                ->andwhere('whitebook_vendor.vendor_status ="Active"')
                ->andwhere('whitebook_vendor.trash ="Default"')
                ->andwhere('whitebook_vendor.approve_status ="yes"')
                ->orderBy('whitebook_vendor.vendor_name ASC')
                ->groupBy('whitebook_vendor.vendor_id')
                ->LIMIT(50);
        $command = $query->createCommand();
        $data = $command->queryAll();
        return $data;
    }

    public static function get_category_id($category_url) {
        $general = Category::find()->select('category_id')->where(['category_url'=>$category_url])->asArray()->all();
        return $general[0]['category_id'];
    }

    public static function vendor_details($slug) {
        return $vendor = Vendor::find()->select('*')->where(['slug'=>$slug])->asArray()->all();
    }

    public static function vendor_item_details($id) {
        return $vendor = Vendoritem::find()->select('*')->where(['item_for_sale'=>'yes','item_approved'=>"yes",
        'item_status'=>"active", 'vendor_id'=>$id])->asArray()->all();
    }

    public static function vendor_social_info($vendor_id) {
        return $vendor = (new Query())
                ->select('*')
                ->from('whitebook_vendor_social_info')
                ->where(['vendor_id' => $vendor_id])
                ->all();
    }

    public static function getSEOdata($table = '', $field = '', $value = '', $data = '') {
        return $result = (new Query())
            ->select($data)
            ->from("{{%$table}}")
            ->where([$field=>$value])
            ->all();
    }

    public static function get_event_types() {
        return $event_type = Eventtype::find()->select('type_name,type_id')->asArray()->all();
    }

    public static function getCustomerEvents($customer_id) {
     return $events = Events::find()->select('event_id,event_name,event_type,event_date,slug')->where(['customer_id'=>$customer_id])->OrderBy('event_date ASC')->asArray()->all();
    }

    public static function check_user_fav($item_id) {
        $customer_id = CUSTOMER_ID;
        return $user_fav = (new Query())
            ->select($data)
            ->from('{{%_wishlist}}')
            ->where(['item_id'=>$item_id])
            ->andwhere(['customer_id'=>$customer_id])
            ->all();
    }

    public static function get_products_list($limit, $offset) {
        $today = date('Y-m-d H:i:s');
        $command = Yii::$app->DB->createCommand(
                'SELECT wvi.item_id,wvi.item_name,wvi.item_description,wvi.item_price_per_unit,wv.vendor_name,wc.category_id FROM {{%vendor_item}} wvi
		LEFT JOIN {{%vendor}} wv on wv.vendor_id=wvi.vendor_id
		LEFT JOIN {{%category}} wc on wc.category_id = wvi.category_id
			WHERE wvi.item_amount_in_stock>0
			AND wvi.item_approved="yes"
			AND wvi.item_archived="no"
			AND wvi.trash="Default"
			AND wvi.item_status="Active"
			AND wvi.item_for_sale="yes"
			AND wv.package_start_date<="' . $today . '"
			AND wv.package_end_date>="' . $today . '"
			AND wv.vendor_Status="Active"
			AND wv.trash="Default"
			AND wv.approve_status="Yes"
			AND wc.trash="Default"
			AND wc.category_allow_sale = "yes" LIMIT ' . $offset . ',' . $limit . '');
        $result = $command->queryAll();

        return $result;
    }

    public static function get_user_event_types($customer_id) {
        $query = new Query;
        $query  ->select([
                'whitebook_events.event_name',
                'whitebook_events.event_id'])
            ->from('whitebook_events')
            ->join('INNER JOIN', 'whitebook_event_type',
                        'whitebook_events.event_type =whitebook_event_type.type_name')
            ->andwhere('whitebook_vendor_item.trash ="default"')
            ->andwhere('whitebook_events.customer_id ='.$customer_id)
            ->orderBy(['item_id' => SORT_DESC]);
        $command = $query->createCommand();
        return $event_type1 = $command->queryAll();
    }

    // user event type birthday, wedding,etc
    public static function get_user_event($customer_id) {
        return $event_type = Events::find()->select('event_type')->where(['customer_id'=>$customer_id])->asArray()->distinct()-> all();
    }

    // GET SEO DATA
    public static function SEOdata($table_name='',$field='',$value='',$data=''){
        if(is_array($data)){
            $select = implode(',',$data);
        }
        if($table_name && $field && $value && $data){
            return Website::getSEOdata($table_name,$field,$value,$select);
        }else{
            return;
        }
    }

}
