<?php

namespace admin\models;

use Yii;
use common\models\City;

/**
 * This is the model class for table "whitebook_customer_address".
 *
 * @property string $address_id
 * @property string $customer_id
 * @property string $address_type_id
 * @property integer $country_id
 * @property integer $city_id
 * @property string $area_id
 * @property string $address_data
 * @property string $address_archived
 * @property integer $created_by
 * @property integer $modified_by
 * @property string $created_datetime
 * @property string $modified_datetime
 * @property string $trash
 */
class CustomerAddress extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'whitebook_customer_address';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'address_type_id', 'country_id', 'city_id', 'area_id', 'address_data', 'created_by', 'modified_by', 'created_datetime', 'modified_datetime'], 'required'],
            [['customer_id', 'address_type_id', 'country_id', 'city_id', 'area_id', 'created_by', 'modified_by'], 'integer'],
            [['address_data', 'address_archived', 'trash'], 'string'],
            [['customer_name', 'type_name', 'city_name', 'created_datetime', 'modified_datetime'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'address_id' => 'Address ID',
            'customer_id' => 'Customer ID',
            'address_type_id' => 'Address Type ID',
            'country_id' => 'Country ID',
            'city_id' => 'City ID',
            'area_id' => 'Area ID',
            'address_data' => 'Address Data',
            'address_archived' => 'Address Archived',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'created_datetime' => 'Created Datetime',
            'modified_datetime' => 'Modified Datetime',
            'trash' => 'Trash',
        ];
    }

    public function getType()
    {
        return $this->hasOne(Addresstype::className(), ['type_id' => 'address_type_id']);
    }

    public function getCustomer()
    {
        return $this->hasOne(Customer::className(), ['customer_id' => 'customer_id']);
    }
     
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::className(), ['city_id' => 'city_id']);
    }
}
