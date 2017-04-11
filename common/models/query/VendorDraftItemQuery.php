<?php

namespace common\models\query;
use Yii;
/**
 * This is the ActiveQuery class for [[VendorDraftItem]].
 *
 * @see Booking
 */
class VendorDraftItemQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return VendorDraftItem[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return VendorDraftItem|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function item($ID) {
        return $this->andWhere(['item_id' => $ID]);
    }

}