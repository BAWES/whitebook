<?php

use yii\db\Migration;

class m161130_113734_event_progress extends Migration
{
    public function up()
    {
        $this->createTable('whitebook_event_category_completed', [
            'ecc_id' => $this->primaryKey(),
            'event_id' => $this->integer(11).' NULL',
            'category_id' => $this->integer(11).' UNSIGNED NULL',
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');

        $this->addForeignKey ('evnt_cat_cmpltd_cat_fk', 'whitebook_event_category_completed', 'category_id', 'whitebook_category', 'category_id', 'SET NULL' , 'SET NULL');

        $this->addForeignKey ('evnt_cat_cmpltd_evnt_fk', 'whitebook_event_category_completed', 'event_id', 'whitebook_events', 'event_id', 'SET NULL' , 'SET NULL');
    }
}
