<?php

namespace common\models\query;
use Yii;
/**
 * This is the ActiveQuery class for [[Vendor]].
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
     * @return Vendor[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Vendor|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function joinLocation() {
        return $this->leftJoin('{{%location}}', '{{%location}}.id = {{%customer_address}}.area_id');
    }

    public function byDefaultAddress()
    {
        return $this->andWhere(['{{%customer_address}}.trash'=>'Default']);
    }

    public function byCustomer($ID)
    {
        return $this->andWhere(['{{%customer_address}}.customer_id' => $ID]);
    }

    public function byLocation($area_ids)
    {
        return $this->andWhere(['{{%location}}.id' => $area_ids]);
    }
}