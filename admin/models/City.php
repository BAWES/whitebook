<?php

namespace admin\models;


use yii\helpers\ArrayHelper;
use Yii;
use yii\db\Query;

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
            [['city_name'], 'string', 'max' => 100],
        ];
    }

        	public static function loadcity()
	{
			$city= City::find()
			->where(['!=', 'status', 'Deactive'])
			->andwhere(['!=', 'trash', 'Deleted'])
			->all();
			$city=ArrayHelper::map($city,'city_id','city_name');
			return $city;
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
            'status' => 'Governorate Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public static function getCountryName($id)
    {
		$model = Country::find()->where(['country_id'=>$id])->one();
        return $model->country_name;
    }
    public static function getCityname($id)
    {
		$model = City::find()->where(['city_id'=>$id])->one();
        return $model->city_name;
    }

        public static function listcityname($id)
    {
		$model = City::find()
		->select(['city_id','city_name'])
		->where(['country_id'=>$id])->all();
		$city=ArrayHelper::map($model,'city_id','city_name');
		return $city;
    }
        public static function fullcityname()
    {
		$model = City::find()
		->select(['city_id','city_name'])
		->all();
		$city=ArrayHelper::map($model,'city_id','city_name');
		return $city;
    }


    public static function statusImageurl($status)
	{
		if($status == 'Active')
		return \Yii::$app->params['appImageUrl'].'active.png';
		return \Yii::$app->params['appImageUrl'].'inactive.png';
	}

    public static function getStatus($status)
    {
		$query = new Query;
		$query->select('name')
			  ->from('whitebook_status1')
			  ->where(['status' => $status]);
		$command = $query->createCommand();
		$data = $command->queryOne();
		return $data['name'];
    }
}
