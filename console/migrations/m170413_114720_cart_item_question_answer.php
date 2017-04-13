<?php

use yii\db\Migration;

class m170413_114720_cart_item_question_answer extends Migration
{
    public function up()
    {
        $this->createTable('{{%customer_cart_item_question_answer}}', [
            'cart_item_question_answer_id' => $this->primaryKey(),
            'cart_id' => $this->integer(11)->notNull(),
            'question_id' => $this->integer(11)->notNull(),
            'item_id' => $this->integer(11)->notNull(),
            'answer' => $this->text(),
            'created_datetime' => $this->dateTime(),
            'modified_datetime' => $this->dateTime(),
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');
    }

    public function down()
    {
        echo "m170413_114720_cart_item_question_answer cannot be reverted.\n";

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
