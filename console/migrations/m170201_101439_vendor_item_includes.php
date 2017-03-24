<?php

use yii\db\Migration;

class m170201_101439_vendor_item_includes extends Migration
{
    public function up()
    {
        $this->dropColumn(
            '{{%vendor_item}}', 
            'whats_include'
        );

        $this->dropColumn(
            '{{%vendor_item}}', 
            'whats_include_ar'
        );
    }
}
