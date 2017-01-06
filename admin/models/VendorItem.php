<?php

namespace admin\models;

use Yii;
use yii\helpers\ArrayHelper;
use admin\models\Vendor;

class VendorItem extends \common\models\VendorItem
{    
    public function get_posted_data()
    {
        return Yii::$app->request->post('VendorItem');        
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

        if(empty($data['vendor_id'])) {
            $errors['vendor_id'] = 'Vendor name cannot be blank.';
            return $errors;
        }

        if(empty($data['item_name'])) {
            $errors['item_name'] = 'Item name cannot be blank.';
            return $errors;
        }

        if(empty($data['item_name_ar'])) {
            $errors['item_name_ar'] = 'Item Name - Arabic cannot be blank.';
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

        if (!empty($data['item_for_sale'])){
            $item_for_sale = $data['item_for_sale'];
        } else {
            $item_for_sale = false;
        }

        if($item_for_sale && !$data['item_amount_in_stock']) {
            $errors['item_amount_in_stock'] = 'Item number of stock cannot be blank.';
        }

        if($item_for_sale && !$data['item_default_capacity']) {
            $errors['item_default_capacity'] = 'Item default capacity cannot be blank.';
        }

        if($item_for_sale && !$data['item_how_long_to_make']) {
            $errors['item_how_long_to_make'] = 'No of days delivery cannot be blank.';
        }

        if($item_for_sale && !$data['item_minimum_quantity_to_order']) {
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

        /*$images = Yii::$app->request->post('images');

        if(!$images) {
            $errors['images'] = 'Item image require.';
        }*/

        return $errors;
    }

    /**
    * To save created, modified user & date time
    */
    public function behaviors()
    {
        return parent::behaviors();
    }

    public static function getVendorName($id)
    {
        $model = Vendor::find()->where(['vendor_id' => $id])->one();
        
        if($model) {
            return $model->vendor_name;
        }
    }

    public static function vendorpriorityitemitem($id)
    {
        $item = VendorItem::find()
            ->select(['item_id','item_name'])
            ->where(['=', 'item_id',$id])
            ->andwhere(['trash' =>'Default','item_for_sale' =>'Yes'])
            ->all();

        return ArrayHelper::map($item, 'item_id', 'item_name');
    }

    public static function loadsubcategoryvendoritem($subcategory)
    {
        $item = VendorItem::find()
            ->where([
                'trash' => 'Default',
                'item_for_sale' => 'Yes',
                'subcategory_id' => $subcategory
            ])
            ->all();

        return ArrayHelper::map($item,'item_id','item_name');
    }

    public static function itemcount()
    {
        return VendorItem::find()->where(['trash' => 'Default'])->count();
    }

    public static function item_pending_count()
    {
        return VendorItem::find()->where([
                'trash' => 'Default',
                'item_approved' => 'Pending'
            ])->count();
    }

    public static function itemmonthcount()
    {
        $month = date('m');
        $year = date('Y');

        return  VendorItem::find()
            ->where(['MONTH(created_datetime)' => $month])
            ->andwhere(['YEAR(created_datetime)' => $year])
            ->count();
    }

    public static function itemdatecount()
    {
        $date = date('d');
        $month = date('m');
        $year = date('Y');

        return  VendorItem::find()
            ->where(['MONTH(created_datetime)' => $month])
            ->andwhere(['YEAR(created_datetime)' => $year])
            ->andwhere(['DAYOFMONTH(created_datetime)' => $date])
            ->count();
    }

    public function statusImageurl($img_status)
    {
        if($img_status == 'Active')
            return \yii\helpers\Url::to('@web/uploads/app_img/active.png');

        return \yii\helpers\Url::to('@web/uploads/app_img/inactive.png');
    }

    // Status Image title
    public function statusTitle($status)
    {
        if($status == 'Active')
            return 'Activate';
        return 'Deactivate';
    }

    //All Gridview Status Filter
    public static function Activestatus()
    {
        return $status = ['Active' => 'Activate', 'Deactive' => 'Deactivate'];
    }

   // Vendor Item Gridview Status Filter
    public static function Vendoritemstatus()
    {
        return $status = ['Yes' => 'Yes', 'Pending' => 'Pending','Rejected'=>'Rejected'];
    }

    public function getThemeName() {

        $string = [];
        
        foreach ($this->vendorItemThemes as $theme) {
              $string[] = ucfirst($theme->themeDetail->theme_name);
        }
        
        return implode(', ',$string);
    }
}
