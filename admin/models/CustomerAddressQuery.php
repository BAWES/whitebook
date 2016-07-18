<?php

namespace admin\models;

/**
 * This is the ActiveQuery class for [[\app\models\CustomerAddress]].
 *
 * @see \app\models\CustomerAddress
 */
class CustomerAddressQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return \app\models\CustomerAddress[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\CustomerAddress|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
