<?php

namespace common\models\query;
use Yii;
/**
 * This is the ActiveQuery class for [[VendorItem]].
 *
 * @see Booking
 */
class VendorItemQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return VendorItem[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return VendorItem|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function byCategoryID($id)
    {
        return $this->andWhere('category_id='.$id);
    }
}