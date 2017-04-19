<?php

namespace common\models\query;
use Yii;
/**
 * This is the ActiveQuery class for [[AddressType]].
 *
 * @see Booking
 */
class AddressTypeQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return AddressType[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return AddressType|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function active() {
        return $this->andWhere(['status' => 'Active']);
    }

    public function defaultTrash() {
        return $this->andWhere(['trash' => 'Default']);
    }
}