<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

class Signup extends Model
{

    public function tableName()
    {
        return 'whitebook_customer';
    }

    public function rules()
    {
        return [
            // the name, email, subject and body attributes are required
            [['customer_name', 'customer_last_name', 'email', 'password', 'confirm_password', 'bday', 'bmonth', 'byear', 'gender', 'phone'], 'required'],
            ['email', 'email'],
           // ['email', 'unique_email'],
            ['confirm_password', 'compare', 'compareAttribute' => 'password'],
        ];
    }

   

    public function check_valid_key($key)
    {
   		$check_key = Customer::find()
    		->select(['customer_id'])
    		->where(['customer_activation_status'=>0])
    		->andwhere(['customer_activation_key'=>$key])
    		->asArray()
    		->all();
        if (count($check_key) > 0) {
       			$model = new Signup();
       			$command=Signup::updateAll(['customer_activation_status' => 1],'customer_activation_key= '.$key);
            if ($command) {
                return 2;
            }
        } else {
            return 1;
        }
    }
    public function customer_logindetail($key)
    {
		return$check_key = Customer::find()
		->select(['customer_email','customer_org_password'])
		->where(['customer_activation_key'=>$key])
		->asArray()
		->all();
    }
}
