<?php

namespace common\models;

use Yii;
use yii\web\IdentityInterface;
use yii\db\BaseActiveRecord;
use yii\helpers\Security;
use yii\db\ActiveRecord;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
* This is the model class for table "{{%vendor}}".
*
* @property string $vendor_id
* @property string $vendor_name
* @property string $vendor_return_policy
* @property string $vendor_public_email
* @property string $vendor_working_hours
* @property string $vendor_contact_name
* @property string $vendor_contact_email
* @property string $vendor_contact_number
* @property string $vendor_emergency_contact_name
* @property string $vendor_emergency_contact_email
* @property string $vendor_emergency_contact_number
* @property string $vendor_website
* @property string $vendor_password
* @property string $vendor_status
* @property integer $created_by
* @property integer $modified_by
* @property string $created_datetime
* @property string $modified_datetime
* @property string $vendor_booking_managed_by
* @property string $trash
* @property string $auth_token
*
* @property Suborder[] $suborders
* @property VendorAddress[] $vendorAddresses
* @property VendorBlockedDate[] $vendorBlockedDates
* @property VendorDeliveryArea[] $vendorDeliveryAreas
* @property VendorDeliveryTimeslot[] $vendorDeliveryTimeslots
* @property VendorItem[] $vendorItems
*/
class Vendor extends \yii\db\ActiveRecord implements IdentityInterface
{
    const STATUS_ACTIVE = "Active";
    const STATUS_DEACTIVE = "Deactive";
	
	/* Upload */
    const UPLOADFOLDER = "vendor_logo/";

    public $auth_key;
    public $password_hash;
    public $password_reset_token;
    public $area_id;
    public $confirm_password;
    public $vendor_working_min;
    public $vendor_working_min_to;
    
    /**
    * @inheritdoc
    */
    public static function tableName()
    {
        return '{{%vendor}}';
    }


