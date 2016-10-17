<?php

use yii\db\Migration;

class m161017_091746_db_relations extends Migration
{
    public function up()
    {
        $this->execute("SET foreign_key_checks = 0;");

        //whitebook_access_control
        $this->alterColumn ('whitebook_access_control', 'admin_id', $this->integer(11).' UNSIGNED NULL');

        $this->alterColumn ('whitebook_access_control', 'role_id', $this->integer(11).' UNSIGNED NULL');

        $this->addForeignKey ('access_control_admin_fk', 'whitebook_access_control', 'admin_id', 'whitebook_admin', 'id', 'SET NULL' , 'SET NULL');

        $this->addForeignKey ('access_control_role_fk', 'whitebook_access_control', 'role_id', 'whitebook_role', 'role_id', 'SET NULL' , 'SET NULL');

        //whitebook_address_question
        $this->alterColumn ('whitebook_address_question', 'address_type_id', $this->integer(11).' UNSIGNED NULL');

        $this->addForeignKey ('address_question_type_fk', 'whitebook_address_question', 'address_type_id', 'whitebook_address_type', 'type_id', 'SET NULL' , 'SET NULL');

        //whitebook_admin
        $this->alterColumn ('whitebook_admin', 'role_id', $this->integer(11).' UNSIGNED NULL');

        $this->addForeignKey ('admin_role_fk', 'whitebook_admin', 'role_id', 'whitebook_role', 'role_id', 'SET NULL' , 'SET NULL');

        //whitebook_auth_assignment
        $this->alterColumn ('whitebook_auth_assignment', 'user_id', $this->integer(11).' UNSIGNED NULL'); 
        $this->alterColumn ('whitebook_auth_assignment', 'controller_id', $this->integer(11).' UNSIGNED NULL');
        
        $this->addForeignKey ('auth_assignment_user_fk', 'whitebook_auth_assignment', 'controller_id', 'whitebook_controller', 'id', 'SET NULL' , 'SET NULL');

        $this->addForeignKey ('auth_assignment_controller_fk', 'whitebook_auth_assignment', 'user_id', 'whitebook_admin', 'id', 'SET NULL' , 'SET NULL');
       
        //whitebook_auth_item_child
        $this->addForeignKey ('auth_item_parent_fk', 'whitebook_auth_item_child', 'parent', 'whitebook_auth_item', 'name', 'CASCADE' , 'CASCADE');
    
        $this->addForeignKey ('auth_item_child_fk', 'whitebook_auth_item_child', 'child', 'whitebook_auth_item', 'name', 'CASCADE' , 'CASCADE');

        //whitebook_category_path
        $this->alterColumn ('whitebook_category_path', 'path_id', $this->integer(11) . ' UNSIGNED NULL');

        $this->addForeignKey ('category_path_fk', 'whitebook_category_path', 'path_id', 'whitebook_category', 'category_id', 'SET NULL' , 'SET NULL');

        //whitebook_city
        $this->alterColumn ('whitebook_city', 'country_id', $this->integer(11) . 'NULL');

        //whitebook_customer_address
        
        $this->alterColumn ('whitebook_customer_address', 'customer_id', $this->integer(11) . ' UNSIGNED NULL');
        $this->alterColumn ('whitebook_customer_address', 'address_type_id', $this->integer(11) . '  UNSIGNED NULL');
        $this->alterColumn ('whitebook_customer_address', 'country_id', $this->integer(11) . ' NULL ');
        $this->alterColumn ('whitebook_customer_address', 'city_id', $this->integer(11) . ' NULL');
        $this->alterColumn ('whitebook_customer_address', 'area_id', $this->integer(11) . ' UNSIGNED NULL');

        $this->addForeignKey ('customer_address_country_fk', 'whitebook_customer_address', 'country_id', 'whitebook_country', 'country_id', 'SET NULL' , 'SET NULL');

        $this->addForeignKey ('customer_address_customer_fk', 'whitebook_customer_address', 'customer_id', 'whitebook_customer', 'customer_id', 'SET NULL' , 'SET NULL');

        $this->addForeignKey ('customer_address_type_fk', 'whitebook_customer_address', 'address_type_id', 'whitebook_address_type', 'type_id', 'SET NULL' , 'SET NULL');

        $this->addForeignKey ('customer_address_city_fk', 'whitebook_customer_address', 'city_id', 'whitebook_city', 'city_id', 'SET NULL' , 'SET NULL');

        $this->addForeignKey ('customer_address_area_fk', 'whitebook_customer_address', 'area_id', 'whitebook_location', 'id', 'SET NULL' , 'SET NULL');
       
        //whitebook_customer_cart
        $this->alterColumn ('whitebook_customer_cart', 'customer_id', $this->integer(11) . ' UNSIGNED NULL');
        $this->alterColumn ('whitebook_customer_cart', 'item_id', $this->integer(11) . ' UNSIGNED NULL');
        $this->alterColumn ('whitebook_customer_cart', 'area_id', $this->integer(11) . ' UNSIGNED NULL');
        $this->alterColumn ('whitebook_customer_cart', 'timeslot_id', $this->integer(11) . ' UNSIGNED NULL');

        $this->addForeignKey ('customer_cart_fk', 'whitebook_customer_cart', 'customer_id', 'whitebook_customer', 'customer_id', 'SET NULL' , 'SET NULL');

        $this->addForeignKey ('cart_item_fk', 'whitebook_customer_cart', 'item_id', 'whitebook_vendor_item', 'item_id', 'SET NULL' , 'SET NULL');

        $this->addForeignKey ('cart_area_fk', 'whitebook_customer_cart', 'area_id', 'whitebook_location', 'id', 'SET NULL' , 'SET NULL');

        $this->addForeignKey ('cart_timeslot_fk', 'whitebook_customer_cart', 'timeslot_id', 'whitebook_vendor_delivery_timeslot', 'timeslot_id', 'SET NULL' , 'SET NULL');

        //whitebook_events
        $this->alterColumn ('whitebook_events', 'customer_id', $this->integer(11) . ' UNSIGNED NULL');

        $this->addForeignKey ('events_customer_fk', 'whitebook_events', 'customer_id', 'whitebook_customer', 'customer_id', 'SET NULL' , 'SET NULL');

        //whitebook_event_invitees
        $this->alterColumn ('whitebook_event_invitees', 'customer_id', $this->integer(11) . ' UNSIGNED NULL');
        $this->alterColumn ('whitebook_event_invitees', 'event_id', $this->integer(11) . ' UNSIGNED NULL');

        $this->addForeignKey ('event_invitees_customer_fk', 'whitebook_event_invitees', 'customer_id', 'whitebook_customer', 'customer_id', 'SET NULL' , 'SET NULL');

        $this->addForeignKey ('event_invitees_event_fk', 'whitebook_event_invitees', 'event_id', 'whitebook_events', 'event_id', 'SET NULL' , 'SET NULL');

        //whitebook_faq
        $this->alterColumn ('whitebook_faq', 'faq_group_id', $this->integer(11) . ' NULL');
        
        $this->addForeignKey ('whitebook_faq_group_fk', 'whitebook_faq', 'faq_group_id', 'whitebook_faq_group', 'faq_group_id', 'SET NULL' , 'SET NULL');
        
        //whitebook_feature_event
        $this->alterColumn ('whitebook_feature_event', 'type_id', $this->integer(11) . ' UNSIGNED NULL');
        $this->alterColumn ('whitebook_feature_event', 'customer_id', $this->integer(11) . ' UNSIGNED NULL');

        $this->addForeignKey ('feature_event_customer_fk', 'whitebook_feature_event', 'customer_id', 'whitebook_customer', 'customer_id', 'SET NULL' , 'SET NULL');

        $this->addForeignKey ('feature_event_type_fk', 'whitebook_feature_event', 'type_id', 'whitebook_event_type', 'type_id', 'SET NULL' , 'SET NULL');

        //whitebook_suborder
        $this->alterColumn ('whitebook_suborder', 'order_id', $this->integer(11) . ' UNSIGNED NULL');

        $this->alterColumn ('whitebook_suborder', 'status_id', $this->integer(11) . ' NULL');

        $this->addForeignKey ('vendor_suborder_order_fk', 'whitebook_suborder', 'order_id', 'whitebook_order', 'order_id', 'SET NULL' , 'SET NULL');
        $this->addForeignKey ('vendor_suborder_status_fk', 'whitebook_suborder', 'status_id', 'whitebook_order_status', 'order_status_id', 'SET NULL' , 'SET NULL');

        //whitebook_vendor
        $this->alterColumn ('whitebook_vendor', 'package_id', $this->integer(11) . ' UNSIGNED NULL');
        $this->alterColumn ('whitebook_vendor', 'image_id', $this->integer(11) . ' UNSIGNED NULL');
        
        $this->addForeignKey ('vendor_to_package_fk', 'whitebook_vendor', 'package_id', 'whitebook_package', 'package_id', 'SET NULL' , 'SET NULL');

        $this->addForeignKey ('vendor_image_fk', 'whitebook_vendor', 'image_id', 'whitebook_image', 'image_id', 'SET NULL' , 'SET NULL');

        //whitebook_vendor_location
        $this->alterColumn ('whitebook_vendor_location', 'city_id', $this->integer(11) . ' NULL');
        $this->alterColumn ('whitebook_vendor_location', 'area_id', $this->integer(11) . ' UNSIGNED NULL');
        $this->addForeignKey ('vendor_location_city_fk', 'whitebook_vendor_location', 'city_id', 'whitebook_city', 'city_id', 'SET NULL' , 'SET NULL');
        $this->addForeignKey ('vendor_location_location_fk', 'whitebook_vendor_location', 'area_id', 'whitebook_location', 'id', 'SET NULL' , 'SET NULL');
            
         //whitebook_customer_address_response
        $this->alterColumn ('whitebook_customer_address_response', 'address_id', $this->integer(11) . ' UNSIGNED NULL');
        
        $this->addForeignKey ('customer_address_r_a_fk', 'whitebook_customer_address_response', 'address_id', 'whitebook_customer_address', 'address_id', 'SET NULL' , 'SET NULL');

        $this->alterColumn ('whitebook_customer_address_response', 'address_type_question_id', $this->integer(11) . ' NULL');

        $this->addForeignKey ('customer_address_r_q_fk', 'whitebook_customer_address_response', 'address_type_question_id', 'whitebook_address_question', 'ques_id', 'SET NULL' , 'SET NULL');
    
        //city 
        $this->addForeignKey ('city_country_fk', 'whitebook_city', 'country_id', 'whitebook_country', 'country_id', 'CASCADE' , 'CASCADE');

        $this->execute("SET foreign_key_checks = 1;");
    }
}
