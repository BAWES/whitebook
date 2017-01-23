<?php

use yii\db\Migration;

class m170123_073220_vendor_item_fields extends Migration
{
    public function up()
    {
        $this->addColumn('whitebook_vendor_item', 'set_up_time', $this->string(256)->after('item_price_description_ar'));
        $this->addColumn('whitebook_vendor_item', 'set_up_time_ar', $this->string(256)->after('set_up_time'));

        $this->addColumn('whitebook_vendor_item', 'max_time', $this->string(256)->after('set_up_time'));
        $this->addColumn('whitebook_vendor_item', 'max_time_ar', $this->string(256)->after('max_time'));

        $this->addColumn('whitebook_vendor_item', 'requirements', $this->string(256)->after('max_time'));
        $this->addColumn('whitebook_vendor_item', 'requirements_ar', $this->string(256)->after('requirements'));

        $this->addColumn('whitebook_vendor_item', 'whats_include', $this->text()->after('item_additional_info_ar'));
        $this->addColumn('whitebook_vendor_item', 'whats_include_ar', $this->text()->after('whats_include'));

        $this->addColumn('whitebook_vendor_item', 'min_order_amount', $this->decimal(10,3)->after('item_price_per_unit'));
    }
}
