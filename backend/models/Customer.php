<?php

namespace backend\models;

use Yii;

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
 * @property string $customer_last_login
 * @property string $customer_ip_address
 * @property integer $created_by
 * @property string $modified_by
 * @property integer $created_date
 * @property string $modified_date
 * @property string $trash
 *
 * @property CustomerAddress[] $customerAddresses
 * @property CustomerCart[] $customerCarts
 * @property FeatureEvent[] $featureEvents
 * @property Order[] $orders
 */
class Customer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
     
    public $newsmail;
    public $content;
    public static function tableName()
    {
        return 'whitebook_customer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_name', 'customer_email', 'customer_password', 'customer_mobile','customer_dateofbirth','customer_gender','customer_address'], 'required'],
            [['created_by', 'message_status'], 'integer'],
            [['customer_email'], 'unique'],
            [['newsmail','content'], 'required', 'on'=>'newsletter'],            
            [['customer_mobile'],'match', 'pattern' => '/^[0-9+ -]+$/','message' => 'Phone number accept only numbers and +,-'],
            [['customer_email'],'email'],            
            [['customer_address'], 'string', 'max' => 512]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'customer_id' => 'Customer',
            'newsmail' => ' Customer mail ID',
            'customer_name' => 'Customer Name',
            'customer_email' => 'Email',
            'customer_password' => 'Password',
            'customer_dateofbirth' => 'Date Of Birth',
            'customer_gender' => 'Gender',
            'customer_mobile' => 'Mobile',
            'customer_address' => 'Address',
            'customer_last_login' => 'Last Login',
            'customer_ip_address' => 'Ip Address',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'created_datetime' => 'Created DateTime',
            'modified_datetime' => 'Modified DateTime',
            'trash' => 'Trash',
            'address_type_id'=>'address type'
        ];
    }
    public function scenarios()
    {
		$scenarios = parent::scenarios();      
        $scenarios['newsletter'] = ['newsmail','content'];//Scenario Values Only Accepted
        return $scenarios;
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomerAddresses()
    {
        return $this->hasMany(CustomerAddress::className(), ['customer_id' => 'customer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomerCarts()
    {
        return $this->hasMany(CustomerCart::className(), ['customer_id' => 'customer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFeatureEvents()
    {
        return $this->hasMany(FeatureEvent::className(), ['customer_id' => 'customer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['customer_id' => 'customer_id']);
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
    
}
