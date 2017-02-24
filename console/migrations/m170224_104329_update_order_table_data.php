<?php

set_time_limit(0);

use yii\db\Migration;
use \common\models\OrderRequestStatus;
class m170224_104329_update_order_table_data extends Migration
{
    public function up()
    {
        $items = \common\models\Order::find()->all();

        foreach ($items as $key => $value)
        {
            $orderStatus = OrderRequestStatus::findOne(['order_id'=>$value->order_id]);

            if (!$orderStatus) {
                $status = new OrderRequestStatus;
                $status->order_id = $value->order_id;
                $status->vendor_id = (isset($value->subOrder->vendor_id)) ? $value->subOrder->vendor_id : 1;
                $status->request_status = ($value->order_transaction_id) ? 'Approved' : 'Pending';
                $status->save(false);
            }
        }
    }
}

