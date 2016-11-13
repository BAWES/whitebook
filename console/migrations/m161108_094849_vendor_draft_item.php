<?php

use yii\db\Migration;

class m161108_094849_vendor_draft_item extends Migration
{
    public function up()
    {
        $this->createTable('whitebook_vendor_draft_item', [
            'drafty_item_id' => $this->primaryKey(),
            'item_id' => $this->integer() . ' UNSIGNED NOT NULL',
            'type_id' => $this->integer() . ' UNSIGNED DEFAULT NULL', 
            'vendor_id' => $this->integer() . ' UNSIGNED DEFAULT NULL', 
            'item_name' => $this->string(128)->notNull()->defaultValue(''),
            'item_name_ar' => $this->text(),
            'priority' => 'enum("Normal", "Super") NOT NULL DEFAULT "Normal"',
            'item_description' => $this->text()->notNull(),
            'item_description_ar' => $this->text(),
            'item_additional_info' => $this->text()->notNull(),
            'item_additional_info_ar' => $this->text(),
            'item_amount_in_stock' => $this->integer(11)->defaultValue(NULL),
            'item_default_capacity' => $this->integer(11)->defaultValue(NULL),
            'item_price_per_unit' => $this->money(11, 0)->defaultValue(NULL),
            'item_customization_description' => $this->text()->notNull(),
            'item_customization_description_ar' => $this->text(),
            'item_price_description' => $this->text()->notNull(),
            'item_price_description_ar' => $this->text(),
            'item_for_sale' => 'enum("Yes", "No") NOT NULL DEFAULT "No"',
            'sort' => $this->integer(11)->notNull(),
            'item_how_long_to_make' => $this->integer(11)->defaultValue(NULL),
            'item_minimum_quantity_to_order' => $this->integer(11)->defaultValue(NULL),
            'item_archived' => 'enum("Yes", "No") NOT NULL DEFAULT "No"',
            'item_approved' => 'enum("Yes", "Pending", "Rejected") NOT NULL DEFAULT "Pending"',
            'item_status' => 'enum("Active", "Deactive") NOT NULL DEFAULT "Deactive"',
            'created_by' => $this->integer(11)->notNull(),
            'modified_by' => $this->integer(11)->notNull(),
            'created_datetime' => $this->dateTime()->notNull(),
            'modified_datetime' => $this->dateTime()->notNull(),
            'trash' => 'enum("Default", "Deleted") NOT NULL DEFAULT "Default"', 
            'slug' => $this->string(255)
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');

        $this->addForeignKey ('vendor_draft_item_fk', 'whitebook_vendor_draft_item', 'vendor_id', 'whitebook_vendor', 'vendor_id', 'SET NULL' , 'SET NULL');

        $this->addForeignKey ('vendor_draft_item_type_fk', 'whitebook_vendor_draft_item', 'type_id', 'whitebook_item_type', 'type_id', 'SET NULL' , 'SET NULL');
    }

    public function down()
    {
        echo "m161108_094849_vendor_draft_item cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
