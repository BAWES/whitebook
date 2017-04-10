<?php

namespace frontend\models;

use Yii;
use common\models\BlockedDate;
use common\models\VendorCategory;
use yii\helpers\ArrayHelper;

class Vendor extends \common\models\Vendor
{
    public static function vendorcontactaddress($id){
        return Vendor::find()
            ->select(['vendor_contact_address','vendor_contact_number'])
            ->where(['vendor_id'=>$id])
            ->one();
    }

    public static function sociallist($id){
        return Vendor::find()
            ->select(['vendor_facebook','vendor_twitter','vendor_instagram','vendor_googleplus','vendor_contact_email'])
            ->where(['vendor_id'=>$id])
            ->one();
    }
    
    public static function loadvendornames()
    {
        return Vendor::find()
            ->where(['!=', 'vendor_status', 'Deactive'])
            ->andwhere(['!=', 'trash', 'Deleted'])
            ->asArray()
            ->all();
    }

    public static function loadvalidvendors()
    {	
    	$vendor = Vendor::find()
            ->select('{{%vendor}}.vendor_id')
            ->leftJoin('{{%vendor_item}}', '{{%vendor_item}}.vendor_id = {{%vendor}}.vendor_id')
            ->where(['{{%vendor}}.vendor_status' => 'Active','{{%vendor}}.trash' => 'Default','{{%vendor_item}}.trash' => 'Default','{{%vendor_item}}.item_status' => 'Active', '{{%vendor_item}}.item_approved' => 'Yes'])
            ->distinct()
            ->asArray()
            ->all();

        $vendor_ids = ArrayHelper::map($vendor, 'vendor_id', 'vendor_id');

        return implode('","', array_filter($vendor_ids));
    }


    public static function loadvendor_item($item)
    {
        return Vendor::find()
            ->select('{{%vendor}}.vendor_id,{{%vendor}}.vendor_name,{{%vendor}}.vendor_name_ar,{{%vendor}}.slug')
            ->leftJoin('{{%vendor_item}}', '{{%vendor_item}}.vendor_id = {{%vendor}}.vendor_id')
            ->where(['{{%vendor}}.vendor_status' => 'Active','{{%vendor}}.trash' => 'Default','{{%vendor_item}}.trash' => 'Default','{{%vendor_item}}.item_status' => 'Active', '{{%vendor_item}}.item_approved' => 'Yes'])
            ->all();
    }

    public static function loadvalidvendorids(
        $cat_id = false, 
        $arr_vendor_slugs = [], 
        $block_date = '', 
        $location = '')
    {
		$vendor_query = Vendor::find()
            ->select('{{%vendor}}.vendor_id')
            ->leftJoin('{{%vendor_item}}', '{{%vendor_item}}.vendor_id = {{%vendor}}.vendor_id')
            ->where([
                '{{%vendor}}.vendor_status' => 'Active',
                '{{%vendor}}.trash' => 'Default',
                '{{%vendor_item}}.trash' => 'Default',
                '{{%vendor_item}}.item_status' => 'Active']);
            
        if($cat_id != '') {
            $vendor_query->leftJoin(
                '{{%vendor_item_to_category}}', 
                '{{%vendor_item_to_category}}.item_id = {{%vendor_item}}.item_id'
            );
            $vendor_query->leftJoin(
                '{{%category_path}}', 
                '{{%vendor_item_to_category}}.category_id = {{%category_path}}.category_id'
            );
            $vendor_query->andWhere(['{{%category_path}}.path_id' => $cat_id]);
        }

        if($block_date) {
            $vendor_query->andWhere('{{%vendor}}.vendor_id NOT IN (select vendor_id from {{%vendor_blocked_date}} WHERE DATE(block_date) = DATE('.$block_date.'))');
        }else{
            $vendor_query->andWhere('{{%vendor}}.vendor_id NOT IN (select vendor_id from {{%vendor_blocked_date}} WHERE DATE(block_date) = DATE(NOW()))');
        }

        if($arr_vendor_slugs) {
            $vendor_query->andWhere(['in', '{{%vendor}}.slug', $arr_vendor_slugs]);
        }

        if($location) {
            $vendor_query->leftJoin(
                '{{%vendor_location}}',
                '{{%vendor_location}}.vendor_id = {{%vendor}}.vendor_id'
            );
            $vendor_query->andWhere(['{{%vendor_location}}.area_id' => $location]);
        }

        $vendor = $vendor_query
            ->asArray()
            ->all();

        return ArrayHelper::map($vendor, 'vendor_id', 'vendor_id');
    }
}
