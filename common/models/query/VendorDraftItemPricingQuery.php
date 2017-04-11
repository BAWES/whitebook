<?php

namespace common\models\query;
use Yii;
/**
 * This is the ActiveQuery class for [[VendorDraftItemPricing]].
 *
 * @see Booking
 */
class VendorDraftItemPricingQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return VendorDraftItemPricing[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return VendorDraftItemPricing|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function item($ID) {
        return $this->andWhere(['item_id' => $ID]);
    }

}