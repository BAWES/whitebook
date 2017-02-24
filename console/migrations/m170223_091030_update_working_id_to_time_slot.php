<?php

use yii\db\Migration;

class m170223_091030_update_working_id_to_time_slot extends Migration
{
    public function up()
    {
        $this->execute("SET foreign_key_checks = 0;");

        // table whitebook_customer_cart
        $this->truncateTable('whitebook_customer_cart');
        $this->dropForeignKey('cart_working_id_fk', 'whitebook_customer_cart');
        $this->dropIndex('working_id','whitebook_customer_cart');
        $this->renameColumn('whitebook_customer_cart','working_id','time_slot');
        $this->alterColumn('whitebook_customer_cart','time_slot',$this->string(250));
        
        // table whitebook_suborder_item_purchase
        $this->dropForeignKey('whitebook_suborder_item_purchase_t_fk', 'whitebook_suborder_item_purchase');
        $this->dropIndex('working_id','whitebook_suborder_item_purchase');
        $this->renameColumn('whitebook_suborder_item_purchase','working_id','time_slot');
        $this->alterColumn('whitebook_suborder_item_purchase','time_slot',$this->string(250));
        $this->execute("SET foreign_key_checks = 1;");
    }

    public function down()
    {
        echo "m170223_091030_update_working_id_to_time_slot cannot be reverted.\n";
        return false;
    }
}
