<?php

use yii\db\Migration;

class m160908_081655_payment_fees extends Migration
{
    public function up()
    {
        $this->addColumn('whitebook_payment_gateway', 'fees', $this->decimal(6,2)->after('percentage'));
        $this->addColumn('whitebook_order', 'order_gateway_fees', $this->decimal(6,2)->after('order_gateway_percentage'));
    }

    public function down()
    {
    
    }
}
