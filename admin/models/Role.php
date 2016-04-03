<?php

namespace admin\models;

use Yii;

/**
 * This is the model class for table "{{%role}}".
 *
 * @property string $role_id
 * @property string $role_name
 * @property integer $created_by
 * @property integer $modified_by
 * @property string $created_datetime
 * @property string $modified_datetime
 * @property string $trash
 */
class Role extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%role}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['role_name'], 'required'],
            [['created_by', 'modified_by'], 'integer'],
            [['modified_by', 'created_datetime', 'modified_datetime', 'modified_datetime'], 'safe'],
            [['trash'], 'string'],
            [['role_name'], 'string', 'max' => 128]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'role_id' => 'Role ID',
            'role_name' => 'Role Name',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'created_datetime' => 'Created Datetime',
            'modified_datetime' => 'Modified Datetime',
            'trash' => 'Trash',
        ];
    }
            public static function getRoleName($id)
	{
			$rolename= Role::find()
			->select ('role_name')
			->where(['=', 'role_id', $id])
			->one();
			return ($rolename['role_name']);
	}

}
