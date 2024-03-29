<?php

use yii\db\Migration;

class m170221_052318_update_timeslot_relation extends Migration
{
    public function up()
    {
        $this->execute("SET foreign_key_checks = 0;");
        
        $this->alterColumn('{{%vendor_working_timing}}', 'working_id', $this->integer(11).' UNSIGNED AUTO_INCREMENT');

        $this->truncateTable('{{%customer_cart}}');
        
        $this->dropForeignKey('cart_timeslot_fk', '{{%customer_cart}}');
        
        $this->dropIndex('timeslot_id','{{%customer_cart}}');
        
        $this->renameColumn('{{%customer_cart}}','timeslot_id','working_id');
        
        $this->alterColumn('{{%customer_cart}}', 'working_id', $this->integer(11) . ' UNSIGNED NULL');
        
        $this->createIndex('working_id','{{%customer_cart}}','working_id');
        
        $this->addForeignKey('cart_working_id_fk', '{{%customer_cart}}', 'working_id', '{{%vendor_working_timing}}', 'working_id', 'SET NULL' , 'SET NULL');
        
        $this->execute("SET foreign_key_checks = 1;");
    }

    public function down()
    {
        $this->execute("SET foreign_key_checks = 0;");
        
        $this->alterColumn ('{{%customer_cart}}', 'timeslot_id', $this->integer(11) . ' UNSIGNED NULL');
        
        $this->addForeignKey ('cart_timeslot_fk', '{{%customer_cart}}', 'timeslot_id', '{{%vendor_delivery_timeslot}}', 'timeslot_id', 'SET NULL' , 'SET NULL');
        
        $this->execute("SET foreign_key_checks = 1;");
    }
}
