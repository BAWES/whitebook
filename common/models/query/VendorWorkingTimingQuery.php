<?php

namespace common\models\query;
use Yii;
/**
 * This is the ActiveQuery class for [[VendorItemThemes]].
 *
 * @see Booking
 */
class VendorWorkingTimingQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return VendorItemThemes[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return VendorItemThemes|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function byVendorID($id)
    {
        return $this->andWhere(['vendor_id'=>$id]);
    }

    public function byWorkingDay($id)
    {
        return $this->andWhere(['working_day'=>$id]);
    }

    public function defaultTiming($id)
    {
        return $this->andWhere(['trash' => 'Default']);
    }
}