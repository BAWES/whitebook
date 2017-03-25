<?php

use yii\db\Migration;

class m170116_100655_menu_item_columns extends Migration
{
    public function up()
    {
        $this->addColumn('{{%vendor_item_menu}}', 'min_quantity', $this->integer(11));
        $this->addColumn('{{%vendor_item_menu}}', 'max_quantity', $this->integer(11));
        $this->addColumn('{{%vendor_item_menu}}', 'sort_order', $this->integer(11));
		
        $this->addColumn('{{%vendor_item_menu_item}}', 'sort_order', $this->integer(11));        
    }
}
