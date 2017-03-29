<?php

use yii\db\Migration;

class m170329_065136_booking_payment extends Migration
{
    public function up()
    {
        $this->addColumn('{{%vendor_payment}}', 'transfer_id', $this->integer(11)->after('description'));

        $this->createIndex('ind-vendor_payment-transfer_id', '{{%vendor_payment}}', 'transfer_id');

        $this->addForeignKey ('fk-vendor_payment-transfer_id', '{{%vendor_payment}}', 'transfer_id', '{{%vendor_payment}}', 'payment_id', 'SET NULL' , 'SET NULL');
    }

    public function down()
    {
        echo "m170329_065136_booking_payment cannot be reverted.\n";

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
