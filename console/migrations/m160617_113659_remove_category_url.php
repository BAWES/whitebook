<?php

use yii\db\Migration;

class m160617_113659_remove_category_url extends Migration
{
    public function up()
    {
        $this->dropColumn('whitebook_category','category_url');
    }

    public function down()
    {
        echo "m160617_113659_remove_category_url cannot be reverted.\n";

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
