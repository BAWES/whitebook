<?php

use yii\db\Migration;

class m160501_060550_whitebook_customer extends Migration
{
    public function up()
    {
        $this->dropColumn('{{%customer}}', 'customer_org_password');
    }

    public function down()
    {
        echo "m160501_060550_whitebook_customer cannot be reverted.\n";

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
