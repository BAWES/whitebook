<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;
/**
 * This is the model class for table "{{%controller}}".
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
class Usercontroller extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%controller}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'controller'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'controller id',
            'controller' => 'Controller',
        ];
    }
    public static function loadcontroller($admin_id='',$role_id='')
    {
		if($admin_id && $role_id){
			$command = \Yii::$app->DB->createCommand('SELECT * FROM whitebook_controller where id NOT IN( select controller FROM whitebook_access_control where admin_id = '.$admin_id.' and role_id = '.$role_id.' )');
			$controller=$command->queryall();
		}else{
			$controller = Usercontroller::find()->all();
		}
		     
        $controller=ArrayHelper::map($controller,'id','controller');
        return $controller;
	}

}

