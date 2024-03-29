<?php

namespace frontend\models;

use common\models\User;
use common\models\Siteinfo;
use common\models\Socialinfo;
use admin\models\AdvertHome;
use common\models\Slide;
use common\models\Events;
use admin\models\EventType;
use yii\base\Model;
use Yii;
use yii\db\Query;

class Website extends Model {

    public static function get_main_category() {
        
        return Category::find()
            ->select('category_id, category_name')
            ->where([
                'parent_category_id' => 'IS NULL', 
                'trash' => "Default"
            ])
            ->asArray()
            ->all();
    }

    public static function get_home_ads() {
        return AdvertHome::find()
            ->select('advert_code')
            ->where(['trash' => 'Default'])
            ->limit(1)
            ->asArray()
            ->one();
    }

    public static function get_banner_details() {
        return Slide::find()
            ->select('*')
            ->where(['trash' => 'Default', 'slide_status' => "Active"])
            ->asArray()
            ->all();
    }
    
    public static function get_category_id($category_url) {
        
        $general = Category::find()
            ->select('category_id')
            ->where(['category_url' => $category_url])
            ->asArray()
            ->one();

        return $general['category_id'];
    }

    public static function vendor_details($slug) {
        return Vendor::find()->select('*')->where(['slug'=>$slug])->asArray()->all();
    }

    public static function vendor_item_details($id) {
        return $vendor = VendorItem::find()
            ->where([
                'item_approved' => "yes",
                'item_status' => "active", 
                'vendor_id' => $id
            ])
            ->asArray()
            ->all();
    }

    public static function vendor_social_info($vendor_id) {
        return $vendor = (new Query())
            ->select('*')
            ->from('whitebook_vendor_social_info')
            ->where(['vendor_id' => $vendor_id])
            ->all();
    }

    public static function get_event_types() {
        return EventType::find()
            ->select('type_name, type_id')
            ->asArray()
            ->all();
    }

    /**
     * Returns event type list for specific customer 
     * @param integer $customer_id
     * @return array of event type 
     */
    public static function get_user_event_types($customer_id) {
		return $event_type = Events::find()
            ->select('event_type')
            ->where(['customer_id' => $customer_id])
            ->asArray()
            ->distinct()
            ->all();
    }

    /**
     * Returns event list for specific customer 
     * @param integer $customer_id
     * @return array of event data 
     */
    public static function getCustomerEvents($customer_id) {
        return Events::find()
            ->select(['{{%events}}.*'])
            ->INNERJOIN('{{%event_type}}', '{{%event_type}}.type_name = {{%events}}.event_type')
            ->where(['{{%events}}.customer_id'=>$customer_id])
            ->andwhere(['{{%event_type}}.trash'=>'Default'])
            ->orderby(['{{%events}}.event_id'=>SORT_DESC])
            ->asArray()
            ->all();        
    }

    /**
     * Returns the seo content from specific table 
     * @param string $table_name, $field, $value, array $data
     * @return array of seo data 
     */
    public static function SEOdata($table_name='', $field='', $value='', $data='') {

        if(is_array($data)){
            $select = implode(',', $data);
        }

        if($table_name && $field && $value && $data){
            
            return (new Query())
                ->select($data)
                ->from("{{%".$table_name."}}")
                ->where([$field => $value])
                ->all();

        }
    }
}
