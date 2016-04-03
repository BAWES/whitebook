<?php
namespace admin\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class VendorLogin extends Model
{
    public $vendor_contact_email;
    public $vendor_password;
    public $rememberMe = true;
    private $_user = false;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['vendor_contact_email', 'vendor_password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['vendor_password', 'validatePassword'],
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
            $user = $this->getUsers();
            if (!$user || !$user->validatePassword($this->vendor_password)) {
                $this->addError($attribute, 'Incorrect email or password.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {

        if ($this->validate()) {
            return Yii::$app->user->login($this->getUsers(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUsers()
    {
        if ($this->_user === false) {
			$session = Yii::$app->session;
			$session->open();
			$session['language'] = 'en-US';
			$session['email'] = $this->vendor_contact_email;
			$session['type'] = 'Vendor';
			$this->_user = Vendor::findByUsername($this->vendor_contact_email);
        }

        return $this->_user;
    }

     public function attributeLabels()
    {
        return [
            'vendor_contact_email' => 'Email',
            'vendor_password' => 'Password',

        ];
    }
}
