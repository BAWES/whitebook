<?php

namespace admin\models;

use Yii;
use yii\web\IdentityInterface;

/**
* This is the model class for table "whitebook_customer".
*
* @property string $customer_id
* @property string $customer_name
* @property string $customer_email
* @property string $customer_password
* @property string $customer_dateofbirth
* @property string $customer_gender
* @property string $customer_mobile
* @property integer $customer_activation_status
* @property integer $customer_activation_key
* @property integer $customer_status
* @property integer $message_status
* @property string $customer_last_login
* @property string $customer_ip_address
* @property integer $created_by
* @property string $modified_by
* @property string $created_datetime
* @property string $modified_datetime
* @property string $trash
*
* @property CustomerAddress[] $customerAddresses
* @property CustomerCart[] $customerCarts
* @property FeatureEvent[] $featureEvents
* @property Order[] $orders
*/
class Customer extends \common\models\Customer
{
   
    public static function customercount()
    {
        return Customer::find()->where(['trash' => 'Default'])->count();
    }

    public static function customermonthcount()
    {
        $month=date('m');
        $year=date('Y');
        return  Customer::find()
        ->where(['MONTH(created_datetime)' => $month])
        ->andwhere(['YEAR(created_datetime)' => $year])
        ->andwhere(['customer_status' => 'Active'])
        ->count();
    }

    public static function customerdatecount()
    {
        $date=date('d');
        $month=date('m');
        $year=date('Y');
        return  Customer::find()
        ->where(['MONTH(created_datetime)' => $month])
        ->andwhere(['YEAR(created_datetime)' => $year])
        ->andwhere(['DAYOFMONTH(created_datetime)' => $date])
        ->andwhere(['customer_status' => 'Active'])
        ->count();
    }

    public static function status($id)
    {
        $read=Customer::find()
        ->select(['message_status'])
        ->where(['customer_id' => $id])
        ->one();
        return $read['message_status'];
    }
}
