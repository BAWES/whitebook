<?php

namespace common\models\query;
use Yii;
/**
 * This is the ActiveQuery class for [[query\VendorWorkingTiming]].
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
     * @return VendorWorkingTiming[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return VendorWorkingTiming|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function vendor($id)
    {
        return $this->andWhere(['vendor_id'=>$id]);
    }

    public function workingDay($id)
    {
        return $this->andWhere(['working_day'=>$id]);
    }

    public function defaultTiming()
    {
        return $this->andWhere(['trash' => 'Default']);
    }
}