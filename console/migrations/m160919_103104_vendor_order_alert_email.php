<?php

use yii\db\Migration;

class m160919_103104_vendor_order_alert_email extends Migration
{
    public function up()
    {
        $this->createTable('{{%vendor_order_alert_emails}}', [
            'voae_id' => $this->primaryKey(),
            'vendor_id' => $this->integer(11),
            'email_address' => $this->string(100)
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');
    }

    public function down()
    {
    }
}
