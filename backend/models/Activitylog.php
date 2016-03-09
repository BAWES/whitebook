<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%activity_log}}".
 *
 * @property string $log_id
 * @property string $log_user_id
 * @property string $log_user_type
 * @property string $log_action
 * @property string $log_datetime
 */
class Activitylog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%activity_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['log_user_id', 'log_username','log_action', 'log_datetime'], 'required'],
            [['log_user_id'], 'integer'],
            [['log_user_type', 'log_action'], 'string'],
            [['log_datetime','log_ip'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'log_id' => 'Log ID',
            'log_user_type' => 'User Type',
            'log_username' => 'Username',
            'log_user_id' => 'User Id',            
            'log_action' => 'Action',
            'log_datetime' => 'Date and time',
            'log_ip' => 'IP address',
        ];
    }
    
    //Gridview Status Filter
	public static function Usertype()
	{
		return $status = ['admin' => 'admin', 'vendor' => 'vendor'];
	}
	
}
