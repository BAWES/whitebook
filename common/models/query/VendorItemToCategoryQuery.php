<?php

namespace common\models\query;
use Yii;

/**
 * This is the ActiveQuery class for [[VendorItemToCategory]].
 *
 * @see VendorItemPricing
 */
class VendorItemToCategoryQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return VendorItemToCategory[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return VendorItemToCategory|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function category($id) {
        return $this->andWhere(['category_id' => $id]);
    }

    public function item($id) {
        return $this->andWhere(['item_id' => $id]);
    }

    public function joinCategory()
    {
        return $this->leftJoin('{{%category}}', '{{%category}}.category_id = {{%vendor_item_to_category}}.category_id');
    }
    public function joinCategoryPath()
    {
        return $this->leftJoin('{{%category_path}}', '{{%category}}.category_id = {{%category_path}}.path_id');
    }

    public function defaultCategory() {
        return $this->andWhere(['{{%category}}.trash' => 'Default']);
    }

    public function categoryPathLevel($level) {
        return $this->andWhere([
            '{{%category_path}}.level' => $level
        ]);
    }

}