<?php

namespace common\models\query;
use Yii;
/**
 * This is the ActiveQuery class for [[VendorLocation]].
 *
 * @see Booking
 */
class VendorLocationQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return VendorLocation[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return VendorLocation|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function vendor($vendor_id)  {
        return $this->andWhere(['vendor_id' => $vendor_id]);
    }

    public function area($area_id)  {
        return $this->andWhere(['area_id' => $area_id]);
    }

    public function nonSame($id) {
        return $this->andWhere(['!=', 'id', $id]);
    }

    public function locationID($id) {
        return $this->andWhere(['id' => $id]);
    }
}