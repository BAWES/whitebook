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

    public function joinPackage() {
        return $this->innerJoin(
            '{{%vendor_item_to_package}}',
            '{{%vendor_item_to_package}}.item_id = {{%vendor_item}}.item_id'
        );
    }

    public function byCategoryID($id)
    {
        return $this->andWhere('{{%vendor_item}}.category_id='.$id);
    }

    public function slug($slug)
    {
        return $this->andWhere(['{{%vendor_item}}.slug' => $slug]);
    }

    public function defaultItems()
    {
        return $this->andWhere(['{{%vendor_item}}.trash' => 'Default']);
    }

    public function active()
    {
        return $this->andWhere(['{{%vendor_item}}.item_status' => 'Active']);
    }

    public function approved()
    {
        return $this->andWhere(['{{%vendor_item}}.item_approved' => 'Yes']);
    }

    public function activeVendor()
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

    public function category($id)
    {
        return $this->andWhere('{{%vendor_item}}.category_id='.$id);
    }

    public function item($id) {
        return $this->andWhere(['item_id'=>$id]);
    }

    public function currentVendor() {
        return $this->andWhere(['vendor_id'=>Yii::$app->user->getId()]);
    }

    public function package($id) {
        return $this->andWhere([
            '{{%vendor_item_to_package}}.package_id' => $id
        ]);
    }
}