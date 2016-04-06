<?php

use yii\db\Migration;

class m160406_115629_image extends Migration
{
    public function up()
    {
         $this->dropTable('image');
         
    }

    public function down()
    {
        echo "m160406_115629_image cannot be reverted.\n";

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
