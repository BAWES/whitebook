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
    const STATUS_ACTIVE = "Active";
    const STATUS_DEACTIVE = "Deactive";
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
            [['iso_country_code', 'currency_code', 'currency_symbol'], 'string', 'max' => 10]
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


   /* 
    *
    *   To save created, modified user & date time 
    */
    public function beforeSave($insert)
    {
        if($this->isNewRecord)
        {
           $this->created_datetime = \yii\helpers\Setdateformat::convert(time(),'datetime');
           $this->created_by = \Yii::$app->user->identity->id;
        } 
        else {
           $this->modified_datetime = \yii\helpers\Setdateformat::convert(time(),'datetime');
           $this->modified_by = \Yii::$app->user->identity->id;
        }
           return parent::beforeSave($insert);
    }

  	public static function loadcountry()
  	{       
  			$country= Country::find()
  			->where(['!=', 'status', 'Deactive'])
  			->where(['!=', 'trash', 'Deleted'])
  			->all();
  			$country=ArrayHelper::map($country,'country_id','country_name');
  			return $country;
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
