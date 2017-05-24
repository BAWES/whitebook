<?php

use yii\db\Migration;

class m170524_065546_booking_group extends Migration
{
    public function up()
    {
        $this->createTable('{{%order}}', [
            'order_id' => $this->primaryKey(),
            'order_token' => $this->char(13),
            'created_datetime' => $this->dateTime(),
            'modified_datetime' => $this->dateTime(),
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');

        $this->addColumn('{{%booking}}', 'order_id', $this->integer(11)->after('booking_id'));

        $this->createIndex ('ind-booking-order_id', '{{%booking}}', 'order_id');

        $this->addForeignKey('booking_order_fk', '{{%booking}}', 'order_id', '{{%order}}', 'order_id', 'SET NULL' , 'SET NULL');        
    }
}
