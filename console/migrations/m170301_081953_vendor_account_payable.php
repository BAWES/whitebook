<?php

use yii\db\Migration;

class m170301_081953_vendor_account_payable extends Migration
{
    public function up()
    {
        $this->createTable('{{%vendor_account_payable}}', [
            'payable_id' => $this->primaryKey(),
            'vendor_id' => $this->integer(11) . ' UNSIGNED NULL',
            'amount' => $this->decimal(11, 3)->notNull(),
            'description' => $this->text(),
            'created_datetime' => $this->dateTime(),
            'modified_datetime' => $this->dateTime(),
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');

        $this->createIndex ('vendor_account_payable_v_inx', '{{%vendor_account_payable}}', 'vendor_id');

        $this->addForeignKey ('vendor_account_payable_v_fk', '{{%vendor_account_payable}}', 'vendor_id', '{{%vendor}}', 'vendor_id', 'SET NULL' , 'SET NULL');
    }

    public function down()
    {
        $this->dropForeignKey(
            'vendor_account_payable_v_fk',
            '{{%vendor_account_payable}}'
        );
        
        $this->dropIndex(
            'vendor_account_payable_v_inx',
            '{{%vendor_account_payable}}'
        );

        $this->dropTable('{{%vendor_account_payable}}');
    }
}
