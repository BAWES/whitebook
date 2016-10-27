<?php

namespace admin\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;
use admin\models\UserController;
use admin\models\Admin;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
* This is the model class for table "{{%access_control}}".
*
* @property integer $access_id
* @property string $role_id
* @property string $admin_id
* @property integer $created_by
* @property integer $modified_by
* @property string $created_datetime
* @property string $modified_datetime
*
* @property Admin $admin
* @property Role $role
*/
class AccessControlList extends \yii\db\ActiveRecord
{
    /**
    * @inheritdoc
    */
    public static function tableName()
    {
        return '{{%access_control}}';
    }


    /*
    *
    *   To save created, modified user & date time
    */
    public function behaviors()
    {
        return [
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
            // [['create','update','delete','manage','view'], 'required'],
            [['controller','method'], 'required'],
            [['role_id'], 'integer'],
            [['created_datetime', 'modified_datetime'], 'safe'],
        ];
    }

    /**
    * @inheritdoc
    */
    public function attributeLabels()
    {
        return [
            'access_id' => 'Access User',
            'role_id' => 'Roles',
            'controller' => 'Controller',
            'Method' => 'Method',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'created_datetime' => 'Created Datetime',
            'modified_datetime' => 'Modified Datetime',
        ];
    }

    //check db if current controller/method assigned to user role         
    public function can() {

        $user_id = Yii::$app->user->getId();

        //for first user 
        if($user_id == 1) {
            return true;
        }

        $user = Admin::findOne($user_id);

        if(!$user) {
            return false;
        }

        return AccessControlList::find()->where([
                'role_id' => $user->role_id,
                'controller' => Yii::$app->controller->id,
                'method' => Yii::$app->controller->action->id
            ])->count();
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getAdmin()
    {
        return $this->hasOne(Admin::className(), ['id' => 'admin_id']);
    }

    public static function getAdminName($id)
    {
        return Admin::find()
            ->select ('admin_name')
            ->where(['=', 'id', $id])
            ->one()
            ->admin_name;
    }

    public static function getControllerName($id)
    {
        return UserController::find()
            ->select ('controller')
            ->where(['=', 'id', $id])
            ->one()
            ->controller;        
    }

    public static function itemcontroller($ctrllist)
    {
        $k = explode(",", $ctrllist);

        $g = array();
        
        foreach ($k as $f)
        {
            $controller= UserController::find()
                ->select ('controller')
                ->where(['=', 'id', $f])
                ->one();
                
            $g[] = $controller;
        }
        
        $m = array();
        
        foreach ($g as $r)
        {
            $m[] = $r['controller'];
        }

        return implode(" , ",$m);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getRole()
    {
        return $this->hasOne(Role::className(), ['role_id' => 'role_id']);
    }
}
