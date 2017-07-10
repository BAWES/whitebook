<?php

use yii\db\Migration;

class m170710_174419_price_description extends Migration
{
    public function up()
    {
        $this->dropColumn('whitebook_vendor_draft_item', 'item_price_description');    
        $this->dropColumn('whitebook_vendor_draft_item', 'item_price_description_ar');    
        $this->dropColumn('whitebook_vendor_item', 'item_price_description');    
        $this->dropColumn('whitebook_vendor_item', 'item_price_description_ar');    
    }

    public function down()
    {
        echo "m170710_174419_price_description cannot be reverted.\n";

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
