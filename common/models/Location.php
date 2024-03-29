<?php

namespace common\models;
use yii\helpers\ArrayHelper;
use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
/**
* This is the model class for table "{{%location}}".
*
* @property integer $id
* @property integer $country_id
* @property integer $city_id
* @property string $location
*
* @property City $city
* @property Category $country
*/
class Location extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = "Active";
    const STATUS_DEACTIVE = "Deactive";
    /**
    * @inheritdoc
    */
    public static function tableName()
    {
        return '{{%location}}';
    }

    /**
    * @inheritdoc
    */
    public function rules()
    {
        return [
            [['country_id', 'city_id', 'location',], 'required'],
            [['country_id', 'city_id'], 'integer'],
            [['location', 'location_ar'], 'string', 'max' => 50],
            [['cityName'], 'safe']
        ];
    }

    /**
    * @inheritdoc
    */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'country_id' => 'Country Name',
            'city_id' => 'Governorate',
            'cityName' => 'Governorate',
            'location' => 'Area',
            'location_ar' => 'Area - Arabic',
            'status'=>'Status',
        ];
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getCity()
    {
        return $this->hasOne(City::className(), ['city_id' => 'city_id']);
    }

    public function getCityName() 
    {
        if(Yii::$app->language == 'en')
        {
            return $this->city->city_name;
        }    
        else
        {
            return $this->city->city_name_ar;
        }
    }
 
    /**
    * @return \yii\db\ActiveQuery
    */
    public function getCountry()
    {
        return $this->hasOne(Country::className(), ['country_id' => 'country_id']);
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

    public static function loadlocation()
    {
        $location= Location::find()
        ->where(['!=', 'status', 'Deactive'])
        ->andwhere(['!=', 'trash', 'Deleted'])
        ->all();
        $location=ArrayHelper::map($location,'id','location');
        return $location;
    }

    public static function locationdetails($id)
    {
        $location= Location::find()
        ->where(['!=', 'trash', 'Deleted'])
        ->where(['=', 'id', $id])
        ->all();
        $location=ArrayHelper::map($location,'id','location');
        return $location;
    }

    public static function areaOptions(){
        
        $options = [];

        $cities = City::find()->where(['status'=>'Active', 'trash' => 'Default'])->all();

        foreach ($cities as $key => $value) { 
                       
            $areas = Location::find()
                ->where(['status'=>'Active', 'trash' => 'Default', 'city_id' => $value['city_id']])
                ->all();

            $child_options = [];

            foreach ($areas as $area) {

                if(Yii::$app->language == 'en') {
                    $child_options[$area->id] = $area->location;
                } else {
                    $child_options[$area->id] = $area->location_ar;
                }
            }            

            if(Yii::$app->language == 'en') {
                $options[$value->city_name] = $child_options;
            }else{
                $options[$value->city_name_ar] = $child_options;
            }
        }

        return $options;
    }

    public static function getlocation($id)
    {
        $model = Location::find()->where(['id'=>$id])->one();
        return $model->location;
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
     * @return query\LocationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new query\LocationQuery(get_called_class());
    }
}
