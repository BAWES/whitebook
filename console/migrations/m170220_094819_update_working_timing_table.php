<?php

use yii\db\Migration;

class m170220_094819_update_working_timing_table extends Migration
{
    public function up()
    {
        $this->addColumn('{{%vendor_working_timing}}', 'trash', "ENUM('Default', 'Deleted') NOT NULL DEFAULT 'Default' after `working_end_time`");
    }

    public function down()
    {
        echo "m170220_094819_update_working_timing_table cannot be reverted.\n";

        return false;
    }
}
