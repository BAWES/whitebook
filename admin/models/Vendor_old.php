<?php
namespace admin\models;

use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\web\IdentityInterface;
use yii\db\BaseActiveRecord;
use yii\helpers\Security;
use yii\helpers\ArrayHelper;
use yii\behaviors\SluggableBehavior;
/**
 * This is the model class for table "{{%vendor}}".
 *
 * @property string $vendor_id
 * @property string $package_id
 * @property string $image_id
 * @property string $vendor_name
 * @property string $vendor_brief
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
 * @property string $vendor_delivery_charge
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
    public $auth_key;
    public $password_hash;
    public $password_reset_token;
//  public $vendor_contact_address;
    public $area_id;
    public $confirm_password;
    public $pack; // vendor package
   // private $_user = false;
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
          ];
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id','package_id', 'vendor_name','vendor_contact_name','vendor_password','vendor_contact_email','vendor_contact_number', 'vendor_contact_address'], 'required'],
            [['category_id','vendor_name','vendor_contact_name','vendor_password','vendor_contact_email','vendor_contact_number','vendor_contact_address','confirm_password'], 'required', 'on'=>'register'],
            [['category_id','vendor_name','vendor_contact_name'], 'required', 'on'=>'vendorprofile'],
            [['category_id','vendor_name','vendor_contact_name','vendor_password','vendor_contact_email','vendor_contact_number', 'vendor_contact_address'], 'required', 'on'=>'vendorUpdate'],
          //  [['commision'], 'number', 'numberPattern' => '/^\s*[-+]?[0-9]*[.,]?[0-9]+([eE][-+]?[0-9]+)?\s*$/'],
            [['package_id', 'created_by', 'modified_by'], 'integer'],
            [['vendor_brief', 'vendor_return_policy', 'vendor_name','vendor_contact_name',], 'string'],
            [['package_start_date', 'package_end_date', 'created_datetime', 'modified_datetime','pack'], 'safe'],
            [['package_end_date'], 'default', 'value' => null],
            [['vendor_delivery_charge'], 'number'],
        //    [['vendor_password'], 'required', 'on' => 'change'],
        //    [['vendor_logo_path'], 'safe', 'on' => 'vendorUpdate'],
            [['vendor_website','vendor_facebook','vendor_twitter','vendor_instagram','vendor_googleplus'],'url', 'defaultScheme' => 'http'],

            /* Validation Rules */
            [['confirm_password'], 'compare', 'compareAttribute'=>'vendor_password','message'=>'Password and confirm password not same' ],
            [['vendor_contact_email'],'email'],
            ['vendor_contact_email', 'unique'],
                       // [['vendor_contact_name'],'string'],
         //   [['vendor_contact_email','vendor_contact_name'],'unique'],
         //   [['vendor_contact_number','vendor_emergency_contact_number'],'match', 'pattern' => '/^[0-9+ -]+$/','message' => 'Phone number accept only numbers and +,-'],
            [['approve_status','vendor_name', 'vendor_public_email','vendor_contact_name', 'vendor_public_phone','vendor_contact_email','vendor_contact_address','vendor_emergency_contact_name', 'vendor_emergency_contact_email', 'vendor_emergency_contact_number','vendor_logo_path', 'vendor_bank_name', 'vendor_bank_branch', 'vendor_account_no', 'vendor_website', 'vendor_facebook', 'vendor_twitter', 'vendor_instagram', 'vendor_googleplus', 'vendor_skype'], 'string', 'max' => 128],
            //['vendor_logo_path', 'image', 'extensions' => 'png, jpg, jpeg','skipOnEmpty' => false,'minWidth' => 100, 'maxWidth' => 300,'minHeight' => 80, 'maxHeight' =>400,'on' => 'register'],
            ['vendor_logo_path', 'image', 'extensions' => 'png, jpg, jpeg','skipOnEmpty' => false,'on' => 'register'],
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['register'] = ['category_id','vendor_name','vendor_password','confirm_password','vendor_contact_name','vendor_contact_email','vendor_contact_number','vendor_contact_address','vendor_return_policy','vendor_status','vendor_website','vendor_facebook','vendor_twitter','vendor_instagram','vendor_googleplus','vendor_skype','vendor_bank_name', 'vendor_bank_branch', 'vendor_account_no','vendor_fax','vendor_logo_path','short_description'];//Scenario Values Only Accepted
        $scenarios['vendorUpdate'] = ['category_id','vendor_name','vendor_contact_name','vendor_contact_email','vendor_contact_number','vendor_contact_address','vendor_return_policy','vendor_status','vendor_website','vendor_facebook','vendor_twitter','vendor_instagram','vendor_googleplus','vendor_skype','vendor_bank_name', 'vendor_bank_branch', 'vendor_account_no','vendor_fax','short_description','vendor_logo_path'];//Scenario Values Only Accepted

        $scenarios['change'] = ['vendor_password'];//Scenario Values Only Accepted
        return $scenarios;
    }
        /*public  function mailvalidation($attribute_name,$params)
    {
        echo '11';die;
        if(!empty($this->vendor_contact_email) ){
        $model = Vendor::find()
        ->where(['vendor_contact_email'=>$this->vendor_contact_email])
        ->one();
        if($model){
        $this->addError('vendor_contact_email','Please enter a unique contact email');
        }
        }
    }*/

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
            'category_id' => 'Category',
            'subcategory_id' => 'Sub category',
            'vendor_name' => 'Vendor Name',
            'vendor_brief' => 'Vendor Brief',
            'vendor_return_policy' => 'Vendor Return Policy',
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
            'vendor_delivery_charge' => 'Vendor Delivery Charge',
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVendorItems()
    {
        return $this->hasMany(VendorItem::className(), ['vendor_id' => 'vendor_id']);
    }

    /*
     *  Relation functions END here
     */



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

