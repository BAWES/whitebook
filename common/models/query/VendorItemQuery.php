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
        return $this->andWhere('{{%vendor_item}}.category_id='.$id);
    }

    public function bySlug($slug)
    {
        return $this->andWhere(['{{%vendor_item}}.slug' => $slug]);
    }

    public function defaultItems()
    {
        return $this->andWhere(['{{%vendor_item}}.trash' => 'Default']);
    }

    public function activeItems()
    {
        return $this->andWhere(['{{%vendor_item}}.item_status' => 'Active']);
    }

    public function approvedItems()
    {
        return $this->andWhere(['{{%vendor_item}}.item_approved' => 'Yes']);
    }

    public function byActiveVendor()
    {
        return $this->andWhere(['{{%vendor}}.vendor_status' => 'Active']);
    }

    public function approvedVendor()
    {
        return $this->andWhere(['{{%vendor}}.approve_status' => 'Yes']);
    }

    public function defaultVendor()
    {
        return $this->andWhere(['{{%vendor}}.trash' => 'Default']);
    }
}