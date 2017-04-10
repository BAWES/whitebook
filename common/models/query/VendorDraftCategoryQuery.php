<?php

namespace common\models\query;
use Yii;
/**
 * This is the ActiveQuery class for [[VendorDraftCategory]].
 *
 * @see Booking
 */
class VendorDraftCategoryQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return VendorDraftCategory[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return VendorDraftCategory|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function joinCategory() {
        return $this->leftJoin('{{%category}}', '{{%category}}.category_id = {{%vendor_draft_category}}.category_id');
    }

    public function draft($ID) {
        return $this->where(['{{%vendor_draft_category}}.vendor_draft_id' => $ID]);
    }
}