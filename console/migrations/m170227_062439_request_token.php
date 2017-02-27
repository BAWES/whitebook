<?php

use yii\db\Migration;

class m170227_062439_request_token extends Migration
{
    public function up()
    {
        $this->addColumn('whitebook_order_request_status', 'request_token', $this->char(13)->after('request_id'));

        $this->createIndex('inx_ors_request_token', 'whitebook_order_request_status', 'request_token');
    }

    public function down()
    {
        $this->dropIndex('inx_ors_request_token', 'whitebook_order_request_status');
        $this->dropColumn('whitebook_order_request_status', 'request_token');
    }
}
