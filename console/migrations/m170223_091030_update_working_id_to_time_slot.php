<?php

use yii\db\Migration;

class m170223_091030_update_working_id_to_time_slot extends Migration
{
    public function up()
    {
        $this->execute("SET foreign_key_checks = 0;");

        // table whitebook_customer_cart
        $this->truncateTable('{{%customer_cart}}');
        $this->dropForeignKey('cart_working_id_fk', '{{%customer_cart}}');
        $this->dropIndex('working_id','{{%customer_cart}}');
        $this->renameColumn('{{%customer_cart}}','working_id','time_slot');
        $this->alterColumn('{{%customer_cart}}','time_slot',$this->string(250));
        
        // table whitebook_suborder_item_purchase
        $this->dropForeignKey('whitebook_suborder_item_purchase_t_fk', '{{%suborder_item_purchase}}');
        $this->dropIndex('working_id','{{%suborder_item_purchase}}');
        $this->renameColumn('{{%suborder_item_purchase}}','working_id','time_slot');
        $this->alterColumn('{{%suborder_item_purchase}}','time_slot',$this->string(250));
        $this->execute("SET foreign_key_checks = 1;");
    }

    public function down()
    {
        echo "m170223_091030_update_working_id_to_time_slot cannot be reverted.\n";
        return false;
    }
}
