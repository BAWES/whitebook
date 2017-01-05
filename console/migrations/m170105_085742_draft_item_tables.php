<?php

use yii\db\Migration;

class m170105_085742_draft_item_tables extends Migration
{
    public function up()
    {   
        // Draft item to category 
        
        $this->createTable('whitebook_vendor_draft_item_to_category', [
            'dic_id' => $this->primaryKey(),
            'item_id' => $this->integer(11) . ' UNSIGNED NULL',
            'category_id' => $this->integer(11) . ' UNSIGNED NULL'
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');

        $this->createIndex ('vendor_draft_item_to_category_c_inx', 'whitebook_vendor_draft_item_to_category', 'category_id');

        $this->createIndex ('vendor_draft_item_to_category_i_inx', 'whitebook_vendor_draft_item_to_category', 'item_id');

        $this->addForeignKey ('vendor_draft_item_to_category_c_fk', 'whitebook_vendor_draft_item_to_category', 'category_id', 'whitebook_category', 'category_id', 'SET NULL' , 'SET NULL');
        
        $this->addForeignKey ('vendor_draft_item_to_category_i_fk', 'whitebook_vendor_draft_item_to_category', 'item_id', 'whitebook_vendor_item', 'item_id', 'SET NULL' , 'SET NULL');
       
        // Draft item pricing 

        $this->createTable('whitebook_vendor_draft_item_pricing', [
            'dp_id' => $this->primaryKey(),
            'item_id' => $this->integer(11) . ' UNSIGNED NULL',
            'range_from' => $this->integer(50),
            'range_to' => $this->integer(50),
            'pricing_quantity_ordered' => $this->integer(11),
            'pricing_price_per_unit' => $this->integer(11),
            'created_by' => $this->integer(11),
            'modified_by' => $this->integer(11), 
            'created_datetime' => $this->dateTime(), 
            'modified_datetime' => $this->dateTime(),
            'trash' => "enum('Default', 'Deleted')" 
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');

        $this->createIndex ('vendor_draft_item_pricing_item_inx', 'whitebook_vendor_draft_item_pricing', 'item_id');

        $this->addForeignKey ('vendor_draft_item_pricing_to_item_fk', 'whitebook_vendor_draft_item_pricing', 'item_id', 'whitebook_vendor_item', 'item_id', 'SET NULL' , 'SET NULL');

        // Draft item image 

        $this->createTable('whitebook_draft_image', [
            'di_id' => $this->primaryKey(),
            'item_id' => $this->integer(11) . ' UNSIGNED NULL',
            'image_user_id' => $this->integer(11),
            'image_path' => $this->string(128),
            'vendorimage_sort_order' => $this->integer(11)
        ]);

        $this->createIndex ('vendor_draft_image_item_inx', 'whitebook_draft_image', 'item_id');

        $this->addForeignKey ('whitebook_draft_image_item_fk', 'whitebook_draft_image', 'item_id', 'whitebook_vendor_item', 'item_id', 'SET NULL' , 'SET NULL');
    }
}
