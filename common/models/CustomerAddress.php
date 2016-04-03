<?php

namespace common\models;

use Yii;

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
        return 'whitebook_customer_address';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['address_type_id', 'country_id', 'city_id', 'area_id'], 'required'],
            [['customer_id', 'address_type_id', 'country_id', 'city_id', 'area_id', 'created_by', 'modified_by'], 'integer'],
            [['address_archived', 'trash'], 'string'],
            [['created_datetime', 'modified_datetime'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'address_id' => 'Address name',
            'customer_id' => 'Customer name',
            'address_type_id' => 'Address type',
            'country_id' => 'Country name',
            'city_id' => 'City name',
            'area_id' => 'Area name',
            'address_archived' => 'Delete',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'created_datetime' => 'Created Datetime',
            'modified_datetime' => 'Modified Datetime',
            'trash' => 'Trash',
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
