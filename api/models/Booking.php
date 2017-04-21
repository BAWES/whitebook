<?php
namespace api\models;

use Yii;

/**
 * This is the model class for table "Booking".
 * It extends from \common\models\Booking but with custom functionality for this application module
 */
class Booking extends \common\models\Booking {

    /**
     * @inheritdoc
     */
    public function fields()
    {
        return [
            'booking_id',
            'booking_token',
            'vendor_id',
            'customer_id',
            'customer_name',
            'customer_lastname',
            'customer_email',
            'customer_mobile',
            //'delivery_date',
            //'timeslot',
            //'area_id',
            //'address_id',
            //'delivery_address',
            'booking_note',
            'expired_on',
            'notification_status',
            'payment_method',
            'transaction_id',
            'total_delivery_charge',
            'total_without_delivery',
            'total_with_delivery',
            'booking_status',
            'ip_address',
            'created_datetime',
            'modified_datetime',
            'items' => function($model) {
                return $model->bookingItems;
            }
        ];
    }
}