<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

class Signup extends Model
{
    public $customer_last_name;
    public $customer_name;
    public $email;
    public $password;
    public $confirm_password;
    public $bday;
    public $bmonth;
    public $byear;
    public $gender;
    public $phone;

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

    public function signup_customer($st, $customer_activation_key)
    {
        $customer_password = Yii::$app->getSecurity()->generatePasswordHash($st['password']);
        $customer_dateofbirth = $st['byear'].'-'.$st['bmonth'].'-'.$st['bday'];
        $created_date = date('Y-m-d H:i:s');
        $user = Yii::$app->DB->createCommand("INSERT INTO whitebook_customer (`customer_name`,`customer_last_name`,`customer_email`,`customer_password`,`customer_org_password`,`customer_dateofbirth`,`customer_gender`,`customer_mobile`,`customer_activation_key`,`created_datetime`) VALUES ('$st[customer_name]','$st[customer_last_name]','$st[email]','$customer_password','$st[password]','$customer_dateofbirth','$st[gender]','$st[phone]','$customer_activation_key','$created_date')")
                ->execute();

        return $user;
    }

    public function check_valid_key($key)
    {
        $command = Yii::$app->DB->createCommand(
        'SELECT customer_id FROM whitebook_customer WHERE customer_activation_status=0 and customer_activation_key="'.$key.'"');
        $check_key = $command->queryAll();
        if (count($check_key) > 0) {
            $command = Yii::$app->DB->createCommand(
                'UPDATE whitebook_customer set customer_activation_status=1 where customer_activation_key="'.$key.'"');
            $customer = $command->execute();
            if ($customer) {
                return 2;
            }
        } else {
            return 1;
        }
    }
    public function customer_logindetail($key)
    {
        $command = Yii::$app->DB->createCommand(
        'SELECT customer_email,customer_org_password FROM whitebook_customer WHERE customer_activation_key="'.$key.'"');

        return $check_key = $command->queryAll();
    }
}
