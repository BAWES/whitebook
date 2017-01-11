<?php

use yii\db\Migration;

class m170110_102803_new_table_customer_token extends Migration
{
    public function up()
    {
        $this->createTable('whitebook_customer_token', [
            'id' => $this->primaryKey(),
            'customer_id' => $this->integer(11),
            'token_value' => $this->string(100),
            'token_status' => $this->string(100)
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');
    }

    public function down()
    {
        echo "m170110_102803_new_table_customer_token cannot be reverted.\n";

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
