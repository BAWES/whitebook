<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

class Signup extends Model
{
    public $bday;
    public $bmonth;
    public $byear;
    public $confirm_password;

    public function tableName()
    {
        return '{{%customer}}';
    }

    public function rules()
    {
        return [
            // the name, email, subject and body attributes are required
            [['customer_name', 'customer_last_name', 'customer_email', 'customer_password', 'confirm_password', 'customer_gender', 'customer_mobile','customer_dateofbirth'], 'required'],
            ['customer_email', 'email'],
           // ['email', 'unique_email'],
            ['confirm_password', 'compare', 'compareAttribute' => 'customer_password'],
        ];
    }
}
