<?php

use yii\db\Migration;

class m170302_115635_delete_order_table extends Migration
{
    public function up()
    {
        // whitebook_suborder_item_purchase_customization
        
        $this->dropIndex('purchase_id', '{{%suborder_item_purchase_customization}}');
        $this->dropIndex('question_id', '{{%suborder_item_purchase_customization}}');
        $this->dropIndex('answer_id', '{{%suborder_item_purchase_customization}}');
        
        // whitebook_suborder_item_purchase
        $this->dropForeignKey('whitebook_suborder_item_purchase_address_fk', '{{%suborder_item_purchase}}');
        $this->dropForeignKey('whitebook_suborder_item_purchase_area_fk', '{{%suborder_item_purchase}}');
        $this->dropForeignKey('whitebook_suborder_item_purchase_i_fk', '{{%suborder_item_purchase}}');
        $this->dropForeignKey('whitebook_suborder_item_purchase_s_fk', '{{%suborder_item_purchase}}');
        
        $this->dropIndex('suborder_id', '{{%suborder_item_purchase}}');
        $this->dropIndex('item_id', '{{%suborder_item_purchase}}');
        $this->dropIndex('area_id', '{{%suborder_item_purchase}}');
        $this->dropIndex('address_id', '{{%suborder_item_purchase}}');

        // whitebook_suborder_item_menu
        $this->dropForeignKey('suborder_item_menu_m_fk', '{{%suborder_item_menu}}');
        $this->dropForeignKey('suborder_item_menu_mi_fk', '{{%suborder_item_menu}}');
        $this->dropForeignKey('suborder_item_menu_p_fk', '{{%suborder_item_menu}}');
        $this->dropIndex('suborder_item_menu_p_inx', '{{%suborder_item_menu}}');
        $this->dropIndex('suborder_item_menu_m_inx', '{{%suborder_item_menu}}');
        $this->dropIndex('suborder_item_menu_mi_inx', '{{%suborder_item_menu}}');


        // whitebook_suborder
        $this->dropForeignKey('vendor_suborder_fk', '{{%suborder}}');
        $this->dropForeignKey('vendor_suborder_order_fk', '{{%suborder}}');
        $this->dropForeignKey('vendor_suborder_status_fk', '{{%suborder}}');
        $this->dropIndex('order_id', '{{%suborder}}');
        $this->dropIndex('vendor_id', '{{%suborder}}');
        $this->dropIndex('status_id', '{{%suborder}}');

        // whitebook_order_request_status
        $this->dropForeignKey('fk_ors_vendor_id', '{{%order_request_status}}');
        $this->dropIndex('inx_ors_vendor_id', '{{%order_request_status}}');
        $this->dropIndex('inx_ors_request_token', '{{%order_request_status}}');

        // whitebook_order
        $this->dropForeignKey('customer_id_fk', '{{%order}}');
        $this->dropIndex('customer_id_fk', '{{%order}}');
        $this->dropIndex('inx_order_uid', '{{%order}}');

        $this->dropTable('{{%suborder_item_purchase_customization}}');
        $this->dropTable('{{%order_request_status}}');
        $this->dropTable('{{%suborder_item_purchase}}');
        $this->dropTable('{{%suborder_item_menu}}');
        $this->dropTable('{{%suborder}}');
        $this->dropTable('{{%order}}');

    }

    public function down()
    {
        echo "m170302_115635_delete_order_table cannot be reverted.\n";

        return false;
    }
}
