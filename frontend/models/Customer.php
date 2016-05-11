<?php

namespace frontend\models;

use Yii;
use yii\db\Expression;

/**
 * This is the model class for table "customer".
 * It extends from \common\models\Customer but with custom functionality for Customer application module
 *
 */
class Customer extends \common\models\Customer {
    public $bday;
    public $bmonth;
    public $byear;
    public $confirm_password;

    public $rememberMe = true;

    private $_customer = false;
    /**
     * @inheritdoc
     */
    public function rules() {
        return array_merge(parent::rules(), [
            [['customer_name', 'customer_last_name', 'customer_email', 'customer_password', 'customer_gender', 'customer_mobile'], 'required'],
            [['customer_name', 'customer_last_name', 'customer_email', 'customer_password', 'confirm_password', 'customer_gender', 'customer_mobile'], 'required', 'on'=>'signup'],
            ['customer_email','email'],
          //  ['confirm_password', 'compare', 'compareAttribute' => 'customer_password','required', 'on'=>'signup'],
            [['customer_email', 'customer_password',], 'required', 'on'=>'login'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean']
            //[['step', 'majorsSelected', 'languagesSelected'], 'required'],
        ]);
    }

    /**
     * Attribute labels that are inherited are extended here
     */
    public function attributeLabels() {
        return array_merge(parent::attributeLabels(), [
            //'majorsSelected' => Yii::t('app', 'Major(s) Studied'),
        ]);
    }

    /**
     * Scenarios for validation and massive assignment
     */
    public function scenarios() {
        $scenarios = parent::scenarios();
          $scenarios['login'] = ['customer_email','customer_password'];//Scenario Values Only Accepted
          $scenarios['signup'] = ['customer_name', 'customer_last_name', 'customer_email', 'customer_password', 'confirm_password', 'bday', 'bmonth', 'byear', 'customer_gender', 'customer_mobile'];
        //$scenarios['changeEmailPreference'] = ['employer_email_preference'];
        return $scenarios;
    }



    public function login()
    {
        if ($this->validate()) {

            $model =  Customer::findByEmail($this->customer_email);
            
            if($model['customer_activation_status'] == 0)
            {
             return -1;
            }
            elseif ($model['customer_status'] == 'Deactive') {
             return -2;
            } 
            else if($model['trash'] == "Deleted")
            {
             return -3;
            }
            if (!$model || !Yii::$app->getSecurity()->validatePassword($this->customer_password, $model['customer_password']))
            {
             return -4;
            }
            else
            {
             Yii::$app->user->login($this->getCustomer(), $this->rememberMe ? 3600 * 24 * 30 : 0);
             return 1;
            }
        }
    }


    /*
     * Set event sessions
     */
    public function setEventSession($user_email)
    {
       $session = Yii::$app->session;
       $session->open();
       $cus_model =  Customer::find()->where(['customer_email'=>$user_email])->one();
       $session['customer_id'] = $cus_model['customer_id'];
       $session['customer_email'] = $user_email;
       $session['customer_name'] = $cus_model['customer_name'];
    }

    /**
     * Finds customer by email
     *
     * @return Customer|null
     */
    public function getCustomer()
    {
        if ($this->_customer === false) {
            $this->_customer = Customer::findByEmail($this->customer_email);
        }

        return $this->_customer;
    }


   public static function findByEmail($email) {
        return static::findOne(['customer_email' => $email]);
    }

   public function getCustomerAddress()
    {
        return $this->hasMany(\common\models\CustomerAddress::className(), ['customer_id' => 'customer_id']);
    }

}
