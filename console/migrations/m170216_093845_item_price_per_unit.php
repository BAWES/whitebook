<?php

use yii\db\Migration;

class m170216_093845_item_price_per_unit extends Migration
{
    public function up()
    {
        $this->alterColumn('{{%vendor_item}}', 'item_price_per_unit', $this->decimal(10,3));
        
        $this->alterColumn('{{%vendor_draft_item}}', 'item_price_per_unit', $this->decimal(10,3));
    }
}
