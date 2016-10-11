<?php

use yii\db\Migration;

class m161011_085531_add_foreign_key extends Migration
{
    public function up()
    {
        $this->execute("SET foreign_key_checks = 0;");

        $this->dropTable('whitebook_profile');        
        
        //to add foreign key, both column should be identical 
        $this->alterColumn ('whitebook_category_path', 'category_id', $this->integer(11) . ' UNSIGNED NULL');

        $this->addForeignKey ('category_fk', 'whitebook_category_path', 'category_id', 'whitebook_category', 'category_id', 'SET NULL' , 'SET NULL');

        //There seems to be no relation set between vendor, items, exceptions, dates, locations, etc.
        $this->alterColumn ('whitebook_vendor_packages', 'vendor_id', $this->integer(11) . ' UNSIGNED NULL');
        
        $this->addForeignKey ('vendor_package_fk', 'whitebook_vendor_packages', 'vendor_id', 'whitebook_vendor', 'vendor_id', 'SET NULL' , 'SET NULL');

        //vendor to item 
        $this->alterColumn ('whitebook_vendor_item', 'vendor_id', $this->integer(11) . ' UNSIGNED NULL');

        $this->addForeignKey ('vendor_item_fk', 'whitebook_vendor_item', 'vendor_id', 'whitebook_vendor', 'vendor_id', 'SET NULL' , 'SET NULL');

        //vendor to suborder 
        $this->alterColumn ('whitebook_suborder', 'vendor_id', $this->integer(11) . ' UNSIGNED NULL');

        $this->addForeignKey ('vendor_suborder_fk', 'whitebook_suborder', 'vendor_id', 'whitebook_vendor', 'vendor_id', 'SET NULL' , 'SET NULL');

        //vendot to alert emails 
        $this->alterColumn ('whitebook_vendor_order_alert_emails', 'vendor_id', $this->integer(11) . ' UNSIGNED NULL');

        $this->addForeignKey ('vendor_alert_emails_fk', 'whitebook_vendor_order_alert_emails', 'vendor_id', 'whitebook_vendor', 'vendor_id', 'SET NULL' , 'SET NULL');

        //vendor to priority items 
        $this->alterColumn ('whitebook_priority_item', 'vendor_id', $this->integer(11) . ' UNSIGNED NULL');

        $this->addForeignKey ('vendor_priority_items_fk', 'whitebook_priority_item', 'vendor_id', 'whitebook_vendor', 'vendor_id', 'SET NULL' , 'SET NULL');

        //vendor delivery location
        $this->alterColumn ('whitebook_vendor_location', 'vendor_id', $this->integer(11) . ' UNSIGNED NULL');

        $this->addForeignKey ('vendor_location_fk', 'whitebook_vendor_location', 'vendor_id', 'whitebook_vendor', 'vendor_id', 'SET NULL' , 'SET NULL');
        
        //vendor to block date 
        $this->alterColumn ('whitebook_vendor_blocked_date', 'vendor_id', $this->integer(11) . ' UNSIGNED NULL');

        $this->addForeignKey ('vendor_block_date_fk', 'whitebook_vendor_blocked_date', 'vendor_id', 'whitebook_vendor', 'vendor_id', 'SET NULL' , 'SET NULL');
        
        //vendor category to vendor 
        $this->alterColumn ('whitebook_vendor_category', 'vendor_id', $this->integer(11) . ' UNSIGNED NULL');

        $this->addForeignKey ('vendor_category_to_vendor_fk', 'whitebook_vendor_category', 'vendor_id', 'whitebook_vendor', 'vendor_id', 'SET NULL' , 'SET NULL');

        //vendor category to category
        $this->alterColumn ('whitebook_vendor_category', 'category_id', $this->integer(11) . ' UNSIGNED NULL');

        $this->addForeignKey ('vendor_category_to_category_fk', 'whitebook_vendor_category', 'category_id', 'whitebook_category', 'category_id', 'SET NULL' , 'SET NULL');
        
        //Same for priority items/feature group items/ and vendor items

        //whitebook_vendor_item_to_category to category 
        $this->alterColumn ('whitebook_vendor_item_to_category', 'category_id', $this->integer(11) . ' UNSIGNED NULL');

        $this->addForeignKey ('vendor_item_to_category_c_fk', 'whitebook_vendor_item_to_category', 'category_id', 'whitebook_category', 'category_id', 'SET NULL' , 'SET NULL');

        //whitebook_vendor_item_to_category to item
        $this->alterColumn ('whitebook_vendor_item_to_category', 'item_id', $this->integer(11) . ' UNSIGNED NULL');

        $this->addForeignKey ('vendor_item_to_category_i_fk', 'whitebook_vendor_item_to_category', 'item_id', 'whitebook_item', 'item_id', 'SET NULL' , 'SET NULL');

        $this->alterColumn ('whitebook_event_item_link', 'item_id', $this->integer(11) . ' UNSIGNED NULL');

        $this->addForeignKey ('vendor_event_item_i_fk', 'whitebook_event_item_link', 'item_id', 'whitebook_item', 'item_id', 'SET NULL' , 'SET NULL');

        $this->alterColumn ('whitebook_event_item_link', 'event_id', $this->integer(11) . ' UNSIGNED NULL');

        $this->addForeignKey ('vendor_event_item_e_fk', 'whitebook_event_item_link', 'event_id', 'whitebook_events', 'event_id', 'SET NULL' , 'SET NULL');
        
        $this->alterColumn ('whitebook_vendor_item_image', 'item_id', $this->integer(11) . ' UNSIGNED NULL');

        $this->addForeignKey ('vendor_item_image_fk', 'whitebook_vendor_item_image', 'item_id', 'whitebook_vendor_item', 'item_id', 'SET NULL' , 'SET NULL');

        $this->alterColumn ('whitebook_wishlist', 'item_id', $this->integer(11) . ' UNSIGNED NULL');

        $this->addForeignKey ('wishlist_to_item_fk', 'whitebook_wishlist', 'item_id', 'whitebook_vendor_item', 'item_id', 'SET NULL' , 'SET NULL');

        $this->alterColumn ('whitebook_wishlist', 'customer_id', $this->integer(11) . ' UNSIGNED NULL');

        $this->addForeignKey ('wishlist_to_customer_fk', 'whitebook_wishlist', 'customer_id', 'whitebook_customer', 'customer_id', 'SET NULL' , 'SET NULL');
                
        //whitebook_vendor_item_capacity_exception - item_id 
        $this->alterColumn ('whitebook_vendor_item_capacity_exception', 'item_id', $this->integer(11) . ' UNSIGNED NULL');

        $this->addForeignKey ('item_capacity_exception_to_item_fk', 'whitebook_vendor_item_capacity_exception', 'item_id', 'whitebook_vendor_item', 'item_id', 'SET NULL' , 'SET NULL');
            
        //whitebook_vendor_item_pricing - item_id 
        $this->alterColumn ('whitebook_vendor_item_pricing', 'item_id', $this->integer(11) . ' UNSIGNED NULL');

        $this->addForeignKey ('vendor_item_pricing_to_item_fk', 'whitebook_vendor_item_pricing', 'item_id', 'whitebook_vendor_item', 'item_id', 'SET NULL' , 'SET NULL');

        //whitebook_vendor_item_theme - item_id 
        $this->alterColumn ('whitebook_vendor_item_theme', 'item_id', $this->integer(11) . ' UNSIGNED NULL');

        $this->addForeignKey ('whitebook_vendor_item_theme_i_fk', 'whitebook_vendor_item_theme', 'item_id', 'whitebook_vendor_item', 'item_id', 'SET NULL' , 'SET NULL');

        //whitebook_vendor_item_theme - theme_id 
        $this->alterColumn ('whitebook_vendor_item_theme', 'theme_id', $this->integer(11) . ' UNSIGNED NULL');

        $this->addForeignKey ('whitebook_vendor_item_theme_t_fk', 'whitebook_vendor_item_theme', 'theme_id', 'whitebook_theme', 'theme_id', 'SET NULL' , 'SET NULL');
 
        //whitebook_vendor_item - type_id 
        $this->alterColumn ('whitebook_vendor_item', 'type_id', $this->integer(11) . ' UNSIGNED NULL');

        $this->addForeignKey ('whitebook_vendor_item_type_fk', 'whitebook_vendor_item', 'type_id', 'whitebook_item_type', 'type_id', 'SET NULL' , 'SET NULL');

        //whitebook_feature_group_item - vendor_id 
        $this->alterColumn ('whitebook_feature_group_item', 'vendor_id', $this->integer(11) . ' UNSIGNED NULL');

        $this->addForeignKey ('whitebook_feature_group_item_v_fk', 'whitebook_feature_group_item', 'vendor_id', 'whitebook_vendor', 'vendor_id', 'SET NULL' , 'SET NULL');

        //whitebook_feature_group_item - item_id 
        $this->alterColumn ('whitebook_feature_group_item', 'item_id', $this->integer(11) . ' UNSIGNED NULL');

        $this->addForeignKey ('whitebook_feature_group_item_i_fk', 'whitebook_feature_group_item', 'item_id', 'whitebook_vendor_item', 'item_id', 'SET NULL' , 'SET NULL');

        //whitebook_feature_group_item - group id 
        $this->alterColumn ('whitebook_feature_group_item', 'group_id', $this->integer(11) . ' UNSIGNED NULL');

        $this->addForeignKey ('whitebook_feature_group_item_g_fk', 'whitebook_feature_group_item', 'group_id', 'whitebook_feature_group', 'group_id', 'SET NULL' , 'SET NULL');

        //whitebook_priority_item - item_id
        $this->alterColumn ('whitebook_priority_item', 'item_id', $this->integer(11) . ' UNSIGNED NULL');

        $this->addForeignKey ('whitebook_priority_item_i_fk', 'whitebook_priority_item', 'item_id', 'whitebook_vendor_item', 'item_id', 'SET NULL' , 'SET NULL');

        //whitebook_priority_item - vendor_id 
        $this->alterColumn ('whitebook_priority_item', 'vendor_id', $this->integer(11) . ' UNSIGNED NULL');

        $this->addForeignKey ('whitebook_priority_item_v_fk', 'whitebook_priority_item', 'vendor_id', 'whitebook_vendor', 'vendor_id', 'SET NULL' , 'SET NULL');

        //whitebook_suborder_item_purchase - suborder_id 
        $this->alterColumn ('whitebook_suborder_item_purchase', 'suborder_id', $this->integer(11) . ' UNSIGNED NULL');

        $this->addForeignKey ('whitebook_suborder_item_purchase_s_fk', 'whitebook_suborder_item_purchase', 'suborder_id', 'whitebook_suborder', 'suborder_id', 'SET NULL' , 'SET NULL');

        //whitebook_suborder_item_purchase - timeslot 
        $this->alterColumn ('whitebook_suborder_item_purchase', 'timeslot_id', $this->integer(11) . ' UNSIGNED NULL');

        $this->addForeignKey ('whitebook_suborder_item_purchase_t_fk', 'whitebook_suborder_item_purchase', 'timeslot_id', 'whitebook_vendor_delivery_timeslot', 'timeslot_id', 'SET NULL' , 'SET NULL');

        //whitebook_suborder_item_purchase - item 
        $this->alterColumn ('whitebook_suborder_item_purchase', 'item_id', $this->integer(11) . ' UNSIGNED NULL');

        $this->addForeignKey ('whitebook_suborder_item_purchase_i_fk', 'whitebook_suborder_item_purchase', 'item_id', 'whitebook_vendor_item', 'item_id', 'SET NULL' , 'SET NULL');

        //whitebook_suborder_item_purchase - area 
        $this->alterColumn ('whitebook_suborder_item_purchase', 'area_id', $this->integer(11) . ' UNSIGNED NULL');

        $this->alterColumn ('whitebook_location', 'id', $this->integer(11) . ' UNSIGNED');

        $this->addForeignKey ('whitebook_suborder_item_purchase_area_fk', 'whitebook_suborder_item_purchase', 'area_id', 'whitebook_location', 'id', 'SET NULL' , 'SET NULL');

        //whitebook_suborder_item_purchase - address 
        $this->alterColumn ('whitebook_suborder_item_purchase', 'address_id', $this->integer(11) . ' UNSIGNED NULL');

        $this->addForeignKey ('whitebook_suborder_item_purchase_address_fk', 'whitebook_suborder_item_purchase', 'address_id', 'whitebook_customer_address', 'address_id', 'SET NULL' , 'SET NULL');

        $this->execute("SET foreign_key_checks = 1;");
    }

    public function down()
    {
       
    }
}
