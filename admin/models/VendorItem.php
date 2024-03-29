<?php

namespace admin\models;

use Yii;
use yii\helpers\ArrayHelper;
use admin\models\Vendor;
use yii\db\Expression;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
class VendorItem extends \common\models\VendorItem
{    
    public function rules()
    {
        return [

            //ItemApproval

            [['item_approved'], 'required', 'on' => ['ItemApproval']],
           
            //MenuItems

            [['allow_special_request', 'have_female_service'], 'number', 'on' => ['MenuItems']],

            //ItemPrice

            [['quantity_label'], 'string', 'max' => 256, 'on' => ['ItemPrice']],
           
            [['min_order_amount', 'item_price_per_unit', 'item_base_price'], 'number', 'on' => ['ItemPrice']],

            [['item_default_capacity', 'item_minimum_quantity_to_order', 'minimum_increment', 'type_id', 'hide_price_chart','included_quantity'], 'integer', 'on' => ['ItemPrice']],

            [['type_id','item_price_per_unit'], 'required', 'on' => ['ItemPrice']],

            //ItemDescription
            
            [['set_up_time', 'set_up_time_ar', 'requirements','requirements_ar', 'max_time', 'max_time_ar', 'item_how_long_to_make', 'notice_period_type', 'item_description', 'item_description_ar', 'item_additional_info', 'item_additional_info_ar'], 'string', 'on' => ['ItemDescription']],

            //ItemInfo
            
            [['vendor_id', 'item_name', 'item_name_ar'], 'required', 'on' => ['ItemInfo']]    
        ];
    }

    public function scenarios()
    {
        return [
            'ItemApproval' => ['item_status', 'item_approved'],
            
            'MenuItems' => ['allow_special_request', 'have_female_service'],

            'ItemPrice' => ['item_base_price', 'hide_price_chart', 'minimum_increment', 'quantity_label', 'item_default_capacity', 'item_minimum_quantity_to_order', 'item_price_per_unit', 'min_order_amount', 'type_id','included_quantity'],

            'ItemDescription' => ['set_up_time', 'set_up_time_ar', 'requirements','requirements_ar', 'max_time', 'max_time_ar', 'item_how_long_to_make', 'notice_period_type', 'item_description', 'item_description_ar', 'item_additional_info', 'item_additional_info_ar'],

            'ItemInfo' => ['vendor_id', 'item_name', 'item_name_ar']
        ];
    }

    /**
     * Validate step 4 on update / create item  
     */
    public static function validate_item_menu($data)
    {
        $errors = VendorItem::validate_item_price($data);

        $menu_items = Yii::$app->request->post('menu_item');
        
        if(!$menu_items) {
            $menu_items = array();
        }

        $menu_id = 0;

        foreach ($menu_items as $key => $value) {

            //if menu 
            if(isset($value['menu_name'])) {
                
                if(empty($value['menu_name'])) {
                    $errors['menu_name'] = 'Menu name field require.';
                }

                if(empty($value['menu_name_ar'])) {
                    $errors['menu_name_ar'] = 'Menu name - Arabic field require.';
                }

            //if menu item 
            } else {

                if(empty($value['menu_item_name'])) {
                    $errors['menu_item_name'] = 'Menu item name field require.';
                }

                if(empty($value['menu_item_name_ar'])) {
                    $errors['menu_item_name_ar'] = 'Menu item name - Arabic field require.';
                }
            }

            $menu_id++;
        }   

        return $errors;
    }

    /**
     * Validate step 5 on update / create item  
     */
    public static function validate_item_addon_menu($data)
    {
        $errors = VendorItem::validate_item_menu($data);

        $menu_items = Yii::$app->request->post('addon_menu_item');
        
        if(!$menu_items) {
            $menu_items = array();
        }

        $menu_id = 0;

        foreach ($menu_items as $key => $value) {

            //if menu 
            if(isset($value['menu_name'])) {
                
                if(empty($value['menu_name'])) {
                    $errors['menu_name'] = 'Menu name field require.';
                }

                if(empty($value['menu_name_ar'])) {
                    $errors['menu_name_ar'] = 'Menu name - Arabic field require.';
                }

            //if menu item 
            } else {

                if(empty($value['menu_item_name'])) {
                    $errors['menu_item_name'] = 'Menu item name field require.';
                }

                if(empty($value['menu_item_name_ar'])) {
                    $errors['menu_item_name_ar'] = 'Menu item name - Arabic field require.';
                }

                if(!is_numeric($value['price'])) {
                    $errors['menu_item_price'] = 'Menu item price is not valid.';
                }
            }

            $menu_id++;
        }   

        return $errors;
    }

    /**
    * To save created, modified user & date time
    */
    public function behaviors()
    {
        return [
            [
                'class' => SluggableBehavior::className(),
                'slugAttribute' => 'slug',
                'attribute' => 'item_name',
                'immutable' => true,
                'ensureUnique'=>true,
            ],
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'modified_by',
            ],
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_datetime',
                'updatedAtAttribute' => 'modified_datetime',
                'value' => new Expression('NOW()'),
            ],
        ];
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
            ->andwhere(['trash' =>'Default'])
            ->all();

        return ArrayHelper::map($item, 'item_id', 'item_name');
    }

    public static function loadsubcategoryvendoritem($subcategory)
    {
        $item = VendorItem::find()
            ->where([
                'trash' => 'Default',
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
