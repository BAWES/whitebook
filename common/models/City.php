<?php

namespace common\models;

use yii\helpers\ArrayHelper;
use Yii;
use yii\db\Query;
use yii\db\ActiveRecord;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
* This is the model class for table "city".
*
* @property integer $city_id
* @property integer $country_id
* @property string $city_name
* @property string $city_status
*
* @property Country $country
*/
class City extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = "Active";
    const STATUS_DEACTIVE = "Deactive";
    /**
    * @inheritdoc
    */
    public static function tableName()
    {
        return '{{%city}}';
    }

    /**
    * @inheritdoc
    */
    public function rules()
    {
        return [
            [['country_id', 'city_name'], 'required'],
            [['country_id'], 'integer'],
            [['city_name', 'city_name_ar','status'], 'string', 'max' => 100]
        ];
    }

    public static function loadcity()
    {
        $city = City::find()
            ->where(['!=', 'status', 'Deactive'])
            ->andwhere(['!=', 'trash', 'Deleted'])
            ->all();

        return ArrayHelper::map($city, 'city_id', 'city_name');
    }

    /**
    * @inheritdoc
    */
    public function attributeLabels()
    {
        return [
            'city_id' => 'Governorate ID',
            'country_id' => 'Country Name',
            'city_name' => 'Governorate',
            'city_name_ar' => 'Governorate - Arabic',
            'status' => 'Governorate Status',
        ];
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
    * @return \yii\db\ActiveQuery
    */
    public function getCountry()
    {
        return $this->hasOne(Country::className(), ['country_id' => 'country_id']);
    }

    public function getLocations(){
        return $this->hasMany(Location::className(), ['city_id' => 'city_id']);
    }

    public function statusImageurl($img_status)
    {
        if($img_status == 'Active')
            return \yii\helpers\Url::to('@web/uploads/app_img/active.png');
        return \yii\helpers\Url::to('@web/uploads/app_img/inactive.png');
    }

    // Status Image title
    public function statusTitle($status)
    {
        return ($status == 'Active') ? 'Activate' : 'Deactivate';
    }

    /**
     * @inheritdoc
     * @return CityQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\CityQuery(get_called_class());
    }
}
