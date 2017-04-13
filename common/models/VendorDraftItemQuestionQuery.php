<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[VendorDraftItemQuestion]].
 *
 * @see VendorDraftItemQuestion
 */
class VendorDraftItemQuestionQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return VendorDraftItemQuestion[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return VendorDraftItemQuestion|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function item($id) {
        return $this->andWhere(['item_id'=>$id]);
    }
}
