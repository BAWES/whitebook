<?php

use yii\db\Migration;

class m170320_081415_vendor_draft extends Migration
{
    public function up()
    {
        $this->dropForeignKey('vendor_image_fk', 'whitebook_vendor');

        $this->dropColumn('whitebook_vendor', 'image_id');

        // vendor draft

        $this->createTable('whitebook_vendor_draft', [
            'vendor_draft_id' => $this->primaryKey(11),
            'vendor_id' => $this->integer(11).' UNSIGNED DEFAULT NULL',
            'vendor_name' => $this->string(128) . ' NOT NULL',
            'vendor_name_ar' => $this->string(255) . ' NOT NULL',
            'vendor_return_policy' => $this->text(),
            'vendor_return_policy_ar' => $this->text(),
            'vendor_public_email' => $this->string(128),
            'vendor_contact_name' => $this->string(128),
            'vendor_contact_email' => $this->string(128),
            'vendor_contact_number' => $this->string(256),
            'vendor_contact_address' => $this->text(),
            'vendor_contact_address_ar' => $this->text(),
            'vendor_emergency_contact_name' => $this->string(128) . ' NOT NULL',
            'vendor_emergency_contact_email' => $this->string(128) . ' NOT NULL',
            'vendor_emergency_contact_number' => $this->string(256) . ' NOT NULL',
            'vendor_fax' => $this->string(50) . ' NOT NULL',
            'vendor_logo_path' => $this->string(250) . ' NOT NULL',
            'short_description' => $this->text(),
            'short_description_ar' => $this->text(),
            'vendor_website' => $this->string(128),
            'vendor_facebook' => $this->string(128),
            'vendor_facebook_text' => $this->string(100),
            'vendor_twitter' => $this->string(128),
            'vendor_twitter_text' => $this->string(100),
            'vendor_instagram' => $this->string(128),
            'vendor_instagram_text' => $this->string(100),
            'vendor_youtube' => $this->string(100),
            'vendor_youtube_text' => $this->string(100),
            'created_by' => $this->integer(11) . ' NOT NULL',
            'modified_by' => $this->integer(11) . ' NOT NULL',
            'created_datetime' => $this->datetime() .' NOT NULL',
            'modified_datetime' => $this->datetime() .' NOT NULL',
            'vendor_bank_name' => $this->string(200) . ' NOT NULL',
            'vendor_bank_branch' => $this->string(200) . ' NOT NULL',
            'vendor_account_no' => $this->string(200) . ' NOT NULL',
            'slug' => $this->string(255),
            'is_ready' => $this->smallInteger(1)
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');

        $this->createIndex ('ind-vendor_draft-vendor_id', 'whitebook_vendor_draft', 'vendor_id');

        $this->addForeignKey ('fk-vendor_draft-vendor_id', 'whitebook_vendor_draft', 'vendor_id', 'whitebook_vendor', 'vendor_id', 'SET NULL' , 'SET NULL');

        // vendor draft phone no  

        $this->createTable('whitebook_vendor_draft_phone_no', [
            'draft_phone_no_id' => $this->primaryKey(11),
            'vendor_draft_id' => $this->integer(11), 
            'phone_no' => $this->string(15),  
            'type' => "enum('Office', 'Mobile', 'Fax', 'Whatsapp')"
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');

        $this->createIndex ('ind-vendor_draft_phone_no-vendor_draft_id', 'whitebook_vendor_draft_phone_no', 'vendor_draft_id');

        $this->addForeignKey ('fk-vendor_draft_phone_no-vendor_draft_id', 'whitebook_vendor_draft_phone_no', 'vendor_draft_id', 'whitebook_vendor_draft', 'vendor_draft_id', 'SET NULL' , 'SET NULL');
       
        // vendor draft category 

        $this->createTable('whitebook_vendor_draft_category', [
            'draft_id' => $this->primaryKey(11),
            'category_id' => $this->integer(11) .' UNSIGNED DEFAULT NULL', 
            'vendor_draft_id' => $this->integer(11)            
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');

        $this->createIndex ('ind-vendor_draft_category-category_id', 'whitebook_vendor_draft_category', 'category_id');

        $this->addForeignKey ('fk-vendor_draft_category-category_id', 'whitebook_vendor_draft_category', 'category_id', 'whitebook_category', 'category_id', 'SET NULL' , 'SET NULL');

        $this->createIndex ('ind-vendor_draft_category-vendor_draft_id', 'whitebook_vendor_draft_category', 'vendor_draft_id');

        $this->addForeignKey ('fk-vendor_draft_category-vendor_draft_id', 'whitebook_vendor_draft_category', 'vendor_draft_id', 'whitebook_vendor_draft', 'vendor_draft_id', 'SET NULL' , 'SET NULL');

        // vendor draft alert emails  

        $this->createTable('whitebook_vendor_draft_order_alert_emails', [
            'vdoae_id' => $this->primaryKey(11),
            'vendor_draft_id' => $this->integer(11),
            'email_address' => $this->string(100)
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');
        
        $this->createIndex ('ind-vendor_draft_order_alert_emails-vendor_draft_id', 'whitebook_vendor_draft_order_alert_emails', 'vendor_draft_id');

        $this->addForeignKey ('fk-vendor_draft_order_alert_emails-vendor_draft_id', 'whitebook_vendor_draft_order_alert_emails', 'vendor_draft_id', 'whitebook_vendor_draft', 'vendor_draft_id', 'SET NULL' , 'SET NULL');
    }   

    public function down()
    {
        $this->dropTable('whitebook_vendor_draft_order_alert_emails');

        $this->dropTable('whitebook_vendor_draft_phone_no');

        $this->dropTable('whitebook_vendor_draft_category');
        
        $this->dropTable('whitebook_vendor_draft');
    }
}
