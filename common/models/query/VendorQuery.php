<?php

namespace common\models\query;
use Yii;
/**
 * This is the ActiveQuery class for [[Vendor]].
 *
 * @see Booking
 */
class VendorQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Vendor[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Vendor|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function byVendorID($vendor_ids)
    {
        return $this->andWhere(['IN','vendor_id'=>$vendor_ids]);
    }

}