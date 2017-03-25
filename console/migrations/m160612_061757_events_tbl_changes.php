<?php

use yii\db\Migration;

class m160612_061757_events_tbl_changes extends Migration
{
    public function up()
    {
         $this->dropColumn('{{%events}}', 'created_date');
    }

    public function down()
    {
        echo "m160612_061757_events_tbl_changes cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
