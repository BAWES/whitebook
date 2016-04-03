<?php
namespace admin\models;

use Yii;
use yii\base\Model;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\web\IdentityInterface;
use yii\db\BaseActiveRecord;
use yii\helpers\Security;

/**
 * Login form
 */
class PasswordForm extends Model
{
    public $admin_email;
    public $old_password;
    public $new_password;
    public $confirm_password;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['admin_email'], 'required'],
            ['admin_email', 'email'],
            [['old_password','new_password','confirm_password'],'required','on' => 'change'],
        ];
    }

    public function scenarios()
    {
		$scenarios = parent::scenarios();
        $scenarios['change'] = ['old_password','new_password','confirm_password'];//Scenario Values Only Accepted
        return $scenarios;
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'admin_email' => 'Email',
        ];
    }


    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */

    public static function getUser()
    {
        if ($this->_user === false) {
            $this->_user = Admin::findByUsername($this->admin_email);
        }

        return $this->_user;
    }



}
