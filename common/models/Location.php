<?php

namespace common\models;
use yii\helpers\ArrayHelper;
use Yii;

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
            [['location'], 'string', 'max' => 50]
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
            'city_id' => 'City Name',
            'location' => 'Area',
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(Country::className(), ['country_id' => 'country_id']);
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
    if($status == 'Active')     
        return 'Activate';
        return 'Deactivate';
    }
}
