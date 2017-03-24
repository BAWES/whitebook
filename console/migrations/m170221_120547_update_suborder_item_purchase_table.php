<?php

use yii\db\Migration;

class m170221_120547_update_suborder_item_purchase_table extends Migration
{
    public function up()
    {
        $this->execute("SET foreign_key_checks = 0;");
        $this->truncateTable('{{%suborder_item_purchase}}');

        $this->dropForeignKey('suborder_item_purchase_t_fk', '{{%suborder_item_purchase}}');
        $this->dropIndex('timeslot_id','{{%suborder_item_purchase}}');

        $this->renameColumn('{{%suborder_item_purchase}}','timeslot_id','working_id');
        $this->alterColumn('{{%suborder_item_purchase}}', 'working_id', $this->integer(11) . ' UNSIGNED NULL');

        $this->createIndex('working_id','{{%suborder_item_purchase}}','working_id');
     
        $this->addForeignKey('whitebook_suborder_item_purchase_t_fk', '{{%suborder_item_purchase}}', 'working_id', '{{%vendor_working_timing}}', 'working_id', 'SET NULL' , 'SET NULL');
     
        $this->execute("SET foreign_key_checks = 1;");
    }

    public function down()
    {
        echo "m170221_120547_update_suborder_item_purchase_table cannot be reverted.\n";

        return false;
    }
}