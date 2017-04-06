<?php

namespace common\models\query;
use Yii;

/**
 * This is the ActiveQuery class for [[BlockDate]].
 *
 * @see VendorItemPricing
 */
class BlockDateQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return BlockDate[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return BlockDate|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function byVendor($vendor_id)
    {
        return $this->andWhere(['vendor_id' => $vendor_id]);
    }

    public function byBlockedDate($selectedDate)
    {
        return $this->andWhere(['block_date' => date('Y-m-d', strtotime($selectedDate))]);
    }
}