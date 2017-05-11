<?php

namespace common\models\query;
use Yii;
/**
 * This is the ActiveQuery class for [[AddressQuestion]].
 *
 * @see Booking
 */
class AddressQuestionQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return AddressQuestion[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return AddressQuestion|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function addressType($type) {
        return $this->andWhere('address_type_id = '. $type);
    }
}