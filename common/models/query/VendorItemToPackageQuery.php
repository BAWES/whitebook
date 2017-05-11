<?php

namespace common\models\query;
use Yii;

/**
 * This is the ActiveQuery class for [[VendorItemToPackageQuery]].
 *
 * @see VendorItemPricing
 */
class VendorItemToPackageQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return VendorItemToPackageQuery[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return VendorItemToPackageQuery|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function package($ID) {
        return $this->andWhere(['package_id' => $ID]);
    }
}