<?php

use yii\db\Migration;

class m170201_082609_item_quantity_label extends Migration
{
    public function up()
    {
        $this->addColumn('{{%vendor_item}}', 'quantity_label', $this->string(100)->after('item_price_per_unit')->defaultValue('Quantity'));

        $this->addColumn('{{%vendor_draft_item}}', 'quantity_label', $this->string(100)->after('item_price_per_unit')->defaultValue('Quantity'));
    }
}
