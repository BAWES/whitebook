<?php

use yii\db\Migration;

class m170301_071636_update_request_table_status_field extends Migration
{
    public function up()
    {
        $this->alterColumn('whitebook_order_request_status','request_status' ,"ENUM('Pending', 'Approved','Declined','Expired') NOT NULL DEFAULT 'Pending'");
        $this->addColumn('whitebook_order_request_status','notification_status' ,$this->boolean()->after('expired_on')->defaultValue('0')->notNull());
    }

    public function down()
    {
        echo "m170301_071636_update_request_table_status_field cannot be reverted.\n";

        return false;
    }

}
