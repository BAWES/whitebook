<?php

namespace common\models\query;
use Yii;
/**
 * This is the ActiveQuery class for [[CustomerAddressResponse]].
 *
 * @see Booking
 */
class CustomerAddressResponseQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return CustomerAddressResponse[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return CustomerAddressResponse|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function joinAddressQuestion() {
        return $this->innerJoin('whitebook_address_question aq', 'aq.ques_id = address_type_question_id');
    }

    public function address($id) {
        return $this->andWhere('address_id = :address_id', [':address_id' => $id]);
    }
}