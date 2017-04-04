<?php

namespace common\models\query;
use Yii;

/**
 * This is the ActiveQuery class for [[VendorItemPricing]].
 *
 * @see VendorItemPricing
 */
class VendorItemPricingQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return VendorItemPricing[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return VendorItemPricing|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function byItemID($ID) {
        return $this->andWhere(['item_id' => $ID]);
    }

    public function defaultItem() {
        return $this->andWhere(['trash' => 'Default']);
    }

    public function byQuantityRange($quantity) {
        return $this->andWhere(['<=', 'range_from', $quantity])->andWhere(['>=', 'range_to', $quantity]);
    }
}