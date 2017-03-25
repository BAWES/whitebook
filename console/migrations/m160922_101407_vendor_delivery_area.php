<?php

use yii\db\Migration;

class m160922_101407_vendor_delivery_area extends Migration
{
    public function up()
    {
        $this->alterColumn('{{%vendor_location}}', 'delivery_price', $this->decimal(10,2));
    }

    public function down()
    {

    }
}
