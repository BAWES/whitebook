<?php

namespace common\models\query;
use Yii;
/**
 * This is the ActiveQuery class for [[VendorDraftItemMenu]].
 *
 * @see Booking
 */
class VendorDraftItemMenuQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return VendorDraftItemMenu[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return VendorDraftItemMenu|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function item($id)
    {
        return $this->andWhere(['item_id' => $id]);
    }

    public function menu($type)
    {
        return $this->andWhere(['menu_type' => $type]);
    }
}