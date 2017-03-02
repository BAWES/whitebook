<?php

use yii\db\Migration;

class m170302_085947_booking_tables extends Migration
{
    public function up()
    {
        $this->createTable('whitebook_booking', [
            'booking_id' => $this->primaryKey(),
            'booking_token' => $this->char(13),
            'vendor_id' => $this->integer(11) . ' UNSIGNED NULL',
            'customer_id' => $this->integer(11) . ' UNSIGNED NULL',
            'customer_name' => $this->string(100),
            'customer_lastname' => $this->string(100),
            'customer_email' => $this->string(100),
            'customer_mobile' => $this->string(20),
            'booking_note' => $this->text(),
            'expired_on' => $this->dateTime(), 
            'notification_status' => $this->smallInteger(1),  
            'commission_percentage' => $this->decimal(5, 3), 
            'commission_total' => $this->decimal(11, 3), // (How much TWB make out of this)
            'payment_method' => $this->string(100), 
            'transaction_id' => $this->string(100),
            'gateway_percentage' => $this->decimal(5, 3),  
            'gateway_fees' =>  $this->decimal(11, 3), 
            'gateway_total' => $this->decimal(11, 3),
            'total_delivery_charge' => $this->decimal(11, 3),
            'total_without_delivery' => $this->decimal(11, 3),
            'total_with_delivery' => $this->decimal(11, 3),
            'total _vendor' => $this->decimal(11, 3),// (How much vendor make out of this) 
            'booking_status' => $this->smallInteger(1), //  (to know booking complete or not)
            'ip_address' => $this->string(128),
            'created_datetime' => $this->dateTime(),
            'modified_datetime' => $this->dateTime(),
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');

        $this->createIndex ('ind-booking-vendor_id', 'whitebook_booking', 'vendor_id');

        $this->addForeignKey ('fk-booking-vendor_id', 'whitebook_booking', 'vendor_id', 'whitebook_vendor', 'vendor_id', 'SET NULL' , 'SET NULL');

        $this->createIndex ('ind-booking-customer_id', 'whitebook_booking', 'customer_id');

        $this->addForeignKey ('fk-booking-customer_id', 'whitebook_booking', 'customer_id', 'whitebook_customer', 'customer_id', 'SET NULL' , 'SET NULL');

        $this->createTable('whitebook_booking_item', [
            'booking_item_id' => $this->primaryKey(),
            'booking_id' => $this->integer(11),
            'item_id' => $this->integer(11) . ' UNSIGNED NULL',
            'item_name' => $this->string(128),
            'item_name_ar' => $this->string(128),
            'timeslot' => $this->string(100),
            'area_id' => $this->integer(11),
            'address_id' => $this->integer(11),
            'delivery_address' => $this->text(), 
            'delivery_date' => $this->date(), 
            'price' => $this->decimal(11, 3), // (Item price per unit)
            'quantity' => $this->integer(11),
            'total' => $this->decimal(11, 3),
            'female_service' => $this->smallInteger(1),
            'special_request' => $this->text()
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');

        $this->createIndex ('ind-booking_item-booking_id', 'whitebook_booking_item', 'booking_id');

        $this->addForeignKey ('fk-booking_item-booking_id', 'whitebook_booking_item', 'booking_id', 'whitebook_booking', 'booking_id', 'SET NULL' , 'SET NULL');

        $this->createIndex ('ind-booking_item-item_id', 'whitebook_booking_item', 'item_id');

        $this->addForeignKey ('fk-booking_item-item_id', 'whitebook_booking_item', 'item_id', 'whitebook_vendor_item', 'item_id', 'SET NULL' , 'SET NULL');

        $this->createTable('whitebook_booking_item_menu', [
            'booking_item_menu_id' => $this->primaryKey(),
            'booking_item_id' => $this->integer(11),
            'menu_id' => $this->integer(11),
            'menu_item_id' => $this->integer(11),
            'menu_name' => $this->string(100),
            'menu_name_ar' => $this->string(100),
            'menu_type' => "enum('addons', 'options')",
            'menu_item_name' => $this->string(100),
            'menu_item_name_ar' => $this->string(100),
            'quantity' => $this->integer(11),
            'price' => $this->decimal(11, 3),
            'total' => $this->decimal(11, 3)
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');  
        
        $this->createIndex ('ind-booking_item_menu-booking_item_id', 'whitebook_booking_item_menu', 'booking_item_id');

        $this->addForeignKey ('fk-booking_item_menu-booking_item_id', 'whitebook_booking_item_menu', 'booking_item_id', 'whitebook_booking_item', 'booking_item_id', 'SET NULL' , 'SET NULL');

        $this->createIndex ('ind-booking_item_menu-menu_id', 'whitebook_booking_item_menu', 'menu_id');

        $this->addForeignKey ('fk-booking_item_menu-menu_id', 'whitebook_booking_item_menu', 'menu_id', 'whitebook_vendor_item_menu', 'menu_id', 'SET NULL' , 'SET NULL');

        $this->createIndex ('ind-booking_item_menu-menu_item_id', 'whitebook_booking_item_menu', 'menu_item_id');
    
        $this->addForeignKey ('fk-booking_item_menu-menu_item_id', 'whitebook_booking_item_menu', 'menu_item_id', 'whitebook_vendor_item_menu_item', 'menu_item_id', 'SET NULL' , 'SET NULL');
    }

    public function down()
    {
        
    }
}
