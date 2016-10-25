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
* @property string $package_id
* @property string $image_id
* @property string $vendor_name
* @property string $vendor_return_policy
* @property string $vendor_public_email
* @property string $vendor_public_phone
* @property string $vendor_working_hours
* @property string $vendor_contact_name
* @property string $vendor_contact_email
* @property string $vendor_contact_number
* @property string $vendor_emergency_contact_name
* @property string $vendor_emergency_contact_email
* @property string $vendor_emergency_contact_number
* @property string $vendor_website
* @property string $package_start_date
* @property string $package_end_date
* @property string $vendor_password
* @property string $vendor_status
* @property integer $created_by
* @property integer $modified_by
* @property string $created_datetime
* @property string $modified_datetime
* @property string $trash
*
* @property Suborder[] $suborders
* @property Package $package
* @property Image $image
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
    public $pack; // vendor package
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
            [['package_id', 'vendor_name', 'vendor_name_ar', 'vendor_contact_name','vendor_password','vendor_contact_email','vendor_contact_number'], 'required'],
            [['vendor_name', 'vendor_name_ar', 'vendor_contact_name','vendor_password','vendor_contact_email','vendor_contact_number','confirm_password'], 'required', 'on'=>'register'],
            [['vendor_name', 'vendor_name_ar', 'vendor_contact_name'], 'required', 'on'=>'vendorprofile'],
            [['vendor_name', 'vendor_name_ar', 'vendor_contact_name','vendor_password','vendor_contact_email','vendor_contact_number'], 'required', 'on'=>'vendorUpdate'],
            [['package_id', 'created_by', 'modified_by'], 'integer'],
            [['vendor_return_policy','vendor_return_policy_ar', 'vendor_name', 'vendor_name_ar', 'vendor_contact_name',], 'string'],
            [['package_start_date', 'package_end_date', 'created_datetime', 'modified_datetime','pack'], 'safe'],
            [['package_end_date'], 'default', 'value' => null],
            [['vendor_website','vendor_facebook','vendor_twitter','vendor_instagram','vendor_googleplus'],'url', 'defaultScheme' => 'http'],
            /* Validation Rules */
            [['confirm_password'], 'compare', 'compareAttribute'=>'vendor_password','message'=>'Password and confirm password not same' ],
            [['vendor_contact_email'],'email'],
            ['vendor_contact_email', 'unique'],
            [['approve_status','vendor_name', 'vendor_name_ar', 'vendor_public_email','vendor_contact_name', 'vendor_public_phone','vendor_contact_email','vendor_contact_address', 'vendor_contact_address_ar' ,'vendor_emergency_contact_name', 'vendor_emergency_contact_email', 'vendor_emergency_contact_number','vendor_logo_path', 'vendor_bank_name', 'vendor_bank_branch', 'vendor_account_no', 'vendor_website', 'vendor_facebook', 'vendor_twitter', 'vendor_instagram', 'vendor_googleplus', 'vendor_skype'], 'string', 'max' => 128],
            ['vendor_logo_path', 'image', 'extensions' => 'png, jpg, jpeg','skipOnEmpty' => false,'on' => 'register'],
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['register'] = ['day_off', 'vendor_name', 'vendor_name_ar', 'vendor_password','confirm_password','vendor_contact_name','vendor_contact_email','vendor_contact_number','vendor_contact_address', 'vendor_contact_address_ar','vendor_return_policy','vendor_return_policy_ar', 'vendor_status','vendor_website','vendor_facebook','vendor_twitter','vendor_instagram','vendor_googleplus','vendor_skype','vendor_bank_name', 'vendor_bank_branch', 'vendor_account_no','vendor_fax','vendor_logo_path','short_description','short_description_ar'];//Scenario Values Only Accepted
        $scenarios['vendorUpdate'] = ['day_off', 'vendor_name', 'vendor_name_ar', 'vendor_contact_name','vendor_contact_email','vendor_contact_number','vendor_contact_address', 'vendor_contact_address_ar','vendor_return_policy','vendor_return_policy_ar', 'vendor_status','vendor_website','vendor_facebook','vendor_twitter','vendor_instagram','vendor_googleplus','vendor_skype','vendor_bank_name', 'vendor_bank_branch', 'vendor_account_no','vendor_fax','short_description','short_description_ar', 'vendor_logo_path'];//Scenario Values Only Accepted
        $scenarios['vendorprofile'] = ['day_off', 'vendor_logo_path', 'vendor_name', 'vendor_name_ar', 'vendor_contact_name','vendor_contact_email','vendor_contact_number','vendor_contact_address', 'vendor_contact_address_ar','vendor_working_hours', 'vendor_working_min', 'vendor_working_am_pm_from', 'vendor_working_hours_to','vendor_working_min_to', 'vendor_working_am_pm_to', 'vendor_return_policy','vendor_return_policy_ar', 'vendor_fax', 'short_description', 'short_description_ar', 'vendor_bank_name', 'vendor_bank_branch', 'vendor_account_no', 'vendor_public_phone', 'vendor_public_email', 'vendor_emergency_contact_email', 'vendor_website', 'vendor_facebook','vendor_twitter','vendor_instagram','vendor_googleplus','vendor_skype'];//Scenario Values Only Accepted
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
            'package_id' => 'Package Name',
            // 'commision' => 'Commision ( % )',
            'image_id' => 'Image ',
            'subcategory_id' => 'Sub category',
            'vendor_name' => 'Vendor Name',
            'vendor_name_ar' => 'Vendor Name - Arabic', 
            'vendor_contact_address' => 'Contact Address',
            'vendor_contact_address_ar' => 'Contact Address - Arabic',
            'vendor_return_policy' => 'Vendor Return Policy',
            'vendor_return_policy_ar' => 'Vendor Return Policy - Arabic',
            'vendor_public_email' => 'Vendor Public Email',
            'vendor_public_phone' => 'Vendor Public Phone',
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
            'vendor_facebook' => 'Vendor Facebook URL',
            'vendor_twitter' => 'Vendor Twitter URL',
            'vendor_instagram' => 'Vendor Instagram URL',
            'vendor_googleplus' => 'Vendor Google Plus URL',
            'vendor_skype' => 'Vendor Skype id',
            'package_start_date' => 'Package Start Date',
            'package_end_date' => 'Package End Date',
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
            'package_start_date'=> 'Vendor Start Date',
            'package_end_date'=> 'Vendor End Date',
            'approve_status'=>'Approve vendor',
            'vendor_logo_path'=>'vendor logo',

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
    public function getPackage()
    {
        return $this->hasOne(Package::className(), ['package_id' => 'package_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getImage()
    {
        return $this->hasOne(Image::className(), ['image_id' => 'image_id']);
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
        return  Yii::$app->getSecurity()->validatePassword($passwords, $this->vendor_password);

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

    public static function packageCheck($id, $check_vendor = false){

        // $check_vendor variable for frontend filter IMPORATNT
        $today=date('Y-m-d');

        $datetime = VendorPackages::find()->select(['DATE_FORMAT(package_start_date,"%Y-%m-%d") as package_start_date','DATE_FORMAT(package_end_date,"%Y-%m-%d") as package_end_date','vendor_id'])
        ->where(['vendor_id' => $id])
        ->asArray()
        ->all();


        $blocked_dates=array();
        if(!empty($datetime)){
            foreach ($datetime as $d)
            {
                $date = $date1 = $d['package_start_date'];
                $end_date = $end_date1 =$d['package_end_date'];

                while (strtotime($date) <= strtotime($end_date)) {
                    $blocked_dates[]=$date;
                    $date = date ("Y-m-d", strtotime("+1 day", strtotime($date)));
                }
            }

            $available = in_array($today, $blocked_dates);
            if($available)
            {
                /* if vendor package not expired */
                if($check_vendor !="")
                {
                    return $datetime[0]['vendor_id'];die;
                }
                return "1"; die;
            }
            else
            {

                return "0";die;
            }

        }
    }
}
