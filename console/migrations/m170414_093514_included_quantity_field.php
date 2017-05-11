<?php

use yii\db\Migration;

class m170414_093514_included_quantity_field extends Migration
{
    public function up()
    {
        $this->addColumn('whitebook_vendor_item','included_quantity',$this->integer(11)->defaultValue(0)->after('item_minimum_quantity_to_order'));
        $this->addColumn('whitebook_vendor_draft_item','included_quantity',$this->integer(11)->defaultValue(0)->after('item_minimum_quantity_to_order'));
        $this->execute('update whitebook_vendor_item set included_quantity = item_minimum_quantity_to_order, item_minimum_quantity_to_order = "0"');
        $this->execute('update whitebook_vendor_draft_item set included_quantity = item_minimum_quantity_to_order, item_minimum_quantity_to_order = "0";');
    }

    public function down()
    {
        $this->dropColumn('whitebook_vendor_item','included_quantity');
        $this->dropColumn('whitebook_vendor_draft_item','included_quantity');
    }
}
