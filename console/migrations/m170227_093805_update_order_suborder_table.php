<?php

use yii\db\Migration;

class m170227_093805_update_order_suborder_table extends Migration
{

    public function up()
    {
        //whitebook_order
        $this->dropColumn('{{%order}}', 'order_payment_method');
        $this->dropColumn('{{%order}}', 'order_transaction_id');
        $this->dropColumn('{{%order}}', 'order_gateway_percentage');
        $this->dropColumn('{{%order}}', 'order_gateway_fees');
        $this->dropColumn('{{%order}}', 'order_gateway_total');

        //whitebook_suborder
        $this->addColumn('{{%suborder}}', 'suborder_payment_method',$this->string(128)->after('suborder_vendor_total'));
        $this->addColumn('{{%suborder}}', 'suborder_transaction_id',$this->string(128)->after('suborder_payment_method'));
        $this->addColumn('{{%suborder}}', 'suborder_gateway_percentage',$this->decimal(11,0)->after('suborder_transaction_id'));
        $this->addColumn('{{%suborder}}', 'suborder_gateway_fees',$this->decimal(6,2)->after('suborder_gateway_percentage'));
        $this->addColumn('{{%suborder}}', 'suborder_gateway_total',$this->decimal(11,2)->after('suborder_gateway_fees'));
    }

    public function down()
    {
        echo "m170227_093805_udpate_order_suborder_table cannot be reverted.\n";

        return false;
    }
}
