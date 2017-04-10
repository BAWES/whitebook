<?php

namespace common\models\query;
use Yii;
/**
 * This is the ActiveQuery class for [[VendorItem]].
 *
 * @see Booking
 */
class VendorItemMenuQuery extends \yii\db\ActiveQuery
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

    public function item($ID) {
        return $this->andWhere(['item_id' => $ID]);
    }

    public function menu($menu_type = 'options') {
        return $this->andWhere(['menu_type' => $menu_type]);
    }

    public function optionMenu() {
        return $this->andWhere(['menu_type' => 'options']);
    }

    public function addonMenu() {
        return $this->andWhere(['menu_type' => 'addons']);
    }
}