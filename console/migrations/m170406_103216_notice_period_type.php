<?php

use yii\db\Migration;

class m170406_103216_notice_period_type extends Migration
{
    public function up()
    {
        $this->addColumn(
            '{{%vendor_item}}', 
            'notice_period_type', 
            $this->string(100)->after('item_how_long_to_make')->defaultValue('Hour')
        );

        $this->addColumn(
            '{{%vendor_draft_item}}', 
            'notice_period_type', 
            $this->string(100)->after('item_how_long_to_make')->defaultValue('Hour')
        );
    }

    public function down()
    {
        echo "m170406_103216_notice_period_type cannot be reverted.\n";

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
