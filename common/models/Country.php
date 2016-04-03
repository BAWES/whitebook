<?php

namespace common\models;

use Yii;

use yii\helpers\ArrayHelper;

use yii\behaviors\SluggableBehavior;
/**
 * This is the model class for table "country".
 *
 * @property integer $country_id
 * @property string $country_name
 * @property string $iso_country_code
 * @property string $currency_code
 * @property string $currency_symbol
 * @property string $country_status
 * @property integer $default
 */
class Country extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%country}}';
    }

    public function behaviors()
      {
          return [
              [
                  'class' => SluggableBehavior::className(),
                  'attribute' => 'country_name',                 
              ],
          ];
      }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['country_name', 'iso_country_code', 'currency_code', 'currency_symbol'], 'required'],
            [['default'], 'integer'],
            [['country_name'], 'string', 'max' => 100],
            [['iso_country_code', 'currency_code', 'currency_symbol'], 'string', 'max' => 10],
            [['country_status'], 'string', 'max' => 5]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'country_id' => 'Country ID',
            'country_name' => 'Country Name',
            'iso_country_code' => 'ISO country code',
            'currency_code' => 'Currency Code',
            'currency_symbol' => 'Currency Symbol',
            'country_status' => 'Country Status',
            'default' => 'Default',
        ];
    }
    
    public static function statusImageurl($status)
	{			
		if($status == 'Active')		
		return \yii\helpers\Url::to('@web/uploads/app_img/active.png');
		return \yii\helpers\Url::to('@web/uploads/app_img/inactive.png');
	}
	
	
	public static function loadcountry()
	{       
			$Country= Country::find()
			->where(['!=', 'status', 'Deactive'])
			->where(['!=', 'trash', 'Deleted'])
			->all();
			$Country=ArrayHelper::map($Country,'country_id','country_name');
			return $Country;
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
