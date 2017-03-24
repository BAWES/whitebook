<?php

use yii\db\Migration;

class m170221_100933_order_product_request_status extends Migration
{
    public function up()
    {
        $this->createTable('{{%order_request_status}}', [
            'request_id' => $this->primaryKey(),
            'order_id' => $this->integer(11)->notNull(),
            'request_status' => "ENUM('Pending', 'Approved','Declined') NOT NULL DEFAULT 'Pending'",
            'request_note' => $this->text(),
            'created_datetime' => $this->dateTime(),
            'modified_datetime' => $this->dateTime(),
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');
    }

    public function down()
    {
        $this->dropTable('{{%order_request_status}}');
    }
}
