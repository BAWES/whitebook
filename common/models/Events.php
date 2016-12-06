<?php

namespace common\models;

use Yii;
use yii\db\Expression;
use yii\db\ActiveRecord;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\helpers\StringHelper;

/**
* This is the model class for table "{{%events}}".
*
* @property integer $event_id
* @property integer $customer_id
* @property string $event_name
* @property string $event_date
* @property string $event_type
* @property string $created_date
*/
class Events extends \yii\db\ActiveRecord
{

    const EVENT_ALREADY_EXIST = -1;
    const EVENT_CREATED = 1;
    const EVENT_ADDED_SUCCESS = 1;

    /**
    * @inheritdoc
    */
    public static function tableName()
    {
        return '{{%events}}';
    }

    /**
    * @inheritdoc
    */
    public function rules()
    {
        return [
            [['customer_id', 'event_name', 'event_date', 'event_type'], 'required'],
            [['customer_id'], 'integer'],
            [['event_date'], 'safe'],
            [['event_name', 'event_type'], 'string', 'max' => 100],
        ];
    }

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
    public function attributeLabels()
    {
        return [
            'event_id' => 'Event ID',
            'customer_id' => 'Customer ID',
            'event_name' => 'Event Name',
            'event_date' => 'Event Date',
            'event_type' => 'Event Type',
        ];
    }

    public function beforeSave($insert)
    {
        if ($this->isNewRecord && empty($this->token)) {
            $this->token = Events::generateToken();
        }

        return parent::beforeSave($insert);
    }

    public function generateToken($length = 32)
    {
        $randomString = Yii::$app->getSecurity()->generateRandomString($length);
                
        $exist = Events::findOne(['token' => $randomString]);

        if(!$exist)
            return $randomString;
        else
            return $this->generateToken($length);
    }

    public function findByToken($token)
    {
        return Events::findByAttributes([
            'token' => $token,
        ]);    
    }
}
