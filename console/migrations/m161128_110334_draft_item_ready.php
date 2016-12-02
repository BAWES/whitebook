<?php

use yii\db\Migration;

class m161128_110334_draft_item_ready extends Migration
{
    public function up()
    {
        $this->addColumn('whitebook_vendor_draft_item', 'is_ready', $this->integer(1)->after('trash'));
    }

    public function down()
    {
        echo "m161128_110334_draft_item_ready cannot be reverted.\n";

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
