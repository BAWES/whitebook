<?php

use yii\db\Migration;

class m160922_110952_remove_vendor_delivery_charges_field extends Migration
{
    public function up()
    {
        $this->dropColumn('{{%vendor}}', 'vendor_delivery_charge');
    }

    public function down()
    {
        $this->alterColumn('{{%vendor}}', 'vendor_delivery_charge', $this->decimal(11,0));

        return false;
    }
}
