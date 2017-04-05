<?php

namespace backend\models;

use Yii;
use backend\models\Vendor;

class VendorItem extends \common\models\VendorItem
{
    public function behaviors()
    {
        return parent::behaviors();
    }

    public function get_posted_data()
    {
        $arr_data_1 = Yii::$app->request->post('VendorItem');
        $arr_data_2 = Yii::$app->request->post('VendorDraftItem');

        if(!$arr_data_1) 
            $arr_data_1 = [];

        if(!$arr_data_2) 
            $arr_data_2 = [];
        
        return array_merge($arr_data_1, $arr_data_2);
    }        

    /**
     * Validate for complete button 
     */
    public static function validate_form($data)
    {
        $step_1 = VendorItem::validate_item_info($data);
        $step_2 = VendorItem::validate_item_description($data);
        $step_3 = VendorItem::validate_item_price($data);
        $step_4 = VendorItem::validate_item_images($data);

        return array_merge($step_1, $step_2, $step_3, $step_4);
    }
    
    /**
     * Validate step 1 on update / create item  
     */
    public static function validate_item_info($data)
    {
        $errors = [];

        $category = Yii::$app->request->post('category');

        if(!$category) 
        {
            $errors['category'] = 'Select main, sub and child category!';
        }

        if(empty($data['item_name'])) {
            $errors['item_name'] = 'Item name cannot be blank.';
            return $errors;
        }

        if(empty($data['item_name_ar'])) {
            $errors['item_name_ar'] = 'Item name - Arabic cannot be blank.';
            return $errors;
        }

        if(strlen($data['item_name']) < 4) {
            $errors['item_name'] = 'Item name minimum 4 letters.';
            return $errors;
        }

        /*
        $count_query = VendorItem::find()
            ->select('item_name')
            ->where([
                'item_name' => $data['item_name'],
                'trash' => 'Default'
            ]);

        $item_id = Yii::$app->request->post('item_id');

        if ($item_id) {            
            $count_query->andWhere(['!=', 'item_id', $item_id]);
        }

        if($count_query->count()) {
            $errors['item_name'] = 'Item name already exists.';
        }
        */

        return $errors;
    }

    /**
     * Validate step 2 on update / create item  
     */
    public static function validate_item_description($data)
    {
        $errors = VendorItem::validate_item_info($data);

        if(empty($data['type_id'])) {
            $errors['type_id'] = 'Item type cannot be blank.';
        }

        if(empty($data['item_description'])) {
            $errors['item_description'] = 'Item description cannot be blank.';
        }

        return $errors;
    }

    /**
     * Validate step 3 on update / create item  
     */
    public static function validate_item_price($data)
    {
        $errors = VendorItem::validate_item_description($data);

        if(!$data['item_default_capacity']) {
            $errors['item_default_capacity'] = 'Item default capacity cannot be blank.';
        }

        if(!$data['item_how_long_to_make']) {
            $errors['item_how_long_to_make'] = 'No of days delivery cannot be blank.';
        }

        if(!$data['item_minimum_quantity_to_order']) {
            $errors['item_minimum_quantity_to_order'] = 'Item minimum quantity to order cannot be blank.';
        }

        return $errors;
    }

    /**
     * Validate step 5 on update / create item  
     */
    public static function validate_item_images($data)
    {
        $errors = VendorItem::validate_item_price($data);

        $images = Yii::$app->request->post('images');

        if(!$images) {
            $errors['images'] = 'Item image require.';
        }

        return $errors;
    }

    public static function vendoritemmonthcount()
    {
        $month = date('m');
        $year = date('Y');
        
        $id = Vendor::getVendor('vendor_id');

        return VendorItem::find()
            ->where(['MONTH(created_datetime)' => $month])
            ->andwhere(['vendor_id' => $id])
            ->andwhere(['YEAR(created_datetime)' => $year])
            ->count();
    }

    public static function vendoritemdatecount($vendor_id = '')
    {
        $date = date('d');
        $month = date('m');
        $year = date('Y');
        
        if(!$vendor_id) {
            $vendor_id = Vendor::getVendor('vendor_id');    
        }
        
        return VendorItem::find()
            ->where([
                'MONTH(created_datetime)' => $month,
                'YEAR(created_datetime)' => $year,
                'vendor_id' => $vendor_id,
                'DAYOFMONTH(created_datetime)' => $date
            ])
            ->count();
    }
}
