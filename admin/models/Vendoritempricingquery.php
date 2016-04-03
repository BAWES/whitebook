<?php

namespace admin\models;

/**
 * This is the ActiveQuery class for [[Vendoritempricing]].
 *
 * @see Vendoritempricing
 */
class Vendoritempricingquery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return Vendoritempricing[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Vendoritempricing|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
