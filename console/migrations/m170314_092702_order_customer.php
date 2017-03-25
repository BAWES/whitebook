<?php

use yii\db\Migration;

class m170314_092702_order_customer extends Migration
{
    public function up()
    {
        $this->dropForeignKey('fk-booking-customer_id', '{{%booking}}');
    }
}
