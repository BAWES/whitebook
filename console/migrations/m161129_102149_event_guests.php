<?php

use yii\db\Migration;

class m161129_102149_event_guests extends Migration
{
    public function up()
    {
        $this->addColumn('whitebook_events', 'no_of_guests', $this->integer(11)->after('event_type'));
    }
}
