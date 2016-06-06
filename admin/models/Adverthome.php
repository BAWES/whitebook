<?php

namespace admin\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
/**
* This is the model class for table "{{%advert_home}}".
*
* @property string $advert_id
* @property string $advert_code
* @property integer $created_by
* @property integer $modified_by
* @property string $created_date
* @property string $modified_date
* @property string $trash
*/
class Adverthome extends \yii\db\ActiveRecord
{
    /**
    * @inheritdoc
    */
    public static function tableName()
    {
        return '{{%advert_home}}';
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
            [['advert_code'], 'required'],
            [['advert_code', 'trash'], 'string'],
            [['created_by', 'modified_by'], 'integer'],
            [['created_by', 'modified_by', 'created_datetime', 'modified_datetime', 'trash'], 'safe'],

        ];
    }

    /**
    * @inheritdoc
    */
    public function attributeLabels()
    {
        return [
            'advert_id' => 'Advertisement ID',
            'ads_type' => 'Home Advertisement ',
            'advert_code' => 'Advertisement Code',
            'ads_image' => 'Advertisement Image',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'created_datetime' => 'Created Date',
            'modified_datetime' => 'Modified Date',
            'trash' => 'Trash',
        ];
    }



}
