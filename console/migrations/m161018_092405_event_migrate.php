<?php

use yii\db\Migration;

class m161018_092405_event_migrate extends Migration
{
    public function up()
    {
        $this->truncateTable('whitebook_event_item_link');
        $this->truncateTable('whitebook_event_invitees');
        $this->truncateTable('whitebook_events');

        #resetting for whitebook_event_invitees table
        $this->dropForeignKey('event_invitees_event_fk','whitebook_event_invitees');
        $this->dropForeignKey('event_invitees_customer_fk','whitebook_event_invitees');

        #resetting for whitebook_event_item_link table
        $this->dropForeignKey('vendor_event_item_e_fk','whitebook_event_item_link');
        $this->dropForeignKey('vendor_event_item_i_fk','whitebook_event_item_link');


        $this->dropForeignKey('vendor_item_to_category_i_fk','whitebook_vendor_item_to_category');

        #resetting for whitebook_events table
        $this->dropIndex('events_customer_fk','whitebook_events');

        Yii::$app->db->createCommand("ALTER TABLE whitebook_events ENGINE = InnoDB; ")->execute();

        $this->alterColumn ('whitebook_events', 'customer_id', $this->integer(11) . ' UNSIGNED NULL');
        $this->addForeignKey ('events_customer_fk', 'whitebook_events', 'customer_id', 'whitebook_customer', 'customer_id', 'SET NULL' , 'SET NULL');

        $this->alterColumn ('whitebook_event_item_link', 'event_id', $this->integer(11) . ' NULL');
        $this->addForeignKey ('vendor_event_item_e_fk', 'whitebook_event_item_link', 'event_id', 'whitebook_events', 'event_id');

        $this->alterColumn ('whitebook_event_item_link', 'item_id', $this->integer(11) . ' UNSIGNED NULL');
        $this->addForeignKey ('vendor_event_item_item_fk', 'whitebook_event_item_link', 'item_id', 'whitebook_vendor_item', 'item_id', 'SET NULL' , 'SET NULL');

        //whitebook_event_invitees
        $this->alterColumn ('whitebook_event_invitees', 'customer_id', $this->integer(11) . ' UNSIGNED NULL');
        $this->alterColumn ('whitebook_event_invitees', 'event_id', $this->integer(11) . ' NULL');

        $this->addForeignKey ('event_invitees_customer_fk', 'whitebook_event_invitees', 'customer_id', 'whitebook_customer', 'customer_id', 'SET NULL' , 'SET NULL');
        $this->addForeignKey ('event_invitees_event_fk', 'whitebook_event_invitees', 'event_id', 'whitebook_events', 'event_id', 'SET NULL' , 'SET NULL');


        $this->alterColumn ('whitebook_vendor_item_to_category', 'item_id', $this->integer(11) . ' UNSIGNED NULL');
        $this->addForeignKey ('vendor_item_to_category_item_fk', 'whitebook_vendor_item_to_category', 'item_id', 'whitebook_vendor_item', 'item_id', 'SET NULL' , 'SET NULL');

    }

    public function down()
    {
        echo "m161018_092405_event_migrate cannot be reverted.\n";

        return false;
    }
}
