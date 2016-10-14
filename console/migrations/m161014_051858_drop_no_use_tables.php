<?php

use yii\db\Migration;

class m161014_051858_drop_no_use_tables extends Migration
{
    public function up()
    {
        $this->dropTable('whitebook_vendor_address');
        $this->dropTable('whitebook_package_invoice');
        $this->dropTable('whitebook_vendor_delivery_area');
        $this->dropTable('whitebook_contacts');
        $this->dropTable('whitebook_status1');
        $this->dropTable('whitebook_commission');
        $this->dropTable('whitebook_subscribe');
        $this->dropTable('whitebook_vendor_item_request');
        $this->dropTable('whitebook_customer_cart_item_customization');
        $this->dropTable('whitebook_shipping_info');
        $this->dropTable('whitebook_priority_log');
    }

    public function down()
    {
        echo "m161014_051858_drop_no_use_tables cannot be reverted.\n";
        return false;
    }
}
