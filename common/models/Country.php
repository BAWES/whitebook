<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\db\ActiveRecord;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
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
            [['country_name', 'iso_country_code', 'currency_code', 'currency_symbol'], 'required'],
            [['country_name'], 'unique'],
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

    public static function loadcountry()
    {
        $country = Country::find()
            ->where(['!=', 'country_status', 'Deactive'])
            ->andWhere(['!=', 'trash', 'Deleted'])
            ->all();
        
        if(Yii::$app->language == 'en') {
            $country = ArrayHelper::map($country, 'country_id', 'country_name');
        } else {
            $country = ArrayHelper::map($country, 'country_id', 'country_name_ar');
        }
                
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
        return ($status == 'Active') ? 'Activate' : 'Deactivate';
    }
}
