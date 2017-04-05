<?php

use yii\db\Migration;

class m170405_091123_item_for_sale extends Migration
{
    public function up()
    {
        $this->dropColumn('{{%vendor_item}}', 'item_for_sale');
        $this->dropColumn('{{%vendor_draft_item}}', 'item_for_sale');
    }

    public function down()
    {
        echo "m170405_091123_item_for_sale cannot be reverted.\n";

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
