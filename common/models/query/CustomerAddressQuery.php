<?php

namespace common\models\query;
use Yii;
/**
 * This is the ActiveQuery class for [[CustomerAddress]].
 *
 * @see Booking
 */
class CustomerAddressQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return CustomerAddress[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return CustomerAddress|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function joinLocation() {
        return $this->leftJoin('{{%location}}', '{{%location}}.id = {{%customer_address}}.area_id');
    }

    public function joinCity() {
        return $this->leftJoin('{{%city}}', '{{%city}}.city_id = {{%customer_address}}.city_id');
    }

    public function defaultAddress()
    {
        return $this->andWhere(['{{%customer_address}}.trash'=>'Default']);
    }

    public function customer($ID)
    {
        return $this->andWhere(['{{%customer_address}}.customer_id' => $ID]);
    }

    public function location($area_ids)
    {
        return $this->andWhere(['{{%location}}.id' => $area_ids]);
    }

    public function addressType($id) {
        return $this->andWhere(['address_type_id' => $id]);
    }

    public function customerID($id) {
        return $this->andWhere('customer_id = :customer_id', [':customer_id' => $id]);
    }
}