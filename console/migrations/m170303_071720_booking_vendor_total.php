<?php

use yii\db\Migration;

class m170303_071720_booking_vendor_total extends Migration
{
    public function up()
    {
        $this->renameColumn('whitebook_booking', 'total _vendor', 'total_vendor');
    }

    public function down()
    {
        echo "m170303_071720_booking_vendor_total cannot be reverted.\n";

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
