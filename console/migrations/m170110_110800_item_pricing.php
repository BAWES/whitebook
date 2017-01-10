<?php

use yii\db\Migration;

class m170110_110800_item_pricing extends Migration
{
    public function up()
    {
        $this->alterColumn('whitebook_vendor_draft_item_pricing', 'pricing_price_per_unit', $this->decimal(10,3));
        $this->alterColumn('whitebook_vendor_item_pricing', 'pricing_price_per_unit', $this->decimal(10,3));
    }
}
