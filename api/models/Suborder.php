<?php

namespace api\models;
use common\models\Suborder;

/**
 * This is the model class for table "whitebook_suborder".
 *
 * @property string $suborder_id
 * @property string $order_id
 * @property string $vendor_id
 * @property string $status_id
 * @property string $suborder_delivery_charge
 * @property string $suborder_total_without_delivery
 * @property string $suborder_total_with_delivery
 * @property string $suborder_commission_percentage
 * @property string $suborder_commission_total
 * @property string $suborder_vendor_total
 * @property string $suborder_datetime
 * @property integer $created_by
 * @property integer $modified_by
 * @property string $created_datetime
 * @property string $modified_datetime
 * @property string $trash
 */
class Order extends \common\models\Order
{
    public function getSuborder()
    {
        return $this->hasOne(Suborder::className(),['order_id'=>'order_id']);
    }

    //    public function getOrderItems()
    //    {
    //        return $this->hasMany(\common\models\SuborderItemPurchase::className(),['suborder_id'=>'suborder_id']);
    //    }
}
