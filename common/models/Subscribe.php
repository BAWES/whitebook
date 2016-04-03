<?php

namespace common\models;

use common\models\Subscribe;
use Yii;

/**
 * This is the model class for table "{{%subscribe}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property integer $created_by
 * @property string $modified_by
 * @property integer $created_datetime
 * @property string $modified_datetime
 */
class Subscribe extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%subscribe}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'email'], 'required'],
            [['email'],'email'],
            [['name', 'email'], 'string', 'max' => 230],
            [['email'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'email' => 'Email',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'created_datetime' => 'Created Datetime',
            'modified_datetime' => 'Modified Datetime',
        ];
    }
}
