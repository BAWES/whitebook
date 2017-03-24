<?php

use yii\db\Migration;

class m160823_065224_arabic_field_data_type_to_text extends Migration
{
    public function up()
    {
        //vendor profile 
        $this->alterColumn("{{%vendor}}", "vendor_return_policy_ar", $this->text());
        $this->alterColumn("{{%vendor}}", "vendor_contact_address_ar", $this->text());
        $this->alterColumn("{{%vendor}}", "vendor_contact_address", $this->text());
        $this->alterColumn("{{%vendor}}", "short_description", $this->text());
        $this->alterColumn("{{%vendor}}", "short_description_ar", $this->text());

        //faqs 
        $this->alterColumn("{{%faq}}", "question_ar", $this->text());
        $this->alterColumn("{{%faq}}", "answer_ar", $this->text());
    }

    public function down()
    {

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
