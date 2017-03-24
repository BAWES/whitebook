<?php

use yii\db\Migration;

class m160912_072136_gateway_total_field extends Migration
{
    public function up()
    {
        $this->alterColumn('{{%order}}', 'order_gateway_total', $this->decimal(11,2));
    }

    public function down()
    {

    }
}
