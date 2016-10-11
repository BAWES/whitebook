<?php

use yii\db\Migration;

class m161004_081233_category_refactor extends Migration
{
    public function up()
    {
        $this->createTable('whitebook_vendor_category', [
            'id' => $this->primaryKey(),
            'category_id' => $this->integer(11),
            'vendor_id' => $this->integer(11)
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');
        
        $this->dropColumn('whitebook_vendor', 'category_id');

        $this->dropColumn('whitebook_vendor_item', 'category_id');
        $this->dropColumn('whitebook_vendor_item', 'subcategory_id');
        $this->dropColumn('whitebook_vendor_item', 'child_category');
    }

    public function down()
    {
    
    }
}
