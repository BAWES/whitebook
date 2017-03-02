<?php

use yii\db\Migration;

class m170302_115635_delete_order_table extends Migration
{
    public function up()
    {
        // whitebook_suborder_item_purchase_customization
        $this->dropIndex('purchase_id', 'whitebook_suborder_item_purchase_customization');
        $this->dropIndex('question_id', 'whitebook_suborder_item_purchase_customization');
        $this->dropIndex('answer_id', 'whitebook_suborder_item_purchase_customization');

        // whitebook_suborder_item_purchase
        $this->dropForeignKey('whitebook_suborder_item_purchase_address_fk', 'whitebook_suborder_item_purchase');
        $this->dropForeignKey('whitebook_suborder_item_purchase_area_fk', 'whitebook_suborder_item_purchase');
        $this->dropForeignKey('whitebook_suborder_item_purchase_i_fk', 'whitebook_suborder_item_purchase');
        $this->dropForeignKey('whitebook_suborder_item_purchase_s_fk', 'whitebook_suborder_item_purchase');
        $this->dropIndex('suborder_id', 'whitebook_suborder_item_purchase');
        $this->dropIndex('item_id', 'whitebook_suborder_item_purchase');
        $this->dropIndex('area_id', 'whitebook_suborder_item_purchase');
        $this->dropIndex('address_id', 'whitebook_suborder_item_purchase');

        // whitebook_suborder_item_menu
        $this->dropForeignKey('suborder_item_menu_m_fk', 'whitebook_suborder_item_menu');
        $this->dropForeignKey('suborder_item_menu_mi_fk', 'whitebook_suborder_item_menu');
        $this->dropForeignKey('suborder_item_menu_p_fk', 'whitebook_suborder_item_menu');
        $this->dropIndex('suborder_item_menu_p_inx', 'whitebook_suborder_item_menu');
        $this->dropIndex('suborder_item_menu_m_inx', 'whitebook_suborder_item_menu');
        $this->dropIndex('suborder_item_menu_mi_inx', 'whitebook_suborder_item_menu');


        // whitebook_suborder
        $this->dropForeignKey('vendor_suborder_fk', 'whitebook_suborder');
        $this->dropForeignKey('vendor_suborder_order_fk', 'whitebook_suborder');
        $this->dropForeignKey('vendor_suborder_status_fk', 'whitebook_suborder');
        $this->dropIndex('order_id', 'whitebook_suborder');
        $this->dropIndex('vendor_id', 'whitebook_suborder');
        $this->dropIndex('status_id', 'whitebook_suborder');

        // whitebook_order_request_status
        $this->dropForeignKey('fk_ors_vendor_id', 'whitebook_order_request_status');
        $this->dropIndex('inx_ors_vendor_id', 'whitebook_order_request_status');
        $this->dropIndex('inx_ors_request_token', 'whitebook_order_request_status');

        // whitebook_order
        $this->dropForeignKey('customer_id_fk', 'whitebook_order');
        $this->dropIndex('customer_id_fk', 'whitebook_order');
        $this->dropIndex('inx_order_uid', 'whitebook_order');

        $this->dropTable('whitebook_suborder_item_purchase_customization');
        $this->dropTable('whitebook_order_request_status');
        $this->dropTable('whitebook_suborder_item_purchase');
        $this->dropTable('whitebook_suborder_item_menu');
        $this->dropTable('whitebook_suborder');
        $this->dropTable('whitebook_order');

    }

    public function down()
    {
        echo "m170302_115635_delete_order_table cannot be reverted.\n";

        return false;
    }
}
