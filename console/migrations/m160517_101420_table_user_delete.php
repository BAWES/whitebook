<?php

use yii\db\Migration;

class m160517_101420_table_user_delete extends Migration
{
    public function up()
    {
         // drops foreign key for table `user`
        $this->dropForeignKey(
            'fk_user_profile',
            'whitebook_profile'
        );

        $this->dropForeignKey(
            'fk_user_account',
            'whitebook_social_account'
        );

        $this->dropForeignKey(
            'fk_user_token',
            'whitebook_token'
        );
        $this->dropTable('{{%user}}');
    }

    public function down()
    {
        echo "m160517_101420_table_user_delete cannot be reverted.\n";

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
