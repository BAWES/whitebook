<?php

use yii\db\Migration;

class m170227_094847_order_unique_id extends Migration
{
    public function up()
    {
        $this->addColumn('whitebook_order_request_status', 'order_id', $this->integer(11)->after('request_token'));

        $this->addColumn('whitebook_order', 'order_uid', $this->char(13)->after('order_id'));
        $this->createIndex('inx_order_uid', 'whitebook_order', 'order_uid');
    }

    public function down()
    {
        $this->dropColumn('whitebook_order_request_status', 'order_id');

        $this->dropIndex('inx_order_uid', 'whitebook_order');
        $this->dropColumn('whitebook_order', 'order_uid');
    }
}
