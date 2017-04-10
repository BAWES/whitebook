<?php

namespace common\models\query;
use Yii;
/**
 * This is the ActiveQuery class for [[VendorItemThemes]].
 *
 * @see Booking
 */
class VendorItemThemesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return VendorItemThemes[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return VendorItemThemes|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function byVendorID($id)
    {
        return $this->andWhere(['vendor_id'=>$id]);
    }

    public function defaultItemThemes()
    {
        return $this->andWhere("{{%vendor_item_theme}}.trash='default'");
    }

    public function itemIDs($ids)
    {
        return $this->andWhere("{{%vendor_item_theme}}.item_id IN(".$ids.")");
    }
}