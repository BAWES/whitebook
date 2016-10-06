<?php
namespace backend\models;

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
class VendorPassword extends Model
{
    public $vendor_contact_email;
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
            [['vendor_contact_email'], 'required'],           
            ['vendor_contact_email', 'email'],
            [['old_password','new_password','confirm_password'],'required','on' => 'change'],
        ];
    }       
    
    public function scenarios()
    {
		$scenarios = parent::scenarios();
        $scenarios['change'] = ['old_password','new_password','confirm_password'];//Scenario Values Only Accepted
        return $scenarios;
    }
    
    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
     
    public function getUsers()
    {
        if ($this->_user === false) {
            $this->_user = Vendor::findByUsername($this->vendor_contact_email);
        }

        return $this->_user;
    }
}
