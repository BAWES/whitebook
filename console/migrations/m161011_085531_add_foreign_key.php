<?php

use yii\db\Migration;

class m161011_085531_add_foreign_key extends Migration
{
    public function up()
    {
        $this->execute("SET foreign_key_checks = 0;");

        $this->dropTable('{{%profile}}');        
        
        //to add foreign key, both column should be identical 
        $this->alterColumn ('{{%category_path}}', 'category_id', $this->integer(11) . ' UNSIGNED NULL');

        $this->addForeignKey ('category_fk', '{{%category_path}}', 'category_id', '{{%category}}', 'category_id', 'SET NULL' , 'SET NULL');

        //There seems to be no relation set between vendor, items, exceptions, dates, locations, etc.
        $this->alterColumn ('{{%vendor_packages}}', 'vendor_id', $this->integer(11) . ' UNSIGNED NULL');
        
        $this->addForeignKey ('vendor_package_fk', '{{%vendor_packages}}', 'vendor_id', '{{%vendor}}', 'vendor_id', 'SET NULL' , 'SET NULL');

        //vendor to item 
        $this->alterColumn ('{{%vendor_item}}', 'vendor_id', $this->integer(11) . ' UNSIGNED NULL');

        $this->addForeignKey ('vendor_item_fk', '{{%vendor_item}}', 'vendor_id', '{{%vendor}}', 'vendor_id', 'SET NULL' , 'SET NULL');

        //vendor to suborder 
        $this->alterColumn ('{{%suborder}}', 'vendor_id', $this->integer(11) . ' UNSIGNED NULL');

        $this->addForeignKey ('vendor_suborder_fk', '{{%suborder}}', 'vendor_id', '{{%vendor}}', 'vendor_id', 'SET NULL' , 'SET NULL');

        //vendot to alert emails 
        $this->alterColumn ('{{%vendor_order_alert_emails}}', 'vendor_id', $this->integer(11) . ' UNSIGNED NULL');

        $this->addForeignKey ('vendor_alert_emails_fk', '{{%vendor_order_alert_emails}}', 'vendor_id', '{{%vendor}}', 'vendor_id', 'SET NULL' , 'SET NULL');

        //vendor to priority items 
        $this->alterColumn ('{{%priority_item}}', 'vendor_id', $this->integer(11) . ' UNSIGNED NULL');

        $this->addForeignKey ('vendor_priority_items_fk', '{{%priority_item}}', 'vendor_id', '{{%vendor}}', 'vendor_id', 'SET NULL' , 'SET NULL');

        //vendor delivery location
        $this->alterColumn ('{{%vendor_location}}', 'vendor_id', $this->integer(11) . ' UNSIGNED NULL');

        $this->addForeignKey ('vendor_location_fk', '{{%vendor_location}}', 'vendor_id', '{{%vendor}}', 'vendor_id', 'SET NULL' , 'SET NULL');
        
        //vendor to block date 
        $this->alterColumn ('{{%vendor_blocked_date}}', 'vendor_id', $this->integer(11) . ' UNSIGNED NULL');

        $this->addForeignKey ('vendor_block_date_fk', '{{%vendor_blocked_date}}', 'vendor_id', '{{%vendor}}', 'vendor_id', 'SET NULL' , 'SET NULL');
        
        //vendor category to vendor 
        $this->alterColumn ('{{%vendor_category}}', 'vendor_id', $this->integer(11) . ' UNSIGNED NULL');

        $this->addForeignKey ('vendor_category_to_vendor_fk', '{{%vendor_category}}', 'vendor_id', '{{%vendor}}', 'vendor_id', 'SET NULL' , 'SET NULL');

        //vendor category to category
        $this->alterColumn ('{{%vendor_category}}', 'category_id', $this->integer(11) . ' UNSIGNED NULL');

        $this->addForeignKey ('vendor_category_to_category_fk', '{{%vendor_category}}', 'category_id', '{{%category}}', 'category_id', 'SET NULL' , 'SET NULL');
        
        //Same for priority items/feature group items/ and vendor items

        //{{%vendor_item_to_category to category 
        $this->alterColumn ('{{%vendor_item_to_category}}', 'category_id', $this->integer(11) . ' UNSIGNED NULL');

        $this->addForeignKey ('vendor_item_to_category_c_fk', '{{%vendor_item_to_category}}', 'category_id', '{{%category}}', 'category_id', 'SET NULL' , 'SET NULL');

        //{{%vendor_item_to_category to item
        $this->alterColumn ('{{%vendor_item_to_category}}', 'item_id', $this->integer(11) . ' UNSIGNED NULL');

        $this->addForeignKey ('vendor_item_to_category_i_fk', '{{%vendor_item_to_category}}', 'item_id', '{{%item}}', 'item_id', 'SET NULL' , 'SET NULL');

        $this->alterColumn ('{{%event_item_link}}', 'item_id', $this->integer(11) . ' UNSIGNED NULL');

        $this->addForeignKey ('vendor_event_item_i_fk', '{{%event_item_link}}', 'item_id', '{{%item}}', 'item_id', 'SET NULL' , 'SET NULL');

        $this->alterColumn ('{{%event_item_link}}', 'event_id', $this->integer(11) . ' UNSIGNED NULL');

        $this->addForeignKey ('vendor_event_item_e_fk', '{{%event_item_link}}', 'event_id', '{{%events}}', 'event_id', 'SET NULL' , 'SET NULL');
        
        $this->alterColumn ('{{%vendor_item_image}}', 'item_id', $this->integer(11) . ' UNSIGNED NULL');

        $this->addForeignKey ('vendor_item_image_fk', '{{%vendor_item_image}}', 'item_id', '{{%vendor_item}}', 'item_id', 'SET NULL' , 'SET NULL');

        $this->alterColumn ('{{%wishlist}}', 'item_id', $this->integer(11) . ' UNSIGNED NULL');

        $this->addForeignKey ('wishlist_to_item_fk', '{{%wishlist}}', 'item_id', '{{%vendor_item}}', 'item_id', 'SET NULL' , 'SET NULL');

        $this->alterColumn ('{{%wishlist}}', 'customer_id', $this->integer(11) . ' UNSIGNED NULL');

        $this->addForeignKey ('wishlist_to_customer_fk', '{{%wishlist}}', 'customer_id', '{{%customer}}', 'customer_id', 'SET NULL' , 'SET NULL');
                
        //{{%vendor_item_capacity_exception - item_id 
        $this->alterColumn ('{{%vendor_item_capacity_exception}}', 'item_id', $this->integer(11) . ' UNSIGNED NULL');

        $this->addForeignKey ('item_capacity_exception_to_item_fk', '{{%vendor_item_capacity_exception}}', 'item_id', '{{%vendor_item}}', 'item_id', 'SET NULL' , 'SET NULL');
            
        //{{%vendor_item_pricing - item_id 
        $this->alterColumn ('{{%vendor_item_pricing}}', 'item_id', $this->integer(11) . ' UNSIGNED NULL');

        $this->addForeignKey ('vendor_item_pricing_to_item_fk', '{{%vendor_item_pricing}}', 'item_id', '{{%vendor_item}}', 'item_id', 'SET NULL' , 'SET NULL');

        //{{%vendor_item_theme - item_id 
        $this->alterColumn ('{{%vendor_item_theme}}', 'item_id', $this->integer(11) . ' UNSIGNED NULL');

        $this->addForeignKey ('vendor_item_theme_i_fk', '{{%vendor_item_theme}}', 'item_id', '{{%vendor_item}}', 'item_id', 'SET NULL' , 'SET NULL');

        //{{%vendor_item_theme - theme_id 
        $this->alterColumn ('{{%vendor_item_theme}}', 'theme_id', $this->integer(11) . ' UNSIGNED NULL');

        $this->addForeignKey ('vendor_item_theme_t_fk', '{{%vendor_item_theme}}', 'theme_id', '{{%theme}}', 'theme_id', 'SET NULL' , 'SET NULL');
 
        //{{%vendor_item}} - type_id 
        $this->alterColumn ('{{%vendor_item}}', 'type_id', $this->integer(11) . ' UNSIGNED NULL');

        $this->addForeignKey ('vendor_item_type_fk', '{{%vendor_item}}', 'type_id', '{{%item_type}}', 'type_id', 'SET NULL' , 'SET NULL');

        //whitebook_feature_group_item - vendor_id 
        $this->alterColumn ('{{%feature_group_item}}', 'vendor_id', $this->integer(11) . ' UNSIGNED NULL');

        $this->addForeignKey ('feature_group_item_v_fk', '{{%feature_group_item}}', 'vendor_id', '{{%vendor}}', 'vendor_id', 'SET NULL' , 'SET NULL');

        //whitebook_feature_group_item - item_id 
        $this->alterColumn ('{{%feature_group_item}}', 'item_id', $this->integer(11) . ' UNSIGNED NULL');

        $this->addForeignKey ('feature_group_item_i_fk', '{{%feature_group_item}}', 'item_id', '{{%vendor_item}}', 'item_id', 'SET NULL' , 'SET NULL');

        //whitebook_feature_group_item - group id 
        $this->alterColumn ('{{%feature_group_item}}', 'group_id', $this->integer(11) . ' UNSIGNED NULL');

        $this->addForeignKey ('feature_group_item_g_fk', '{{%feature_group_item}}', 'group_id', '{{%feature_group}}', 'group_id', 'SET NULL' , 'SET NULL');

        //whitebook_priority_item - item_id
        $this->alterColumn ('{{%priority_item}}', 'item_id', $this->integer(11) . ' UNSIGNED NULL');

        $this->addForeignKey ('priority_item_i_fk', '{{%priority_item}}', 'item_id', '{{%vendor_item}}', 'item_id', 'SET NULL' , 'SET NULL');

        //whitebook_priority_item - vendor_id 
        $this->alterColumn ('{{%priority_item}}', 'vendor_id', $this->integer(11) . ' UNSIGNED NULL');

        $this->addForeignKey ('priority_item_v_fk', '{{%priority_item}}', 'vendor_id', '{{%vendor}}', 'vendor_id', 'SET NULL' , 'SET NULL');

        //whitebook_suborder_item_purchase - suborder_id 
        $this->alterColumn ('{{%suborder_item_purchase}}', 'suborder_id', $this->integer(11) . ' UNSIGNED NULL');

        $this->addForeignKey ('suborder_item_purchase_s_fk', '{{%suborder_item_purchase}}', 'suborder_id', '{{%suborder}}', 'suborder_id', 'SET NULL' , 'SET NULL');

        //whitebook_suborder_item_purchase - timeslot 
        $this->alterColumn ('{{%suborder_item_purchase}}', 'timeslot_id', $this->integer(11) . ' UNSIGNED NULL');

        $this->addForeignKey ('suborder_item_purchase_t_fk', '{{%suborder_item_purchase}}', 'timeslot_id', '{{%vendor_delivery_timeslot}}', 'timeslot_id', 'SET NULL' , 'SET NULL');

        //whitebook_suborder_item_purchase - item 

        $this->alterColumn ('{{%suborder_item_purchase}}', 'item_id', $this->integer(11) . ' UNSIGNED NULL');

        $this->addForeignKey ('suborder_item_purchase_i_fk', '{{%suborder_item_purchase}}', 'item_id', '{{%vendor_item}}', 'item_id', 'SET NULL' , 'SET NULL');

        //whitebook_suborder_item_purchase - area 
        $this->alterColumn ('{{%suborder_item_purchase}}', 'area_id', $this->integer(11) . ' UNSIGNED NULL');

        $this->alterColumn ('{{%location}}', 'id', $this->integer(11) . ' UNSIGNED');

        $this->addForeignKey ('suborder_item_purchase_area_fk', '{{%suborder_item_purchase}}', 'area_id', '{{%location}}', 'id', 'SET NULL' , 'SET NULL');

        //whitebook_suborder_item_purchase - address 
        $this->alterColumn ('{{%suborder_item_purchase}}', 'address_id', $this->integer(11) . ' UNSIGNED NULL');

        $this->addForeignKey ('suborder_item_purchase_address_fk', '{{%suborder_item_purchase}}', 'address_id', '{{%customer_address}}', 'address_id', 'SET NULL' , 'SET NULL');

        $this->execute("SET foreign_key_checks = 1;");
    }

    public function down()
    {
       
    }
}
