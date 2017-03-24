<?php

use yii\db\Migration;

class m160402_142307_drop_whitebook_activity_log extends Migration
{
    public function up()
    {
        $this->dropTable('{{%activity_log}}');
    }

    public function down()
    {
        echo "m160402_142307_drop_whitebook_activity_log cannot be reverted.\n";

        return false;
    }
}
