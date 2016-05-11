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
   
   public function rules()
   {
    return array_merge(parent::rules(),[
    [['customer_name','customer_email', 'customer_password', 'customer_mobile','customer_dateofbirth','customer_gender'], 'required' ,'on'=>'createAdmin'],
    ]);
   }

   /**
     * Scenarios for validation and massive assignment
     */
    public function scenarios() {
        $scenarios = parent::scenarios();

        $scenarios['createAdmin'] = ['customer_name','customer_email', 'customer_password', 'customer_mobile','customer_dateofbirth','customer_gender'];

        return $scenarios;
    }


   /* 
    *
    *   To save created, modified user & date time 
    */
    public function beforeSave($insert)
    {
        if($this->isNewRecord)
        {
           $this->created_datetime = \yii\helpers\Setdateformat::convert(time(),'datetime');
           $this->created_by = \Yii::$app->user->identity->id;
        } 
        else {
           $this->modified_datetime = \yii\helpers\Setdateformat::convert(time(),'datetime');
           $this->modified_by = \Yii::$app->user->identity->id;
        }
           return parent::beforeSave($insert);
    }
    

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
}
