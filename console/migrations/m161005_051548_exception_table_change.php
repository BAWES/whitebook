<?php

use yii\db\Migration;

class m161005_051548_exception_table_change extends Migration
{
    public function up()
    {
        $this->alterColumn('{{%vendor_item_capacity_exception}}','item_id',$this->integer(11));
    }

    public function down()
    {
        echo "m161005_051548_exception_table_change cannot be reverted.\n";

        return false;
    }
}
