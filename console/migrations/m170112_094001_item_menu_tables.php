<?php

use yii\db\Migration;

class m170112_094001_item_menu_tables extends Migration
{
    public function up()
    {                
        //menu 

        $this->createTable('whitebook_vendor_item_menu', [
            'menu_id' => $this->primaryKey(),
            'item_id' => $this->integer(11) . ' UNSIGNED NULL',
            'menu_name' => $this->string(100),
            'menu_name_ar' => $this->string(100)
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');

        $this->createIndex ('vendor_item_menu_i_inx', 'whitebook_vendor_item_menu', 'item_id');

        $this->addForeignKey ('vendor_item_menu_i_fk', 'whitebook_vendor_item_menu', 'item_id', 'whitebook_vendor_item', 'item_id', 'SET NULL' , 'SET NULL');

        //menu items 

        $this->createTable('whitebook_vendor_item_menu_item', [
            'menu_item_id' => $this->primaryKey() ,
            'item_id' => $this->integer(11) . ' UNSIGNED NULL',
            'menu_id' => $this->integer(11) . ' NULL',            
            'menu_item_name' => $this->string(100),            
            'menu_item_name_ar' => $this->string(100),            
            'min_quantity' => $this->integer(11),            
            'max_quantity' => $this->integer(11),            
            'price' => $this->decimal(10,3),             
            'hint' => $this->string(250)
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');

        $this->createIndex ('vendor_item_menu_item_i_inx', 'whitebook_vendor_item_menu_item', 'item_id');

        $this->createIndex ('vendor_item_menu_item_m_inx', 'whitebook_vendor_item_menu_item', 'menu_id');

        $this->addForeignKey ('vendor_item_menu_item_i_fk', 'whitebook_vendor_item_menu_item', 'item_id', 'whitebook_vendor_item', 'item_id', 'SET NULL' , 'SET NULL');

        $this->addForeignKey ('vendor_item_menu_item_m_fk', 'whitebook_vendor_item_menu_item', 'menu_id', 'whitebook_vendor_item_menu', 'menu_id', 'SET NULL' , 'SET NULL');

        //cart menu item 

        $this->createTable('whitebook_customer_cart_menu_item', [
            'cart_menu_item_id' => $this->primaryKey(),
            'cart_id' => $this->integer(11). ' UNSIGNED NULL',
            'menu_id' => $this->integer(11). ' NULL',  
            'menu_item_id' => $this->integer(11). ' NULL',            
            'quantity' => $this->integer(11),            
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');    
        
        $this->createIndex ('customer_cart_menu_item_c_inx', 'whitebook_customer_cart_menu_item', 'cart_id');

        $this->createIndex ('customer_cart_menu_item_m_inx', 'whitebook_customer_cart_menu_item', 'menu_id');

        $this->createIndex ('customer_cart_menu_item_mi_inx', 'whitebook_customer_cart_menu_item', 'menu_item_id');

        $this->addForeignKey ('customer_cart_menu_item_c_fk', 'whitebook_customer_cart_menu_item', 'cart_id', 'whitebook_customer_cart', 'cart_id', 'SET NULL' , 'SET NULL');

        $this->addForeignKey ('customer_cart_menu_item_m_fk', 'whitebook_customer_cart_menu_item', 'menu_id', 'whitebook_vendor_item_menu', 'menu_id', 'SET NULL' , 'SET NULL');

        $this->addForeignKey ('customer_cart_menu_item_mi_fk', 'whitebook_customer_cart_menu_item', 'menu_item_id', 'whitebook_vendor_item_menu_item', 'menu_item_id', 'SET NULL' , 'SET NULL');

        //to save order related data  

        $this->createTable('whitebook_suborder_item_menu', [
            'suborder_item_menu_id' => $this->primaryKey(),
            'purchase_id' => $this->integer(11) . ' UNSIGNED NULL',
            'menu_id' => $this->integer(11) . ' NULL',
            'menu_item_id' => $this->integer(11) . ' NULL',
            'menu_name' => $this->string(100), 
            'menu_name_ar' => $this->string(100), 
            'menu_item_name' => $this->string(100), 
            'menu_item_name_ar' => $this->string(100),            
            'quantity' => $this->integer(11),   
            'price' => $this->decimal(10, 3),  
            'total' => $this->decimal(10, 3)           
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');    

        $this->createIndex ('suborder_item_menu_p_inx', 'whitebook_suborder_item_menu', 'purchase_id');
        $this->createIndex ('suborder_item_menu_m_inx', 'whitebook_suborder_item_menu', 'menu_id');
        $this->createIndex ('suborder_item_menu_mi_inx', 'whitebook_suborder_item_menu', 'menu_item_id');
        
        $this->addForeignKey ('suborder_item_menu_p_fk', 'whitebook_suborder_item_menu', 'purchase_id', 'whitebook_suborder_item_purchase', 'purchase_id', 'SET NULL' , 'SET NULL');

        $this->addForeignKey ('suborder_item_menu_m_fk', 'whitebook_suborder_item_menu', 'menu_id', 'whitebook_vendor_item_menu', 'menu_id', 'SET NULL' , 'SET NULL');

        $this->addForeignKey ('suborder_item_menu_mi_fk', 'whitebook_suborder_item_menu', 'menu_item_id', 'whitebook_vendor_item_menu_item', 'menu_item_id', 'SET NULL' , 'SET NULL');  
    }
}
