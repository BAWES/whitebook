<?php

use yii\db\Migration;

class m170104_102216_hide_incomplete_item extends Migration
{
    public function up()
    {
        $this->addColumn(
            'whitebook_vendor_item', 
            'hide_from_admin', 
            $this->smallInteger(1)->after('item_status')->defaultValue(0)
        );
    } 

    public function down()
    {
        echo "m170104_102216_hide_incomplete_item cannot be reverted.\n";

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
