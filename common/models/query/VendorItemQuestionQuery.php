<?php

namespace common\models\query;
use Yii;
/**
 * This is the ActiveQuery class for [[VendorItemQuestion]].
 *
 * @see Booking
 */
class VendorItemQuestionQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return VendorItemQuestion[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return VendorItemQuestion|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function item() {

    }
}