    public function behaviors()
    {
        return [
            [
                'class' => SluggableBehavior::className(),
                'attribute' => 'vendor_name',
            ],
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'modified_by',
            ],
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_datetime',
                'updatedAtAttribute' => 'modified_datetime',
                'value' => new Expression('NOW()'),
            ],
        ];
    }
    /**
    * @inheritdoc
    */
    public function rules()
    {
        return [
            [['vendor_name', 'vendor_name_ar', 'vendor_contact_name','vendor_password','vendor_contact_email','vendor_contact_number'], 'required'],
            [['vendor_name', 'vendor_name_ar', 'vendor_contact_name','vendor_password','vendor_contact_email','vendor_contact_number','confirm_password'], 'required', 'on'=>'register'],
            [['vendor_name', 'vendor_name_ar', 'vendor_contact_name'], 'required', 'on'=>'vendorprofile'],
            [['vendor_name', 'vendor_name_ar', 'vendor_contact_name','vendor_password','vendor_contact_email','vendor_contact_number'], 'required', 'on'=>'vendorUpdate'],
            [['created_by', 'modified_by'], 'integer'],
            [['vendor_payable'], 'number'],
            [['vendor_return_policy','vendor_return_policy_ar', 'vendor_name', 'vendor_name_ar', 'vendor_contact_name','vendor_booking_managed_by'], 'string'],
            [['created_datetime', 'modified_datetime', 'vendor_fax'], 'safe'],
            [['vendor_website','vendor_facebook','vendor_twitter','vendor_instagram', 'vendor_youtube'],'url', 'defaultScheme' => 'http'],
            /* Validation Rules */
            [['confirm_password'], 'compare', 'compareAttribute'=>'vendor_password','message'=>'Password and confirm password not same' ],
            [['vendor_contact_email'],'email'],
            ['vendor_contact_email', 'unique'],
            [['approve_status','vendor_name', 'vendor_name_ar', 'vendor_public_email','vendor_contact_name','vendor_contact_email','vendor_contact_address', 'vendor_contact_address_ar' ,'vendor_emergency_contact_name', 'vendor_emergency_contact_email', 'vendor_emergency_contact_number','vendor_logo_path', 'vendor_bank_name', 'vendor_bank_branch', 'vendor_account_no', 'vendor_website', 'vendor_facebook','vendor_facebook_text', 'vendor_twitter','vendor_twitter_text', 'vendor_instagram','vendor_instagram_text', 'vendor_youtube','vendor_youtube_text','auth_token'], 'string', 'max' => 128],
            ['vendor_logo_path', 'image', 'extensions' => 'png, jpg, jpeg','skipOnEmpty' => false,'on' => 'register'],
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['register'] = ['day_off', 'vendor_name', 'vendor_name_ar', 'vendor_contact_name','vendor_contact_email','vendor_contact_number','vendor_contact_address', 'vendor_contact_address_ar','vendor_return_policy','vendor_return_policy_ar', 'vendor_status','vendor_website','vendor_facebook','vendor_facebook_text','vendor_twitter','vendor_twitter_text','vendor_instagram','vendor_instagram_text','vendor_youtube','vendor_youtube_text','vendor_bank_name', 'vendor_bank_branch', 'vendor_account_no','vendor_fax','short_description','short_description_ar', 'vendor_logo_path', 'vendor_working_hours_to', 'vendor_working_hours'];//Scenario Values Only Accepted
        $scenarios['vendorUpdate'] = ['day_off', 'vendor_name', 'vendor_name_ar', 'vendor_contact_name','vendor_contact_email','vendor_contact_number','vendor_contact_address', 'vendor_contact_address_ar','vendor_return_policy','vendor_return_policy_ar', 'vendor_status','vendor_website','vendor_facebook','vendor_facebook_text','vendor_twitter','vendor_twitter_text','vendor_instagram','vendor_instagram_text','vendor_youtube','vendor_youtube_text','vendor_bank_name', 'vendor_bank_branch', 'vendor_account_no','vendor_fax','short_description','short_description_ar', 'vendor_logo_path', 'vendor_working_hours_to', 'vendor_working_hours'];//Scenario Values Only Accepted
        $scenarios['vendorprofile'] = ['day_off', 'vendor_logo_path', 'vendor_name', 'vendor_name_ar', 'vendor_contact_name','vendor_contact_email','vendor_contact_number','vendor_contact_address', 'vendor_contact_address_ar','vendor_working_hours', 'vendor_working_hours_to', 'vendor_return_policy','vendor_return_policy_ar', 'vendor_fax', 'short_description', 'short_description_ar', 'vendor_bank_name', 'vendor_bank_branch', 'vendor_account_no', 'vendor_public_email', 'vendor_emergency_contact_email', 'vendor_website', 'vendor_facebook','vendor_facebook_text','vendor_twitter','vendor_twitter_text','vendor_instagram','vendor_instagram_text','vendor_youtube','vendor_youtube_text'];//Scenario Values Only Accepted
        $scenarios['change'] = ['vendor_password'];//Scenario Values Only Accepted
        return $scenarios;
    }
    /**
    * @inheritdoc
    */
    public function attributeLabels()
    {
        return [
            'vendor_id' => 'Vendor',
            // 'commision' => 'Commision ( % )',
            'vendor_payable' => 'Payable',
            'subcategory_id' => 'Sub category',
            'vendor_name' => 'Vendor Name',
            'vendor_name_ar' => 'Vendor Name - Arabic', 
            'vendor_contact_address' => 'Contact Address',
            'vendor_contact_address_ar' => 'Contact Address - Arabic',
            'vendor_return_policy' => 'Vendor Return Policy',
            'vendor_return_policy_ar' => 'Vendor Return Policy - Arabic',
            'vendor_public_email' => 'Vendor Public Email',
            'vendor_working_hours' => 'From working Hours',
            'vendor_working_min' => 'From minutes',
            'vendor_working_hours_to' => 'To working Hours',
            //'vendor_working_min_to' => 'To minutes',
            'vendor_contact_name' => 'Vendor Contact Name',
            'vendor_contact_email' => 'Email',
            'vendor_contact_number' => 'Vendor Contact Number',
            'vendor_emergency_contact_name' => 'Vendor Emergency Contact Name',
            'vendor_emergency_contact_email' => 'Vendor Emergency Contact Email',
            'vendor_emergency_contact_number' => 'Vendor Emergency Contact Number',
            'vendor_website' => 'Vendor Website URL',
            'vendor_facebook','vendor_facebook_text' => 'Vendor Facebook URL',
            'vendor_twitter' => 'Vendor Twitter URL',
            'vendor_instagram' => 'Vendor Instagram URL',
            'vendor_password' => 'Password',
            'vendor_status' => 'Active Vendor',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'created_datetime' => 'Created Datetime',
            'modified_datetime' => 'Modified Datetime',
            'trash' => 'Trash',
            'vendor_bank_name' => 'Vendor Bank Name',
            'vendor_bank_branch' => 'Vendor Bank Branch',
            'vendor_account_no' => 'Vendor Account No',
            'short_description'=>'Vendor Short Description',
            'short_description_ar'=>'Vendor Short Description - Arabic',
            'approve_status'=>'Approve vendor',
            'vendor_logo_path'=>'vendor logo',
            'vendor_booking_managed_by'=>'Vendor Booking Managed By',
            'auth_token'=>'Auth Token'
        ];
    }

    /**   Relation function begin here
    *
    *
    * @return \yii\db\ActiveQuery
    */
    public function getSuborders()
    {
        return $this->hasMany(Suborder::className(), ['vendor_id' => 'vendor_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getVendorAddresses()
    {
        return $this->hasMany(VendorAddress::className(), ['vendor_id' => 'vendor_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getVendorBlockedDates()
    {
        return $this->hasMany(VendorBlockedDate::className(), ['vendor_id' => 'vendor_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getVendorDeliveryAreas()
    {
        return $this->hasMany(VendorDeliveryArea::className(), ['vendor_id' => 'vendor_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getVendorDeliveryTimeslots()
    {
        return $this->hasMany(VendorDeliveryTimeslot::className(), ['vendor_id' => 'vendor_id']);
    }

    /** INCLUDE USER LOGIN VALIDATION FUNCTIONS**/
    /**
    * @inheritdoc
    */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
    * @inheritdoc
    */
    /* modified */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    /**
    * Finds user by username
    *
    * @param string $email
    * @return static|null
    */
    public static function findByUsername($email)
    {
        return static::findOne(['vendor_contact_email' => $email,'approve_status'=>'Yes']);
    }

    public function findById($email)
    {
        return Vendor::find()->select('vendor_id')->where(['vendor_contact_email' => $email])->one();
    }

    /**
    * Finds user by password reset token
    *
    * @param  string      $token password reset token
    * @return static|null
    */
    public function findByPasswordResetToken($token)
    {
        $expire = \Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        if ($timestamp + $expire < time()) {
            // token expired
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token
        ]);
    }

    /**
    * @inheritdoc
    */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
    * @inheritdoc
    */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
    * @inheritdoc
    */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
    * Validates password
    *
    * @param  string  $password password to validate
    * @return boolean if password provided is valid for current user
    */
    public function validatePassword($passwords)
    {
        if ($this->vendor_password) {
            return Yii::$app->getSecurity()->validatePassword($passwords, $this->vendor_password);
        } else {
            return false;
        }
    }

    /**
    * Generates password hash from password and sets it to the model
    *
    * @param string $password
    */
    public function setPassword($password)
    {
        $this->vendor_password = Yii::$app->getSecurity()->generatePasswordHash($this->vendor_password);
    }

    /**
    * Generates "remember me" authentication key
    */
    public function generateAuthKey()
    {
        $this->auth_key = Security::generateRandomKey();
    }

    /**
    * Generates new password reset token
    */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Security::generateRandomKey() . '_' . time();
    }

    /**
    * Removes password reset token
    */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
    /** EXTENSION MOVIE * */

    /* admin and vendor */
    public static function loadvendorname()
    {
        $vendorname= Vendor::find()
        ->where(['!=', 'vendor_status', 'Deactive'])
        ->andwhere(['!=', 'trash', 'Deleted'])
        ->all();
        $vendorname= \yii\helpers\ArrayHelper::map($vendorname,'vendor_id','vendor_name');
        return $vendorname;
    }

    public static function getVendor($arr='')
    {
        $session = Yii::$app->session;
        $query = Vendor::find()->where('vendor_contact_email = "'.$session['email'].'"')->one();
        return (isset($query["$arr"]))?$query["$arr"]:'';
    }

    public static function vendorManageBy($id){
        return self::findOne($id)->vendor_booking_managed_by;
    }


    /**
     * @inheritdoc
     * @return query\VendorQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new query\VendorQuery(get_called_class());
    }
}

