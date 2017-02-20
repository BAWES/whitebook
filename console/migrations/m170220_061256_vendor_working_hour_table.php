<?php

use yii\db\Migration;

class m170220_061256_vendor_working_hour_table extends Migration
{
    public function up()
    {
        $this->createTable('whitebook_vendor_working_timing', [
            'working_id' => $this->primaryKey(),
            'vendor_id' => $this->integer()->notNull(),
            'working_day' => "ENUM('Sunday', 'Monday', 'Tuesday', 'Wednesday','Thursday','Friday','Saturday') NOT NULL",
            'working_start_time' => $this->time()->notNull(),
            'working_end_time' => $this->time()->notNull(),
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');
    }

    public function down()
    {
        $this->dropTable('whitebook_vendor_working_timing');
    }
}
