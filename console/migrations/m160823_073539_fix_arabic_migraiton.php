<?php

use yii\db\Migration;

class m160823_073539_fix_arabic_migraiton extends Migration
{
    public function up()
    {
        //vendor profile
        $this->alterColumn("whitebook_vendor", "vendor_return_policy_ar", $this->text());
        $this->alterColumn("whitebook_vendor", "vendor_contact_address_ar", $this->text());
        $this->alterColumn("whitebook_vendor", "vendor_contact_address", $this->text());
        $this->alterColumn("whitebook_vendor", "short_description", $this->text());
        $this->alterColumn("whitebook_vendor", "short_description_ar", $this->text());

        //faqs
        $this->alterColumn("whitebook_faq", "question_ar", $this->text());
        $this->alterColumn("whitebook_faq", "answer_ar", $this->text());
    }

    public function down()
    {
        echo "m160823_073539_fix_arabic_migraiton cannot be reverted.\n";

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
