<?php

use yii\db\Migration;

class m161123_085722_vendor_phone extends Migration
{
    public function up()
    {
        $this->dropColumn('whitebook_vendor', 'vendor_public_phone');

        $this->createTable('whitebook_vendor_phone_no', [
            'phone_no_id' => $this->primaryKey(),
            'vendor_id' => $this->integer(11).' UNSIGNED NULL',
            'phone_no' => $this->string(15),
            'type' => "ENUM('Office', 'Mobile', 'Fax', 'Whatsapp')", 
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');

        $this->addForeignKey ('vendor_phone_no_fk', 'whitebook_vendor_phone_no', 'vendor_id', 'whitebook_vendor', 'vendor_id', 'SET NULL' , 'SET NULL');
    }   

    public function down()
    {

    }
}
