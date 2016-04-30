<?php

use yii\db\Migration;

class m160430_130653_whitebook_customer extends Migration
{
    public function up()
    {
        $this->dropColumn('whitebook_customer', 'customer_address');
        $this->dropColumn('whitebook_customer', 'country');
        $this->dropColumn('whitebook_customer', 'area');
        $this->dropColumn('whitebook_customer', 'block');
        $this->dropColumn('whitebook_customer', 'street');
        $this->dropColumn('whitebook_customer', 'juda');
        $this->dropColumn('whitebook_customer', 'phone');
        $this->dropColumn('whitebook_customer', 'extra');
    }

    public function down()
    {
        echo "m160430_130653_whitebook_customer cannot be reverted.\n";

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
