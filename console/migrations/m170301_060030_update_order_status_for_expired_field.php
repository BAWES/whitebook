<?php

use yii\db\Migration;

class m170301_060030_update_order_status_for_expired_field extends Migration
{
    public function up()
    {
        $this->addColumn('whitebook_order_request_status', 'expired_on', $this->dateTime()->after('request_note'));
    }

    public function down()
    {
        echo "m170301_060030_update_order_status_for_expired_field cannot be reverted.\n";

        return false;
    }
}
