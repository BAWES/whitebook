<?php

namespace common\models\query;
use Yii;
/**
 * This is the ActiveQuery class for [[VendorPayment]].
 *
 * @see Booking
 */
class VendorPaymentQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return VendorPayment[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return VendorPayment|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function transfer($payment_id) {
        return $this->andWhere(['transfer_id' => $payment_id]);
    }

    public function joinBooking() {
        return $this->innerJoin('{{%booking}}', '{{%booking}}.booking_id = {{%vendor_payment}}.booking_id');
    }
}