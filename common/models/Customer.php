<?php

namespace common\models;

use Yii;
use yii\web\IdentityInterface;
use common\models\CustomerAddress;
use common\models\CustomerCart;

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
* @property integer $customer_activation_status
* @property integer $customer_activation_key
* @property integer $customer_status
* @property integer $message_status
* @property string $customer_last_login
* @property string $customer_ip_address
* @property integer $created_by
* @property string $modified_by
* @property string $created_datetime
* @property string $modified_datetime
* @property string $trash
*
* @property CustomerAddress[] $customerAddresses
* @property CustomerCart[] $customerCarts
* @property FeatureEvent[] $featureEvents
* @property Order[] $orders
*/
class Customer extends \yii\db\ActiveRecord implements IdentityInterface
{
    const STATUS_ACTIVE = "Active";
    const STATUS_DEACTIVE = "Deactive";

    const TRASH_DELETED = "Deleted";

    const ACTIVATION_TRUE = 1;
    const ACTIVATION_FALSE = 0;

    //Email verification values for `customer_email_verification`
    const EMAIL_VERIFIED = 1;
    const EMAIL_NOT_VERIFIED = 0;

    /**
    * @inheritdoc
    */
    public $newsmail;
    public $content;
    public $customer_auth_key;

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
            [['customer_name','customer_last_name', 'customer_email', 'customer_password', 'customer_mobile'], 'required'],
            [['created_by', 'message_status'], 'integer'],
            [['customer_email'], 'unique', 'on'=>'signup'],
            [['newsmail','content'], 'required', 'on'=>'newsletter'],
            [['customer_mobile'],'match', 'pattern' => '/^[0-9+ -]+$/','message' => 'Phone number accept only numbers and +,-'],
            [['customer_email'],'email'],
            ['trash', 'safe'],

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

   /*
     * Start Identity Code
     */

    /**
     * @inheritdoc
     */
    public static function findIdentity($id) {
        return static::findOne(['customer_id' => $id]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null) {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds student by email
     *
     * @param string $email
     * @return static|null
     */

    /**
     * Finds customer by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token) {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
                    'customer_password_reset_token' => $token,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token) {
        if (empty($token)) {
            return false;
        }
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId() {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey() {
        return $this->customer_auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey) {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    /*
    public function validatePassword($password) {
        return Yii::$app->security->validatePassword($password, $this->customer_password_hash);
    }*/

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    /*
    public function setPassword($password) {
        $this->customer_password_hash = Yii::$app->security->generatePasswordHash($password);
    } */

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey() {
        $this->customer_auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken() {
        $this->customer_password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken() {
        $this->customer_password_reset_token = null;
    }

    public function getCustomerAddress()
    {
        return $this->hasMany(CustomerAddress::className(), ['customer_id' => 'customer_id']);
    }

    public function getCustomerCart()
    {
        return $this->hasMany(CustomerCart::className(), ['customer_id' => 'customer_id']);
    }

    public static function currentUser(){
        return self::getSessionUser();
    }

    public static function getSessionUser() {
        if (Yii::$app->session->has('_user')) {
            return Yii::$app->session->get('_user');
        } else {
            $SessionUserID = self::getSessionCartID();
            Yii::$app->session->set('_user', $SessionUserID);
            return Yii::$app->session->get('_user');
        }
    }

    public static function destroySessionUser() {
        if (Yii::$app->session->has('_user')) {
            Yii::$app->session->remove('_user');
        }
    }

    public static function getSessionCartID() {
        $unique = Yii::$app->getSecurity()->generateRandomString(13);
        return $unique.strtotime('now');
    }
}
