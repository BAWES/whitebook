<?php

use yii\db\Migration;

class m170324_095715_vendor_base_price_changes extends Migration
{
    public function up()
    {
        $this->addColumn('whitebook_vendor_item','item_base_price',$this->decimal(10,3)->after('item_price_per_unit')->null());
        $this->addColumn('whitebook_vendor_draft_item','item_base_price',$this->decimal(10,3)->after('item_price_per_unit')->null());
    }

    public function down()
    {
        echo "m170324_095715_vendor_base_price_changes cannot be reverted.\n";

        return false;
    }
}
