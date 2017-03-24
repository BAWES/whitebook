<?php

use yii\db\Migration;

class m160612_062805_events_add_columns extends Migration
{
    public function up()
    {
        $this->addColumn('{{%events}}', 'created_by',$this->integer());
        $this->addColumn('{{%events}}', 'modified_by',$this->integer());
        $this->addColumn('{{%events}}', 'created_datetime',$this->datetime());
        $this->addColumn('{{%events}}', 'modified_datetime',$this->datetime());
    }

    public function down()
    {
        echo "m160612_062805_events_add_columns cannot be reverted.\n";

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
