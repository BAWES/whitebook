<?php

use yii\db\Migration;

class m170118_085047_menu_item_changes extends Migration
{
    public function up()
    {
        $this->dropColumn('whitebook_vendor_item_menu_item', 'min_quantity');
        $this->dropColumn('whitebook_vendor_item_menu_item', 'max_quantity');

        $this->addColumn('whitebook_vendor_item_menu_item', 'hint_ar', $this->string(250)->after('hint'));        
    }
}
