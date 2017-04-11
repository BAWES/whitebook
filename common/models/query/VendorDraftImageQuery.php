<?php

namespace common\models\query;
use Yii;
/**
 * This is the ActiveQuery class for [[VendorDraftImage]].
 *
 * @see Booking
 */
class VendorDraftImageQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return VendorDraftImage[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return VendorDraftImage|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function item($ID) {
        return $this->andWhere(['item_id'=> $ID]);
    }

}