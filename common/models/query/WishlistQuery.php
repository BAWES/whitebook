<?php

namespace common\models\query;
use Yii;
/**
 * This is the ActiveQuery class for [[Wishlist]].
 *
 * @see Booking
 */
class WishlistQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Wishlist[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Wishlist|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function active() {
        return $this->andWhere([
            '{{%wishlist}}.wish_status' => 1,
        ]);
    }

    public function defaultWishlist() {
        return $this->andWhere([
            '{{%vendor_item}}.trash' => 'Default'
        ]);
    }

    public function customer($customer_id) {
        return $this->andWhere([
            '{{%wishlist}}.customer_id' => $customer_id,
        ]);
    }

    public function vendor($ID){
        return $this->andWhere(['{{%vendor_item}}.vendor_id' => $ID]);
    }

    public function theme($ID){
        return $this->andWhere('FIND_IN_SET ("'.$ID.'", {{%vendor_item_theme}}.theme_id)');
    }


}