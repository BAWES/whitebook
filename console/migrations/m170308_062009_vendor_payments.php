<?php

use yii\db\Migration;

class m170308_062009_vendor_payments extends Migration
{
    public function up()
    {
        $this->addColumn('whitebook_vendor_payment', 'booking_id', $this->integer(11)->after('vendor_id'));

        $this->addColumn('whitebook_vendor_payment', 'type', $this->smallInteger(1)->after('booking_id'));

        $this->createIndex ('ind-vendor_payments-booking_id', 'whitebook_vendor_payment', 'booking_id');

        $this->addForeignKey ('fk-vendor_payments-booking_id', 'whitebook_vendor_payment', 'booking_id', 'whitebook_booking', 'booking_id', 'SET NULL' , 'SET NULL');

        $this->dropTable('whitebook_vendor_account_payable');
    }

    public function down()
    {
        echo "m170308_062009_vendor_payments cannot be reverted.\n";

        return false;
    }
}
