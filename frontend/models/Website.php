<?php

namespace frontend\models;

use common\models\User;
use common\models\Siteinfo;
use common\models\Socialinfo;
use admin\models\Adverthome;
use common\models\Slide;
use common\models\Events;
use admin\models\Eventtype;
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
        return $general = Category::find()->select('category_id,category_name')->where(['parent_category_id'=>'IS NULL', 'trash'=>"Default",'category_allow_sale'=>"yes"])->asArray()->all();
    }

    public static function get_home_ads() {
        return $ads = Adverthome::find()->select('advert_code')->where(['trash'=>'Default'])->limit(1)->asArray()->one();
    }

    public static function get_banner_details() {
        return $ads = Slide::find()->select('*')->where(['trash'=>'Default','slide_status'=>"Active"])->asArray()->all();
    }

    public static function get_search_directory_list($categoryid, $sort = 'vendor_name') {
        $categoryid = (isset($categoryid)) ? $categoryid : '';
        
        $today = date('Y-m-d H:i:s');
             return $data=Vendor::find()
                ->select(['{{%vendor}}.vendor_id AS vid',
                        '{{%vendor}}.vendor_name AS vname',
                        '{{%vendor}}.vendor_name_ar AS vname_ar',
                        '{{%vendor}}.slug AS slug'])
                ->LEFTJOIN('{{%vendor_packages}}', '{{%vendor}}.vendor_id = {{%vendor_packages}}.vendor_id')
                ->where(['<=','{{%vendor_packages}}.package_start_date',$today])
                ->andWhere(['>=','{{%vendor_packages}}.package_end_date',$today])
    			->andWhere(['{{%vendor}}.trash'=>'Default'])
    			->andWhere(['{{%vendor}}.approve_status'=>'Yes'])
    			->andWhere(['{{%vendor}}.vendor_status'=>'Active'])
    			->andWhere(new \yii\db\Expression('FIND_IN_SET('.$categoryid.',{{%vendor}}.category_id)'))
    			->orderby(['{{%vendor}}.'.$sort => SORT_ASC])
    			->groupby(['{{%vendor}}.vendor_id'])
    			->asArray()
    			->all();
        }

    public static function get_search_directory_all_list($sort = 'vendor_name') {
		$today = date('Y-m-d H:i:s');
        
        return $data=Vendor::find()
            ->select(['{{%vendor}}.vendor_id AS vid',
                    '{{%vendor}}.vendor_name AS vname',
                    '{{%vendor}}.vendor_name_ar AS vname_ar',
                    '{{%vendor}}.slug AS slug'])
            ->LEFTJOIN('{{%vendor_packages}}', '{{%vendor}}.vendor_id = {{%vendor_packages}}.vendor_id')
            ->where(['<=','{{%vendor_packages}}.package_start_date',$today])
            ->andwhere(['>=','{{%vendor_packages}}.package_end_date',$today])
			->andwhere(['{{%vendor}}.trash'=>'Default'])
			->andwhere(['{{%vendor}}.approve_status'=>'Yes'])
			->andwhere(['{{%vendor}}.vendor_status'=>'Active'])
			->orderby(['{{%vendor}}.'.$sort => SORT_ASC])
			->groupby(['{{%vendor}}.vendor_id'])
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
                'item_for_sale' => 'yes',
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
        return Eventtype::find()
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
