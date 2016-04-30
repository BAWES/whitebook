<?php

use yii\db\Migration;

class m160430_123635_whitebook_user extends Migration
{
    public function up()
    {
        $this->dropTable('whitebook_user');
    }

    public function down()
    {
        echo "m160430_123635_whitebook_user cannot be reverted.\n";

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
