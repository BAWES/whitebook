<?php

use yii\db\Migration;

class m170208_095555_draft_item_addon_options extends Migration
{
    public function up()
    {
        //new fields
        
        $this->addColumn('whitebook_vendor_draft_item', 'set_up_time', $this->string(256)->after('item_price_description_ar'));
        $this->addColumn('whitebook_vendor_draft_item', 'set_up_time_ar', $this->string(256)->after('set_up_time'));

        $this->addColumn('whitebook_vendor_draft_item', 'max_time', $this->string(256)->after('set_up_time'));
        $this->addColumn('whitebook_vendor_draft_item', 'max_time_ar', $this->string(256)->after('max_time'));

        $this->addColumn('whitebook_vendor_draft_item', 'requirements', $this->string(256)->after('max_time'));
        $this->addColumn('whitebook_vendor_draft_item', 'requirements_ar', $this->string(256)->after('requirements'));

        $this->addColumn('whitebook_vendor_draft_item', 'min_order_amount', $this->decimal(10,3)->after('item_price_per_unit'));

         $this->addColumn(
            'whitebook_vendor_draft_item', 
            'allow_special_request', 
            $this->smallInteger(1)->after('item_price_per_unit')
        );
        
        $this->addColumn(
            'whitebook_vendor_draft_item', 
            'have_female_service', 
            $this->smallInteger(1)->after('item_price_per_unit')
        );
        
        //add index to use it in foreign key 

        $this->createIndex ('vendor_draft_item_i_inx', 'whitebook_vendor_draft_item', 'item_id');

        //menu 

        $this->createTable('whitebook_vendor_draft_item_menu', [
            'draft_menu_id' => $this->primaryKey(),
            'item_id' => $this->integer(11) . ' UNSIGNED NULL',
            'menu_name' => $this->string(100),
            'menu_name_ar' => $this->string(100),
            'menu_type' => "ENUM('addons', 'options')",
            'min_quantity' => $this->integer(11),
            'max_quantity' => $this->integer(11),
            'quantity_type' => $this->string(100),
            'sort_order' => $this->integer(11)
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');

        $this->createIndex ('vendor_draft_item_menu_i_inx', 'whitebook_vendor_draft_item_menu', 'item_id');

        $this->addForeignKey ('vendor_draft_item_menu_i_fk', 'whitebook_vendor_draft_item_menu', 'item_id', 'whitebook_vendor_draft_item', 'item_id', 'SET NULL' , 'SET NULL');

        //menu items 

        $this->createTable('whitebook_vendor_draft_item_menu_item', [
            'draft_menu_item_id' => $this->primaryKey() ,
            'draft_menu_id' => $this->integer(11) . ' NULL',            
            'item_id' => $this->integer(11) . ' UNSIGNED NULL',
            'menu_item_name' => $this->string(100),            
            'menu_item_name_ar' => $this->string(100),            
            'price' => $this->decimal(10,3),             
            'hint' => $this->string(250),
            'hint_ar' => $this->string(250),
            'sort_order' => $this->integer(11)
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');

        $this->createIndex ('vendor_draft_item_menu_item_i_inx', 'whitebook_vendor_draft_item_menu_item', 'item_id');

        $this->createIndex ('vendor_draft_item_menu_item_m_inx', 'whitebook_vendor_draft_item_menu_item', 'draft_menu_id');

        $this->addForeignKey ('vendor_draft_item_menu_item_i_fk', 'whitebook_vendor_draft_item_menu_item', 'item_id', 'whitebook_vendor_draft_item', 'item_id', 'SET NULL' , 'SET NULL');

        $this->addForeignKey ('vendor_draft_item_menu_item_m_fk', 'whitebook_vendor_draft_item_menu_item', 'draft_menu_id', 'whitebook_vendor_draft_item_menu', 'draft_menu_id', 'SET NULL' , 'SET NULL');
    }
}
