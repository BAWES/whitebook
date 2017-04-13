<?php

use yii\db\Migration;

class m170413_044758_item_question extends Migration
{
    public function up()
    {

        $this->truncateTable('{{%vendor_item_question_guide}}');
        $this->dropIndex('guide_image_id','{{%vendor_item_question_guide}}');
        $this->dropIndex('question_id','{{%vendor_item_question_guide}}');
        $this->dropTable('{{%vendor_item_question_guide}}');

        $this->dropForeignKey('whitebook_vendor_item_question_answer_option_ibfk_1','{{%vendor_item_question_answer_option}}');
        $this->dropIndex('answer_background_image_id','{{%vendor_item_question_answer_option}}');
        $this->dropIndex('question_id','{{%vendor_item_question_answer_option}}');
        $this->dropTable('{{%vendor_item_question_answer_option}}');


        $this->dropIndex('answer_id','{{%vendor_item_question}}');
        $this->dropIndex('item_id','{{%vendor_item_question}}');
        $this->dropTable('{{%vendor_item_question}}');


        $this->createTable('{{%vendor_item_question}}', [
            'item_question_id' => $this->primaryKey(),
            'item_id' => $this->integer(11)->notNull(),
            'question' => $this->text(),
            'required' => $this->boolean()->defaultValue(0),
            'created_datetime' => $this->dateTime(),
            'modified_datetime' => $this->dateTime(),
            'trash' => "ENUM('Default', 'Deleted') NOT NULL Default 'Default'",
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');

        $this->createTable('{{%vendor_draft_item_question}}', [
            'item_question_id' => $this->primaryKey(),
            'item_id' => $this->integer(11)->notNull(),
            'question' => $this->text(),
            'required' => $this->boolean()->defaultValue(0),
            'created_datetime' => $this->dateTime(),
            'modified_datetime' => $this->dateTime(),
            'trash' => "ENUM('Default', 'Deleted') NOT NULL Default 'Default'",
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');


        $this->createTable('{{%booking_item_answers}}', [
            'answer_id' => $this->primaryKey(),
            'booking_id' => $this->integer(11)->notNull(),
            'item_id' => $this->integer(11)->notNull(),
            'question' => $this->text(),
            'answer' => $this->text(),
            'created_datetime' => $this->dateTime(),
            'modified_datetime' => $this->dateTime(),
            'trash' => "ENUM('Default', 'Deleted') NOT NULL Default 'Default'",
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');
    }

    public function down()
    {
        echo "m170413_044758_item_question cannot be reverted.\n";

        return false;
    }

}
