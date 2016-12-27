<?php

namespace admin\models;

use Yii;
use yii\db\ActiveRecord;


class Vendor extends \common\models\Vendor
{
    public $category_id;

    public function behaviors()
    {
        return parent::behaviors();
    }

    public static function vendorcount()
    {
        return Vendor::find()->where(['trash' => 'Default'])->count();
    }

    public static function vendormonthcount()
    {
        $month = date('m');
        $year = date('Y');
        
        return Vendor::find()
            ->where(['MONTH(created_datetime)' => $month])
            ->andwhere(['YEAR(created_datetime)' => $year])
            ->count();
    }

    public static function vendordatecount()
    {
        $date = date('d');
        $month = date('m');
        $year = date('Y');
        
        return Vendor::find()
            ->where(['MONTH(created_datetime)' => $month])
            ->andwhere(['YEAR(created_datetime)' => $year])
            ->andwhere(['DAYOFMONTH(created_datetime)' => $date])
            ->count();
    }

    public static function getvendorname($id){
        $vendorname= Vendor::find()
            ->where(['vendor_id'=>$id])
            ->all();
        
        $vendorname= \yii\helpers\ArrayHelper::map($vendorname,'vendor_id','vendor_name');
        
        return $vendorname;
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

    /**
     * Validate for complete button 
     */
    public static function validate_form($data)
    {
        //call last step validation method to validate whole form 
        return Vendor::validate_email_addresses($data);
    }

    public static function validate_unique_name($vendor_name)
    {
        $vendor_id = Yii::$app->request->post('vendor_id');

        $count = Vendor::find()
            ->where('vendor_id != "'.$vendor_id.'" AND vendor_name="'.$vendor_name.'"')
            ->count();

        if($count)
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    public static function validate_unique_email($vendor_contact_email)
    {
        $vendor_id = Yii::$app->request->post('vendor_id');

        $count = Vendor::find()
            ->where('vendor_id != "'.$vendor_id.'" AND vendor_contact_email="'.$vendor_contact_email.'"')
            ->count();

        if($count)
        {
            return false;
        }
        else
        {
            return true;
        }
    }
        
    public static function validate_vendor_logo()
    {
        $errors = [];

        return $errors;
    }

    public static function validate_basic_info($posted_data)
    {
        $errors = [];

        if(!$posted_data['vendor_name']) 
        {
            $errors['vendor_name'] = 'Vendor name cannot be blank.';
        }

        if($posted_data['vendor_name'] && strlen($posted_data['vendor_name']) < 3) 
        {
            $errors['vendor_name'] = 'Vendor name minimum 4 letters.';
        } 
        elseif(!Vendor::validate_unique_name($posted_data['vendor_name']))
        {
            $errors['vendor_name'] = 'Vendor name already exist.';
        }

        if(!$posted_data['vendor_contact_email']) 
        {
            $errors['vendor_contact_email'] = 'Email cannot be blank.';
        }
        elseif(!Vendor::validate_unique_email($posted_data['vendor_contact_email']))
        {
            $errors['vendor_contact_email'] = 'Email already exist.';
        }

        if(!$posted_data['vendor_contact_name']) 
        {
            $errors['vendor_contact_name'] = 'Contact name cannot be blank.';
        }

        if(!$posted_data['vendor_contact_number']) 
        {
            $errors['vendor_contact_number'] = 'Contact number cannot be blank.';
        }

        return $errors;
    }

    public static function validate_main_info($posted_data)
    {
        $errors = Vendor::validate_basic_info($posted_data);

        return $errors;
    }

    public static function validate_social_info($posted_data)
    {
        $errors = Vendor::validate_main_info($posted_data);

        return $errors;
    }

    public static function validate_additional_info($posted_data)
    {
        $errors = Vendor::validate_social_info($posted_data);

        return $errors;
    }

    public static function validate_email_addresses($posted_data)
    {
        $errors = Vendor::validate_additional_info($posted_data);

        return $errors;
    }
}
