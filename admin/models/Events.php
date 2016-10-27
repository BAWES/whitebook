<?php

namespace admin\models;

use common\models\Customer;
use Yii;
/**
* This is the model class for table "{{%events}}".
*
* @property integer $event_id
* @property integer $customer_id
* @property string $event_name
* @property string $event_date
* @property string $event_type
* @property string $created_date
*/
class Events extends \common\models\Events
{
    public function getCustomer(){

        return $this->hasOne(Customer::className(),['customer_id'=>'customer_id']);

    }


    public function attributeLabels()
    {
        return [
            'event_id' => 'Event ID',
            'customer_id' => 'Customer',
            'event_name' => 'Event Name',
            'event_date' => 'Event Date',
            'event_type' => 'Event Type',
        ];
    }
}
