<?php

use yii\db\Migration;

class m161019_085931_drop_table_whitebook_transaction extends Migration
{
    public function up()
    {
        $this->dropTable('whitebook_transaction');
    }

    public function down()
    {
    }
}
