<?php
namespace admin\models;

use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\web\IdentityInterface;
use admin\models\Role;
use yii\db\BaseActiveRecord;
use yii\helpers\Security;
use yii\helpers\ArrayHelper;
use admin\models\Accesscontroller;
use yii\behaviors\SluggableBehavior;

/**
 * This is the model class for table "{{%admin}}".
 *
 * @property string $id
 * @property string $role_id
 * @property string $admin_name
 * @property string $admin_email
 * @property string $admin_password
 * @property string $admin_status
 * @property string $admin_last_login
 * @property integer $created_by
 * @property integer $modified_by
 * @property string $created_date
 * @property string $modified_date
 */
class Admin extends \yii\db\ActiveRecord implements IdentityInterface
{
	public $auth_key;
	public $password_hash;
	public $password_reset_token;
	public $file;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%admin}}';
    }

    public function behaviors()
    {
        return parent::behaviors();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['role_id', 'admin_status', 'admin_name', 'admin_email', 'admin_password','address','phone' ], 'required'],
            [['role_id'], 'integer'],
            [['admin_email'],'email'],
            [['created_datetime', 'modified_datetime','created_by','modified_by','admin_last_login','trash','address','phone'], 'safe'],
            [['role_id', 'admin_status', 'admin_name', 'admin_email'], 'required','on' => 'profile'],
            [['admin_password'], 'required', 'on' => 'change'],
            ['phone','match', 'pattern' => '/^[0-9+ -]+$/','message' => 'Phone number accept only numbers and +,-']
          //  [['admin_status'], 'string', 'max' => 25]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'role_id' => 'Role',
            'admin_name' => ' Username',
            'admin_email' => ' Email',
            'admin_password' => 'Password',
            'admin_status' => 'Status',
        ];
    }

    public function scenarios()
    {
		$scenarios = parent::scenarios();
		$scenarios['change'] = ['admin_password'];//Scenario Values Only Accepted
        $scenarios['profile'] = ['admin_name', 'admin_email','address','phone'];//Scenario Values Only Accepted
        return $scenarios;
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
     * @param  string      $username
     * @return static|null
     */
    public static function findByUsername($email)
    {
        return static::findOne(['admin_email' => $email,'admin_status'=>'Active']);
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
          return  Yii::$app->getSecurity()->validatePassword($passwords, $this->admin_password);

    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->admin_password = Yii::$app->getSecurity()->generatePasswordHash($this->admin_password);
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

    public function getRoledata(){
        return $this->hasOne(\admin\models\Role::className(), ['role_id' => 'role_id']);
    }
   
	public static function Roles()
    {
		$roles = Role::find()->all();
        $role=ArrayHelper::map($roles,'role_id','role_name');
        return $role;
	}


	public static function Accessroles()
    {
		$role = Role::find()
		->select(['role_id','role_name'])
		->asArray()
		->all();
        $roles=ArrayHelper::map($role,'role_id','role_name');
        return $roles;
	}

	public static function Admin()
    {
		$accessdata = Accesscontroller::find()
		->select(['admin_id'])
		->groupBy('admin_id')
		 ->asArray()
		->all();
		for($i=0; $i<count($accessdata); $i++)
		{
			$data[]=$accessdata[$i]['admin_id'];
		}

		$data=implode(',',$data);
		$admin=Admin::find()
		->select(["CONCAT(CAST(id as CHAR),'_', CAST(role_id as CHAR)) AS id",'admin_name'])
		->where(['admin_status'=>'Active'])
		->andwhere(['not in','id',$data])
		->asArray()
		->all();
		
		return $admin=ArrayHelper::map($admin,'id','admin_name');
	}

		public static function Adminupdate()
    {
		$admin=Admin::find()
		->select(["CONCAT(CAST(id as CHAR),'_', CAST(role_id as CHAR)) AS id",'admin_name'])
		->where(['admin_status'=>'Active'])
		->asArray()
		->all();
		$admin=ArrayHelper::map($admin,'id','admin_name');
        return $admin;
	}


    public static function getAdmin($arr='')
	{
		$session = Yii::$app->session;
		$query = Admin::find()->where('admin_email = "'.$session['email'].'"')->one();
		return (isset($query["$arr"]))?$query["$arr"]:'';
	}
}
