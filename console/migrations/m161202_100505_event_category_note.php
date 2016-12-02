<?php

use yii\db\Migration;

class m161202_100505_event_category_note extends Migration
{
    public function up()
    {
        $this->addColumn('whitebook_category_note', 'event_id', $this->integer(11).' NULL AFTER customer_id');

        $this->addForeignKey ('cat_note_evnt_fk', 'whitebook_category_note', 'event_id', 'whitebook_events', 'event_id', 'SET NULL' , 'SET NULL');
    }
}
