<?php

use yii\db\Migration;

class m170301_102221_vendor_payment extends Migration
{
    public function up()
    {
        $this->createTable('whitebook_vendor_payment', [
            'payment_id' => $this->primaryKey(),
            'vendor_id' => $this->integer(11) . ' UNSIGNED NULL',
            'amount' => $this->decimal(11, 3)->notNull(),
            'description' => $this->text(),
            'created_datetime' => $this->dateTime(),
            'modified_datetime' => $this->dateTime(),
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');

        $this->createIndex ('vendor_payment_v_inx', 'whitebook_vendor_payment', 'vendor_id');

        $this->addForeignKey ('vendor_payment_v_fk', 'whitebook_vendor_payment', 'vendor_id', 'whitebook_vendor', 'vendor_id', 'SET NULL' , 'SET NULL');
    }

    public function down()
    {
        $this->dropForeignKey(
            'vendor_payment_v_fk',
            'whitebook_vendor_payment'
        );
        
        $this->dropIndex(
            'vendor_payment_v_inx',
            'whitebook_vendor_payment'
        );

        $this->dropTable('whitebook_vendor_payment');
    }
}
