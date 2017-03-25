<?php

set_time_limit(0);

use yii\db\Migration;

class m170224_104329_update_order_table_data extends Migration
{
    public function up()
    {
        $sql = 'select * from {{%order}}';

        $items = Yii::$app->db->createCommand($sql)->queryAll();

        foreach ($items as $key => $value)
        {
            $sql = 'select * from {{%order_request_status}} where order_id="'.$value->order_id.'"';

            $orderStatus = Yii::$app->db->createCommand($sql)->queryAll();

            if (!$orderStatus) {

                $request_status = ($value->order_transaction_id) ? 'Approved' : 'Pending';

                $vendor_id = (isset($value->subOrder->vendor_id)) ? $value->subOrder->vendor_id : 1;

                $sql = 'update {{%order_request_status}} set order_id="'.$value->order_id.'", vendor_id="'.$vendor_id.'", request_status="'.$request_status.'"';

                Yii::$app->db->createCommand($sql)->execute();
            }
        }
    }
}

