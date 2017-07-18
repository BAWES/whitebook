<?php

namespace frontend\models;

use Yii;
use yii\db\Expression;
use yii\helpers\Url;

/**
* This is the model class for table "customer".
* It extends from \common\models\Customer but with custom functionality for Customer application module
*
*/
class Customer extends \common\models\Customer {
    //Login Success Constants
    const SUCCESS_LOGIN = 1;

    //Login Error Constants
    const ERROR_EMAIL_NOT_VERIFIED = -1;
    const ERROR_ACCOUNT_DISABLED = -2;
    const ERROR_EMAIL_DOESNT_EXIST = -3;
    const ERROR_PASSWORD_NO_MATCH = -4;

    public $bday;
    public $bmonth;
    public $byear;
    public $confirm_password;
    public $agree_terms;
    public $rememberMe = true;

    private $_customer = false;

    /**
    * @inheritdoc
    */
    public function rules() {
        return array_merge(parent::rules(), [
            [['customer_name', 'customer_last_name', 'customer_email', 'customer_password', 'customer_mobile'], 'required'],
            ['customer_mobile', 'string', 'length' => [8]],

            //[[''], 'integer', 'length' => 8],

            [['customer_name', 'customer_last_name', 'customer_email', 'customer_mobile'], 'required', 'on'=>'guest'],

            [['customer_name', 'customer_last_name', 'customer_email', 'customer_password', 'confirm_password', 'customer_mobile', 'agree_terms'], 'required', 'on'=>'signup'],

            ['customer_password', 'compare', 'compareAttribute' => 'confirm_password', 'operator' => '==', 'on' => 'signup'],

            ['customer_email','email'],
            
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
        $scenarios['guest'] = ['customer_name', 'customer_last_name', 'customer_email', 'customer_mobile'];
        return $scenarios;
    }

    public function getErrorName() 
    {

    }

    public function login()
    {
        if ($this->validate()) {

            $model =  Customer::findByEmail($this->customer_email);

            if($model){

                if($model->customer_activation_status == self::ACTIVATION_FALSE)
                {
                    $this->addError('customer_email', Yii::t('frontend', 'Email not verified'));

                    return self::ERROR_EMAIL_NOT_VERIFIED;
                }
                elseif ($model->customer_status == self::STATUS_DEACTIVE) 
                {
                    $this->addError('customer_email', Yii::t('frontend', 'Account disabled'));

                    return self::ERROR_ACCOUNT_DISABLED;
                }
                else if($model->trash == self::TRASH_DELETED)
                {
                    $this->addError('customer_email', Yii::t('frontend', 'Email not exists'));

                    return self::ERROR_EMAIL_DOESNT_EXIST;
                }
                if (!Yii::$app->getSecurity()->validatePassword($this->customer_password, $model['customer_password']))
                {
                    $this->addError('customer_password', Yii::t('frontend', 'Password not match'));

                    return self::ERROR_PASSWORD_NO_MATCH;
                }

                //No issues, now we can successfully log the customer in
                Yii::$app->user->login($this->getCustomer(), $this->rememberMe ? 3600 * 24 * 30 : 0);
                
                return self::SUCCESS_LOGIN;

            }else{
                return self::ERROR_PASSWORD_NO_MATCH;
            }

        }
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

    public static function welcomeMail($model)
    {
        Yii::$app->mailer->htmlLayout = 'layouts/empty';

        Yii::$app->mailer->compose("customer/confirm",
            [
                "user" => $model->customer_name,
                "confirm_link" => Url::to(['/users/confirm_email', 'key' => $model->customer_activation_key], true),
                "logo_1" => Url::to("@web/images/twb-logo-horiz-white.png", true),
                "logo_2" => Url::to("@web/images/twb-logo-trans.png", true),
            ])
            ->setFrom(Yii::$app->params['supportEmail'])
            ->setTo($model['customer_email'])
            ->setSubject('Welcome to The White Book')
            ->send();

        //Send Email to admin
        Yii::$app->mailer->htmlLayout = 'layouts/empty';

        $send_admin = Yii::$app->mailer->compose("customer/user-register",
            [
                'confirm_link' => Url::to(['/users/confirm_email', 'key' => $model->customer_activation_key], true),
                'logo_1' => Url::to("@web/images/twb-logo-horiz-white.png", true),
                'logo_2' => Url::to("@web/images/twb-logo-trans.png", true),
                'model' => $model
            ]
        );

        $send_admin
            ->setFrom(Yii::$app->params['supportEmail'])
            ->setTo(Yii::$app->params['adminEmail'])
            ->setSubject('User registered')
            ->send();
    }            
}
