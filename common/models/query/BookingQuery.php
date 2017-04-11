<?php

namespace common\models\query;
use Yii;
/**
 * This is the ActiveQuery class for [[Booking]].
 *
 * @see Booking
 */
class BookingQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Booking[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Booking|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    // create by record
    public function createdBy($id) {
        return $this->andWhere('customer_id='.$id);
    }

    // create by current record
    public function currentUser() {
        return $this->andWhere('customer_id='.Yii::$app->user->getId());
    }

    // order by record
    public function orderByDate() {
        return $this->orderBy('created_datetime DESC');
    }

    public function token($booking_token) {
        return $this->where(["booking_token"=>$booking_token]);
    }

    public function nonEmptyTransactionID(){
        return $this->andWhere('transaction_id is not NULL');
    }

    public function vendor($vendor_id) {
        return $this->andWhere(['vendor_id' => $vendor_id]);
    }

    public function activeBooking() {
        return $this->andWhere(['booking_status'=>1]);
    }

    public function inactiveBooking() {
        return $this->andWhere(['booking_status'=>0]);
    }

    public function joinVendorPayment() {
        return $this->leftJoin('{{%vendor_payment}}', '{{%vendor_payment}}.booking_id = {{%booking}}.booking_id');
    }
}