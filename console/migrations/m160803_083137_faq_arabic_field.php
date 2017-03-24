<?php

use yii\db\Migration;

class m160803_083137_faq_arabic_field extends Migration
{
    public function up()
    {
        $this->addColumn(
            '{{%faq}}', 
            'question_ar', 
            $this->string()->notNull()->after('question')
        );

        $this->addColumn(
            '{{%faq}}', 
            'answer_ar', 
            $this->string()->notNull()->after('answer')
        );
    }

    public function down()
    {
        echo "m160803_083137_faq_arabic_field cannot be reverted.\n";
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
