<?php

use yii\db\Migration;

class m170221_101901_timeslot_to_working_hour extends Migration
{
    public function up()
    {
        $this->dropForeignKey('whitebook_suborder_item_purchase_t_fk', 'whitebook_suborder_item_purchase');

        $this->dropColumn('whitebook_suborder_item_purchase', 'timeslot_id');
        
        $this->addColumn('whitebook_suborder_item_purchase', 'working_id', $this->integer(11) . ' UNSIGNED NULL AFTER address_id');

        $this->createIndex('inx_sip_working_id', 'whitebook_suborder_item_purchase', 'working_id');

        $this->addForeignKey('fk_sip_working_id', 'whitebook_suborder_item_purchase', 'working_id', 'whitebook_vendor_working_timing', 'working_id', 'SET NULL' , 'SET NULL');
    }
}
