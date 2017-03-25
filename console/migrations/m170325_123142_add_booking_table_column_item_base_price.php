<?php

use yii\db\Migration;

class m170325_123142_add_booking_table_column_item_base_price extends Migration
{
    public function up()
    {
        $this->addColumn('whitebook_booking_item','item_base_price',$this->decimal(10,3)->after('price')->defaultValue(0.0));
    }

    public function down()
    {
        echo "m170325_123142_add_booking_table_column_item_base_price cannot be reverted.\n";

        return false;
    }
}
