<?php

use yii\db\Migration;

class m170222_104825_update_order_request_status_table extends Migration
{
    public function up()
    {
        $this->addColumn('whitebook_order_request_status', 'vendor_id', $this->integer(11) . ' UNSIGNED NULL AFTER order_id');
        $this->createIndex('inx_ors_vendor_id', 'whitebook_order_request_status', 'vendor_id');
        $this->addForeignKey('fk_ors_vendor_id', 'whitebook_order_request_status', 'vendor_id', 'whitebook_vendor', 'vendor_id', 'SET NULL' , 'SET NULL');
    }

    public function down()
    {
        echo "m170222_104825_update_order_request_status_table cannot be reverted.\n";

        return false;
    }
}
