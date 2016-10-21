<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use common\models\City;
use common\models\Location;
use common\models\Vendor;

/**
* This is the model class for table "{{%vendor_location}}".
*
* @property integer $id
* @property integer $vendor_id
* @property string $city_id
* @property string $area_id
* @property string $created_datetime
* @property string $modified_datetime
*/
class VendorLocation extends \yii\db\ActiveRecord
{
    /**
    * @inheritdoc
    */
    public static function tableName()
    {
        return '{{%vendor_location}}';
    }

    /**
    * @inheritdoc
    */
    public function rules()
    {
        return [
            //[[''], 'required'],
            [['vendor_id'], 'integer'],
            [['delivery_price', 'created_datetime', 'modified_datetime'], 'safe'],
            [['city_id', 'area_id'], 'integer']
        ];
    }

    /**
    * @inheritdoc
    */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'vendor_id' => 'Vendor ID',
            'city_id' => 'City ID',
            'area_id' => 'Area ID',
            'created_datetime' => 'Created Datetime',
            'modified_datetime' => 'Modified Datetime',
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

    # relation with location table
    public function getLocation()
    {
        return $this->hasOne(Location::className(), ['id' => 'area_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getVendor()
    {
        return $this->hasOne(Vendor::className(), ['vendor_id' => 'vendor_id']);
    }

    # relation with city table
    public function getCity()
    {
        return $this->hasOne(City::className(), ['city_id' => 'city_id']);
    }

    # fetch city name on base of language
    public function getCityName()
    {
        if(Yii::$app->language == 'en') {
            return isset($this->city->city_name) ? $this->city->city_name : $this->city_id;
        } else{
            return isset($this->city->city_name_ar) ? $this->city->city_name_ar : $this->city_id;
        }
    }

    # fetch location name on base of language
    public function getLocationName()
    {
        if(Yii::$app->language == 'en') {
            return isset($this->location->location) ? $this->location->location : $this->area_id;
        } else{
            return isset($this->location->location_ar) ? $this->location->location_ar : $this->area_id;
        }
    }
}
