<?php

use yii\db\Migration;

class m170227_092907_update_request_table extends Migration
{
    public function up()
    {
        $this->renameColumn('{{%order_request_status}}', 'order_id', 'suborder_id');
    }

    public function down()
    {
        echo "m170227_092907_update_request_table cannot be reverted.\n";

        return false;
    }
}
