<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;


/**
* This is the model class for table "whitebook_customer_address".
*
* @property string $address_id
* @property string $customer_id
* @property string $address_type_id
* @property integer $country_id
* @property integer $city_id
* @property string $area_id
* @property string $address_archived
* @property integer $created_by
* @property integer $modified_by
* @property string $created_datetime
* @property string $modified_datetime
* @property string $trash
*
* @property Customer $customer
* @property Area $area
* @property AddressType $addressType
* @property CustomerAddressResponse[] $customerAddressResponses
* @property SuborderItemPurchase[] $suborderItemPurchases
*/
class CustomerAddress extends \yii\db\ActiveRecord
{
    /**
    * @inheritdoc
    */
    public static function tableName()
    {
        return '{{%customer_address}}';
    }

    /**
    * @inheritdoc
    */
    public function rules()
    {
        return [
            [['country_id', 'city_id'], 'required'],
            [['customer_id', 'address_type_id', 'country_id', 'city_id', 'area_id', 'created_by', 'modified_by'], 'integer'],
            [['address_archived', 'trash'], 'string'],
            [['customer', 'address_name', 'address_data', 'created_datetime', 'modified_datetime'], 'safe']
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
    * @inheritdoc
    */
    public function attributeLabels()
    {
        return [
            'address_id' => Yii::t('frontend', 'Address ID'),
            'address_name' => Yii::t('frontend', 'Address name'),
            'customer_id' => Yii::t('frontend', 'Customer name'),
            'address_type_id' => Yii::t('frontend', 'Address type'),
            'country_id' => Yii::t('frontend', 'Country name'),
            'city_id' => Yii::t('frontend', 'Governorate'),
            'area_id' => Yii::t('frontend', 'Area name'),
            'address_archived' => Yii::t('frontend', 'Delete'),
            'address_data' =>  Yii::t('frontend', 'Address Data'),
            'created_by' => Yii::t('frontend', 'Created By'),
            'modified_by' => Yii::t('frontend', 'Modified By'),
            'created_datetime' => Yii::t('frontend', 'Created Datetime'),
            'modified_datetime' => Yii::t('frontend', 'Modified Datetime'),
            'trash' => Yii::t('frontend', 'Trash')
        ];
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getCustomer()
    {
        return $this->hasOne(Customer::className(), ['customer_id' => 'customer_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getArea()
    {
        return $this->hasOne(Area::className(), ['area_id' => 'area_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getAddressType()
    {
        return $this->hasOne(AddressType::className(), ['type_id' => 'address_type_id']);
    }

    public function getLocation()
    {
        return $this->hasOne(Location::className(), ['id' => 'area_id']);
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
    public function getCustomerAddressResponses()
    {
        return $this->hasMany(CustomerAddressResponse::className(), ['address_id' => 'address_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getSuborderItemPurchases()
    {
        return $this->hasMany(SuborderItemPurchase::className(), ['address_id' => 'address_id']);
    }    
}
