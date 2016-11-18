<?php

use yii\db\Migration;

class m161118_102245_priority_items extends Migration
{
    public function up()
    {
        $this->dropForeignKey('vendor_priority_items_fk', 'whitebook_priority_item');
        $this->dropForeignKey('whitebook_priority_item_v_fk', 'whitebook_priority_item');

        $this->truncateTable('whitebook_priority_item');

        $this->dropColumn('whitebook_priority_item', 'vendor_id');
        $this->dropColumn('whitebook_priority_item', 'category_id');
        $this->dropColumn('whitebook_priority_item', 'subcategory_id');
        $this->dropColumn('whitebook_priority_item', 'child_category');


        



    }
}
