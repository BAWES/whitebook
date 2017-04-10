<?php

namespace common\models\query;
use Yii;

/**
 * This is the ActiveQuery class for [[VendorItemToCategory]].
 *
 * @see VendorItemPricing
 */
class VendorItemToCategoryQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return VendorItemToCategory[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return VendorItemToCategory|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function category($id) {
        return $this->andWhere(['category_id' => $id]);
    }

}