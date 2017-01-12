<?php

namespace api\models;

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
class CustomerAddress extends \common\models\CustomerAddress
{
    public function rules()
    {
        return [
            [['country_id', 'city_id','created_by', 'modified_by'], 'required'],
            [['customer_id', 'address_type_id', 'country_id', 'city_id', 'area_id'], 'integer'],
            [['address_archived', 'trash'], 'string'],
            [['customer', 'address_name', 'address_data', 'created_datetime','created_by', 'modified_by','modified_datetime'], 'safe']
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_datetime',
                'updatedAtAttribute' => 'modified_datetime',
                'value' => new Expression('NOW()'),
            ],
        ];
    }
}
