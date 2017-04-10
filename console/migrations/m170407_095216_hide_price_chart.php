<?php

use yii\db\Migration;

class m170407_095216_hide_price_chart extends Migration
{
    public function up()
    {
        $this->addColumn(
            '{{%vendor_item}}', 
            'hide_price_chart', 
            $this->smallInteger(1)->after('item_base_price')->defaultValue(0)
        );

        $this->addColumn(
            '{{%vendor_draft_item}}', 
            'hide_price_chart', 
            $this->smallInteger(1)->after('item_base_price')->defaultValue(0)
        );
    }

    public function down()
    {
        echo "m170407_095216_hide_price_chart cannot be reverted.\n";

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
