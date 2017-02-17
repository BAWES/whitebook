<?php

use yii\db\Migration;

class m170217_105706_item_increment extends Migration
{
    public function up()
    {
        $this->addColumn('whitebook_vendor_item', 'minimum_increment', $this->integer(11)->after('item_minimum_quantity_to_order')); 
        
        $this->addColumn('whitebook_vendor_draft_item', 'minimum_increment', $this->integer(11)->after('item_minimum_quantity_to_order')); 
    }
}
