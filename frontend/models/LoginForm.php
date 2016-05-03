<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use frontend\models\Customer;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $email;
    public $password;
    public $rememberMe = true;

    /**
     * @var \frontend\models\Customer
     */
    private $_customer = false;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // email and password are both required
            [['email', 'password'], 'required'],
            // email must be an email
            ['email', 'email'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email' => Yii::t('app', 'Email'),
            'password' => Yii::t('app', 'Password'),
            'rememberMe' => Yii::t('app', 'Remember me'),
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $customer = $this->getCustomer();
            if (!$customer || !$customer->validatePassword($this->password)) {
                $this->addError($attribute, Yii::t('app', 'Incorrect email or password.'));
            }
        }
    }


    public function getCustomer()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }

    /**
     * Logs in a customer using the provided email and password.
     *
     * @return boolean whether the customer is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {

            //Check if Customer has verified his email
            $customer = $this->getCustomer();
            if($customer){
                //if($customer->customer_activation_status == Customer::EMAIL_NOT_VERIFIED){
                    //Email not verified, show warning message

                    /*
                    Yii::$app->session->setFlash("warning",
                        Yii::t('student',"Please click the verification link sent to you by email to activate your account.<br/><a href='{resendLink}'>Resend verification email</a>",[
                                'resendLink' => \yii\helpers\Url::to(["site/resend-verification",
                                    'id' => $customer->customer_id,
                                    'email' => $customer->customer_email,
                                ]),
                            ]));
                            */

                //}else{
                    //Log him in
                    return Yii::$app->user->login($this->getCustomer(), $this->rememberMe ? 3600 * 24 * 30 : 0);
                //}
            }
        }

        return false;
    }

    /**
     * Finds customer by email
     *
     * @return Customer|null
     */
    public function getCustomer()
    {
        if ($this->_customer === false) {
            $this->_customer = Customer::findByEmail($this->email);
        }

        return $this->_customer;
    }
}
