<?php

namespace common\models\query;
use Yii;
/**
 * This is the ActiveQuery class for [[CustomerCartMenuItemQuery]].
 *
 * @see Booking
 */
class CustomerCartMenuItemQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    public function all($db = null)
    {
        return parent::all($db);
    }

    public function one($db = null)
    {
        return parent::one($db);
    }

    public function cartID($cartID) {
        return $this->andWhere(['cart_id' => $cartID]);
    }

    public function joinVendorItemMenuItem() {
        return $this->innerJoin('{{%vendor_item_menu_item}}', '{{%vendor_item_menu_item}}.menu_item_id = {{%customer_cart_menu_item}}.menu_item_id');
    }

    public function joinVendorItemMenu() {
        return $this->innerJoin('{{%vendor_item_menu}}', '{{%vendor_item_menu}}.menu_id = {{%customer_cart_menu_item}}.menu_id');
    }
}