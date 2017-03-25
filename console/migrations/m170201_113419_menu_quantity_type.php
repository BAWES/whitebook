<?php

use yii\db\Migration;

class m170201_113419_menu_quantity_type extends Migration
{
    public function up()
    {
        $this->addColumn('{{%vendor_item_menu}}', 'quantity_type', $this->string(100)->after('max_quantity')->defaultValue('selection'));
    }
}
