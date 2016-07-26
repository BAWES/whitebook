<?php

use yii\db\Migration;

class m160726_064307_arabic_field_in_forms extends Migration
{
    public function up()
    {
        $this->addColumn('whitebook_vendor', 'vendor_contact_address_ar', $this->string()->notNull()->after('vendor_contact_address'));
        $this->addColumn('whitebook_vendor', 'vendor_return_policy_ar', $this->string()->notNull()->after('vendor_return_policy'));
        $this->addColumn('whitebook_vendor', 'short_description_ar', $this->string()->notNull()->after('short_description'));
        $this->addColumn('whitebook_vendor', 'vendor_name_ar', $this->string()->notNull()->after('vendor_name'));

        $this->addColumn('whitebook_vendor_item', 'item_name_ar', $this->string()->notNull()->after('item_name'));
        $this->addColumn('whitebook_vendor_item', 'item_description_ar', $this->string()->notNull()->after('item_description'));
        $this->addColumn('whitebook_vendor_item', 'item_additional_info_ar', $this->string()->notNull()->after('item_additional_info'));
        $this->addColumn('whitebook_vendor_item', 'item_price_description_ar', $this->string()->notNull()->after('item_price_description'));
        $this->addColumn('whitebook_vendor_item', 'item_customization_description_ar', $this->string()->notNull()->after('item_customization_description'));
    }

    public function down()
    {
        echo "m160726_064307_arabic_field_in_forms cannot be reverted.\n";

        return false;
    }
}
