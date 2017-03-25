<?php

use yii\db\Migration;

class m160908_081655_payment_fees extends Migration
{
    public function up()
    {
        $this->addColumn('{{%payment_gateway}}', 'fees', $this->decimal(6,2)->after('percentage'));
        $this->addColumn('{{%order}}', 'order_gateway_fees', $this->decimal(6,2)->after('order_gateway_percentage'));
    }

    public function down()
    {
    
    }
}
