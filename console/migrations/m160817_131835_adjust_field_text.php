<?php

use yii\db\Migration;

class m160817_131835_adjust_field_text extends Migration
{
    public function up()
    {
        $this->alterColumn("whitebook_cms", "page_content_ar", $this->text());
    }

    public function down()
    {
        echo "m160817_131835_adjust_field_text cannot be reverted.\n";

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
