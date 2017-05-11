<?php

namespace common\models\query;
use Yii;
/**
 * This is the ActiveQuery class for [[VendorDraftItemToCategory]].
 *
 * @see Booking
 */
class VendorDraftItemToCategoryQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return VendorDraftItemToCategory[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return VendorDraftItemToCategory|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function item($ID) {
        return $this->andWhere(['{{%vendor_draft_item_to_category}}.item_id' => $ID]);
    }

    public function secondLevel() {
        return $this->andWhere(['{{%category_path}}.level' => 2]);
    }

    public function defaultDraftCategory() {
        return $this->andWhere([
            '{{%category}}.trash' => 'Default',
        ]);
    }

    public function joinCategory()
    {
        return $this->leftJoin('{{%category}}', '{{%category}}.category_id = {{%vendor_draft_item_to_category}}.category_id');
    }

    public function joinCategoryPath() {
        return $this->leftJoin('{{%category_path}}', '{{%category}}.category_id = {{%category_path}}.path_id');
    }
}