/* removed
    public static function findIdentityByAccessToken($token)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }
*/
    /**
     * Finds user by username
     *
     * @param  string      $username
     * @return static|null
     */
    public static function findByUsername($email)
    {
        return static::findOne(['vendor_contact_email' => $email,'approve_status'=>'Yes']);
    }


    public static function Vendorblockeddays($id)
    {
        $result= Vendor::find()->select('blocked_days')->where(['vendor_id' => $id,'approve_status' => 'Yes'])->one();
    if($result){
        return $result;
    }
    else{
        return 0;
    }
    }



    public static function statusCheck($id){
    $result= Vendor::find()->select('vendor_id')->where(['vendor_id' => $id,'vendor_status' => 'Active'])->one();
    if($result){
        return 1;
    }
    else{
        return 0;
    }


        }
    public static function packageCheck($id, $check_vendor = false){

        // $check_vendor variable for frontend filter IMPORATNT
        $today=date('Y-m-d');
        $datetime = Yii::$app->db->createCommand('SELECT vendor_id, DATE_FORMAT(package_start_date,"%Y-%m-%d") as package_start_date ,DATE_FORMAT(package_end_date,"%Y-%m-%d") AS package_end_date FROM whitebook_vendor_packages where  vendor_id='.$id);
        $datetime = $datetime->queryAll();

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

    public static function loadvendorname()
    {
        $vendorname= Vendor::find()
        ->where(['!=', 'vendor_status', 'Deactive'])
        ->andwhere(['!=', 'trash', 'Deleted'])
        ->all();
        $vendorname=ArrayHelper::map($vendorname,'vendor_id','vendor_name');
        return $vendorname;
    }
        public static function statusImageurl($status)
    {
        if($status == 'Active')
        return \Yii::$app->params['appImageUrl'].'active.png';
        return \Yii::$app->params['appImageUrl'].'inactive.png';
    }

    public static function getVendor_packagedate($id)
    {
        $id = 1;  // id for testing // check while dynamic

        $datetime = Yii::$app->db->createCommand('SELECT DATE_FORMAT(package_start_date,"%Y-%m-%d") as package_start_date ,DATE_FORMAT(package_end_date,"%Y-%m-%d") AS package_end_date FROM whitebook_vendor_packages where  vendor_id='.$id);


         $datetime = $datetime->queryAll();
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
    }
    $max = max(array_map('strtotime', $blocked_dates));
return date('d-m-Y', $max);die;
die;


    }
    public static function getVendor($arr='')
    {
        $session = Yii::$app->session;
        $query = Vendor::find()->where('vendor_contact_email = "'.$session['email'].'"')->one();
        return (isset($query["$arr"]))?$query["$arr"]:'';
    }


    public static function getvendorname($id){
        $vendorname= Vendor::find()
            ->where(['vendor_id'=>$id])
            ->all();
            $vendorname=ArrayHelper::map($vendorname,'vendor_id','vendor_name');
            return $vendorname;
    }
    // Pass vendor name to frontend
      /*  public static function vendorname($id){
        $vendorname= Vendor::find()
            ->select(['vendor_name'])
            ->where(['vendor_id'=>$id])
            ->one();
            return $vendorname['vendor_name'];
        }
        */
        // Pass vendor slug to frontend
        public static function vendorslug($id){
        $vendorname= Vendor::find()
            ->select(['vendor_name','slug'])
            ->where(['vendor_id'=>$id])
            ->one();
            return $vendorname;
        }
        // Pass vendor contact address to frontend
        public static function vendorcontactaddress($id){
        $vendordetail= Vendor::find()
            ->select(['vendor_contact_address','vendor_contact_number'])
            ->where(['vendor_id'=>$id])
            ->one();
            return $vendordetail;
        }

     // Pass vendor social details to frontend
        public static function sociallist($id){
        $socialdetail= Vendor::find()
            ->select(['vendor_facebook','vendor_twitter','vendor_instagram','vendor_googleplus','vendor_contact_email'])
            ->where(['vendor_id'=>$id])
            ->one();
            return $socialdetail;
        }

             public static function vendorcount()
    {
        return Vendor::find()->where(['trash' => 'Default'])->count();
    }
         public static function vendormonthcount()
    {
        //echo $date=date('d');die;
        $month=date('m');
        $year=date('Y');
        return  Vendor::find()
        ->where(['MONTH(created_datetime)' => $month])
        ->andwhere(['YEAR(created_datetime)' => $year])
        ->count();
    }
     public static function vendordatecount()
    {
        $date=date('d');
        $month=date('m');
        $year=date('Y');
        return  Vendor::find()
        ->where(['MONTH(created_datetime)' => $month])
        ->andwhere(['YEAR(created_datetime)' => $year])
        ->andwhere(['DAYOFMONTH(created_datetime)' => $date])
        ->count();
    }

         public static function vendorperiod()
    {
        $contractDateBegin=date('Y-m-d');
        $date = strtotime(date("Y-m-d", strtotime($contractDateBegin)) . " +60 days");
        $contractDateEnd = date('Y-m-d',$date);
        $sql="SELECT * FROM whitebook_vendor WHERE `package_end_date` >= '".$contractDateBegin."' AND`package_end_date` >= '".$contractDateBegin."' AND `package_end_date` <=  '".$contractDateEnd."'";
        $command = Yii::$app->db->createCommand($sql);
        $period=$command->queryall();
        return  $period;
    }

         public static function vendorexpiry()
    {

        for ($x = 1; $x <= 5; $x++) {
        echo "The number is: $x <br>";
        }
        $year = date("Y");

        $previousyear = $year -1;
        $contractDateBegin=date('Y-m-d');
        $date = strtotime(date("Y-m-d", strtotime($contractDateBegin)) . " +60 days");
        $contractDateEnd = date('Y-m-d',$date);
        $sql="SELECT * FROM whitebook_vendor WHERE `package_end_date` >= '".$contractDateBegin."' AND `package_end_date` <=  '".$contractDateEnd."'";
        $command = Yii::$app->db->createCommand($sql);
        $period=$command->queryall();
        return  $period;
    }

    public static function Commision()
    {
        $model_siteinfo = Siteinfo::find()->all();
        foreach($model_siteinfo as $key=>$val)
        {
            return $first_id = $val['commision'];
        }
    }

    /* load vendor details for front end */
    public static function loadvendornames()
    {
            $vendorname= Vendor::find()
            ->where(['!=', 'vendor_status', 'Deactive'])
            ->andwhere(['!=', 'trash', 'Deleted'])
            ->asArray()
            ->all();
            return $vendorname;
    }

    public static function loadvalidvendors()
    {
        /* STEP 1 GET ACTIVE VENDORS*/
      //  $vendor = Vendor::loadvendornames();
        $vendor = Yii::$app->db->createCommand('Select DISTINCT wv.vendor_id, wvi.vendor_id from whitebook_vendor as wv LEFT JOIN whitebook_vendor_item as wvi ON wv.vendor_id = wvi.vendor_id where wv.vendor_status ="Active" AND wv.trash ="Default" AND wvi.trash="Default" AND wvi.item_status ="Active" AND wvi.item_for_sale = "Yes" AND wvi.item_approved = "Yes"')->queryAll();

        /* STEP 2 CHECK PACKAGE */
        foreach ($vendor as $key => $value) {
            $package[] = Vendor::packageCheck($value['vendor_id'],$check_vendor="Notempty");
        }

        return $active_vendors = implode('","', array_filter($package));
    }
    public static function loadvendor_item($item)
    {
				$k=array();
		foreach ($item as $data){
		$k[]=$data;
		}
		$id = implode("','", $k);
		$val = "'".$id."'";
        /* STEP 1 GET ACTIVE VENDORS*/
      //  $vendor = Vendor::loadvendornames();
        $vendor = Yii::$app->db->createCommand('Select DISTINCT wv.vendor_id,wv.vendor_name,wv.slug  from whitebook_vendor as wv LEFT JOIN whitebook_vendor_item as wvi ON wv.vendor_id = wvi.vendor_id where wv.vendor_status ="Active" AND wv.trash ="Default" AND wvi.trash="Default" AND wvi.item_status ="Active" AND wvi.item_for_sale = "Yes" AND wvi.item_approved = "Yes" AND wvi.item_id IN('.$val.')')->queryAll();
        //echo '<pre>';//print_r ( $vendor);die;
        /* STEP 2 CHECK PACKAGE */
        foreach ($vendor as $key => $value) {
			//echo $value['vendor_id'];
            $package[] = Vendor::packageCheck($value['vendor_id'],$check_vendor="Notempty");
        }
        $active_vendors = implode('","', array_filter($package));
        $query = Vendor::find()
        ->select(['vendor_id','slug','vendor_name'])
        ->where('vendor_id IN ("'.$active_vendors.'")')->asArray()->all();
		return ($query);
      //
    }

    public static function loadvalidvendorids($cat_id=false)
    {
        /* STEP 1 GET ACTIVE VENDORS*/
      //  $vendor = Vendor::loadvendornames();
        $vendor = Yii::$app->db->createCommand('Select DISTINCT wv.vendor_id, wvi.vendor_id from
        whitebook_vendor as wv LEFT JOIN whitebook_vendor_item as wvi ON wv.vendor_id = wvi.vendor_id
        where wv.vendor_status ="Active" AND wv.trash ="Default" AND wvi.trash="Default"
        AND wvi.item_status ="Active" AND wvi.item_for_sale = "Yes" AND
        wvi.item_approved = "Yes" AND wvi.type_id=2 AND wvi.category_id='.$cat_id)->queryAll();

        $package = array();
        /* STEP 2 CHECK PACKAGE */
        foreach ($vendor as $key => $value) {
            $package[] = Vendor::packageCheck($value['vendor_id'],$check_vendor="Notempty");
        }
        if($package =='')
        {
            return '';
        }
        return $active_vendors = implode('","', array_filter($package));
    }

    /* Load who vendor having category */
    public static function Vendorcategories($slug){
        $vendor_category = Vendor::find()
            ->select(['category_id'])
            ->where(['slug'=>$slug])
            ->asArray()
            ->one();
            return $vendor_category;
        }
    public static function Vendorid_item($slug){
        $vendor_category = Vendor::find()
            ->select(['vendor_id'])
            ->where(['slug'=>$slug])
            ->asArray()
            ->one();
            return $vendor_category;
        }
}
