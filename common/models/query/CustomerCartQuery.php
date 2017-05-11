<?php

namespace common\models\query;
use Yii;
/**
 * This is the ActiveQuery class for [[CustomerCart]].
 *
 * @see Booking
 */
class CustomerCartQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return CustomerCart[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return CustomerCart|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }


    public function item($id) {
        return $this->andWhere(['item_id' => $id]);
    }

    public function area($id) {
        return $this->andWhere(['area_id'   => $id]);
    }

    public function timeSlot($id) {
        return $this->andWhere(['time_slot' => $id]);
    }

    public function deliveryDate($date) {
        return $this->andWhere(['cart_delivery_date' => $date]);
    }

    public function femaleService($service) {
        return $this->andWhere(['female_service' => $service]);
    }

    public function request($request) {
        return $this->andWhere(['special_request' => $request]);
    }

    public function user() {
        if (Yii::$app->user->getId()) {
            return $this->andWhere(['customer_id'=>Yii::$app->user->getId()]);
        } else {
            return $this->andWhere(['cart_session_id'=>\common\models\Customer::currentUser()]);
        }
    }

    public function customer($ID)
    {
        return $this->andWhere(['customer_id' => $ID]);
    }

    public function valid() {
        return $this->andWhere(['cart_valid' => 'yes']);

    }

    public function defaultCart(){
        return $this->andWhere(['trash' => 'Default']);
    }

    public function sessionUser() {
        return $this->andWhere(['cart_session_id'=>\common\models\Customer::currentUser()]);
    }


}