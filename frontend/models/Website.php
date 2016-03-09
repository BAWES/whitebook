<?php

namespace frontend\models;

use common\models\User;
use yii\base\Model;
use Yii;
use yii\db\Query;

class Website extends Model
{
    public static function get_general_settings()
    {
        $command = Yii::$app->DB->createCommand(
        'SELECT * FROM whitebook_siteinfo WHERE id=1');
        $general = $command->queryAll();

        return $general;
    }

    public static function get_social_network()
    {
        $command = Yii::$app->DB->createCommand(
        'SELECT * FROM whitebook_social_info WHERE store_social_id=1');
        $social = $command->queryAll();

        return $social;
    }
    public static function get_email_template()
    {
        $command = Yii::$app->DB->createCommand(
        'SELECT * FROM whitebook_email_template');
        $email_template = $command->queryAll();

        return $email_template;
    }

    public static function get_main_category()
    {
        $command = Yii::$app->DB->createCommand(
        'SELECT category_id,category_icon,category_name,category_url FROM whitebook_category WHERE parent_category_id IS NULL and trash="Default" and category_allow_sale="yes"');
        $general = $command->queryAll();

        return $general;
    }

    public static function get_featured_product_id()
    {
        $db = Yii::$app->db;

        return $p_id = $db->cache(function ($db) {
        $today = date('Y-m-d H:i:s');
        $today_date = date('Y-m-d');
        $db->createCommand(
        'SELECT whitebook_feature_group_item.item_id FROM whitebook_feature_group_item
		JOIN whitebook_vendor on whitebook_vendor.vendor_id=whitebook_feature_group_item.vendor_id
		WHERE whitebook_feature_group_item.group_item_status="Active"
		AND whitebook_vendor.vendor_Status="Active"
		AND whitebook_vendor.trash="Default"
		AND whitebook_vendor.approve_status="Yes"
		AND whitebook_vendor.package_start_date<="'.$today.'"
		AND whitebook_vendor.package_end_date>="'.$today.'"
		AND whitebook_feature_group_item.featured_start_date<="'.$today_date.'"
		AND whitebook_feature_group_item.featured_end_date>="'.$today_date.'"')->queryAll();
        });
    }

    public static function get_featured_product()
    {
        $today = date('Y-m-d H:i:s');
        $sql = 'SELECT item_id,whitebook_vendor_item.slug as slug,item_name,item_price_per_unit,vendor_name FROM whitebook_vendor_item
		JOIN whitebook_vendor on whitebook_vendor.vendor_id=whitebook_vendor_item.vendor_id
		JOIN whitebook_category on whitebook_category.category_id=whitebook_vendor_item.category_id
			WHERE whitebook_vendor_item.item_status="Active"';
        $command = Yii::$app->DB->createCommand($sql);
        $feature = $command->queryAll();

        return $feature;
    }
    public static function get_home_ads()
    {
        $command = Yii::$app->DB->createCommand(
        'SELECT advert_code FROM whitebook_advert_home where trash="Default" limit 1');
        $ads = $command->queryAll();

        return $ads;
    }

    public static function get_banner_details()
    {
        $command = Yii::$app->DB->createCommand(
        'SELECT * FROM whitebook_slide where trash="Default" and slide_status="Active" order by sort');
        $ads = $command->queryAll();

        return $ads;
    }

    public static function get_directory_list()
    {
        $today = date('Y-m-d H:i:s');

        $query = new Query();
        $query->select([
        'whitebook_vendor.vendor_id AS vid',
        'whitebook_vendor.vendor_name AS vname',
        'whitebook_vendor.slug AS slug',
        ]
        )
    ->from('whitebook_vendor')
    ->join('LEFT OUTER JOIN', 'whitebook_vendor_packages',
                'whitebook_vendor.vendor_id =whitebook_vendor_packages.vendor_id')
    ->where('whitebook_vendor_packages.package_start_date <="'.$today.'"')
    ->andwhere('whitebook_vendor_packages.package_start_date <="'.$today.'"')
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
    public static function get_search_directory_list($categoryid)
    {
        $today = date('Y-m-d H:i:s');
        $query = new Query();
        $query->select([
        'whitebook_vendor.vendor_id AS vid',
        'whitebook_vendor.vendor_name AS vname',
        'whitebook_vendor.slug AS slug',
        ])
    ->from('whitebook_vendor')
    ->join('LEFT OUTER JOIN', 'whitebook_vendor_packages',
                'whitebook_vendor.vendor_id =whitebook_vendor_packages.vendor_id')
    ->where('whitebook_vendor_packages.package_start_date <="'.$today.'"')
    ->andwhere('whitebook_vendor_packages.package_start_date <="'.$today.'"')
    ->andwhere('whitebook_vendor.vendor_status ="Active"')
    ->andwhere('whitebook_vendor.trash ="Default"')
    ->andwhere('whitebook_vendor.approve_status ="yes"')
    ->andwhere('FIND_IN_SET('.$categoryid.', whitebook_vendor.category_id)')
    ->orderBy('whitebook_vendor.vendor_name ASC')
    ->groupBy('whitebook_vendor.vendor_id')
    ->LIMIT(50);
        $command = $query->createCommand();
        $data = $command->queryAll();

        return $data;
    }

    public static function get_search_directory_all_list()
    {
        $today = date('Y-m-d H:i:s');
        $query = new Query();
        $query->select([
        'whitebook_vendor.vendor_id AS vid',
        'whitebook_vendor.vendor_name AS vname',
        'whitebook_vendor.slug AS slug',
        ])
    ->from('whitebook_vendor')
    ->join('LEFT OUTER JOIN', 'whitebook_vendor_packages',
                'whitebook_vendor.vendor_id =whitebook_vendor_packages.vendor_id')
    ->where('whitebook_vendor_packages.package_start_date <="'.$today.'"')
    ->andwhere('whitebook_vendor_packages.package_start_date <="'.$today.'"')
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

    public static function get_category_id($category_url)
    {
        $command = Yii::$app->DB->createCommand(
        'SELECT category_id FROM whitebook_category WHERE category_url="'.$category_url.'"');
        $general = $command->queryAll();

        return $general[0]['category_id'];
    }

    public static function vendor_details($slug)
    {
        $command = Yii::$app->DB->createCommand(
        'SELECT * FROM whitebook_vendor WHERE slug="'.$slug.'"');
        $vendor = $command->queryAll();

        return $vendor;
    }

    public static function vendor_item_details($id)
    {
        $command = Yii::$app->DB->createCommand(
        'SELECT * FROM whitebook_vendor_item WHERE item_for_sale="yes" and item_approved="yes" and item_status="active" and vendor_id="'.$id.'"');
        $vendor = $command->queryAll();

        return $vendor;
    }

    public static function vendor_social_info($vendor_id)
    {
        $command = Yii::$app->DB->createCommand(
        'SELECT * FROM whitebook_vendor_social_info WHERE vendor_id="'.$vendor_id.'"');
        $vendor = $command->queryAll();

        return $vendor;
    }

    public static function getSEOdata($table = '', $field = '', $value = '', $data = '')
    {
        $command = Yii::$app->DB->createCommand("SELECT $data FROM {{%$table}} WHERE $field=$value");
        $result = $command->queryAll();

        return $result;
    }
    public static function get_event_types()
    {
        $command = Yii::$app->DB->createCommand(
        'SELECT type_name,type_id FROM whitebook_event_type');
        $event_type = $command->queryAll();

        return $event_type;
    }

    public static function get_customer_events($customer_id)
    {
        $command = Yii::$app->DB->createCommand(
        'SELECT event_id,event_name,event_type,event_date,slug FROM whitebook_events WHERE customer_id="'.$customer_id.'" order by event_date asc');
        $events = $command->queryAll();

        return $events;
    }

    public static function check_user_fav($item_id)
    {
        $customer_id = CUSTOMER_ID;
        $command = Yii::$app->DB->createCommand(
        'SELECT wish_status FROM whitebook_wishlist WHERE item_id="'.$item_id.'" and customer_id="'.$customer_id.'"');
        $user_fav = $command->queryAll();

        return $user_fav;
    }

    public static function get_products_list($limit, $offset)
    {
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
			AND wv.package_start_date<="'.$today.'"
			AND wv.package_end_date>="'.$today.'"
			AND wv.vendor_Status="Active"
			AND wv.trash="Default"
			AND wv.approve_status="Yes"
			AND wc.trash="Default"
			AND wc.category_allow_sale = "yes" LIMIT '.$offset.','.$limit.'');
        $result = $command->queryAll();

        return $result;
    }

    public static function get_user_event_types($customer_id)
    {
        $command = Yii::$app->DB->createCommand("SELECT DISTINCT event_name,event_id
                  FROM whitebook_events
                  INNER JOIN whitebook_event_type
                  ON whitebook_events.event_type=whitebook_event_type.type_name
                  WHERE whitebook_event_type.trash='default' and whitebook_events.customer_id='$customer_id'");
        $event_type1 = $command->queryAll();

        return $event_type1;
    }
    // user event type birthday, wedding,etc
        public static function get_user_event($customer_id)
        {
            $command = Yii::$app->DB->createCommand(
        'SELECT DISTINCT event_type FROM whitebook_events where customer_id ='.$customer_id);
            $event_type = $command->queryAll();

            return $event_type;
        }
}
