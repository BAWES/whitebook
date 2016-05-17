<?php

use yii\db\Migration;

class m160430_123635_whitebook_user extends Migration
{
    public function up()
    {

         // drops foreign key for table `user`
        $this->dropForeignKey(
            'fk-user_id',
            'whitebook_user'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            'idx-user_id',
            'whitebook_user'
        );

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
