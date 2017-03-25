<?php

use yii\db\Migration;

class m161004_081233_category_refactor extends Migration
{
    public function up()
    {
        $this->createTable('{{%vendor_category}}', [
            'id' => $this->primaryKey(),
            'category_id' => $this->integer(11),
            'vendor_id' => $this->integer(11)
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');
        
        $this->dropColumn('{{%vendor}}', 'category_id');

        $this->dropColumn('{{%vendor_item}}', 'category_id');
        $this->dropColumn('{{%vendor_item}}', 'subcategory_id');
        $this->dropColumn('{{%vendor_item}}', 'child_category');
    }

    public function down()
    {
    
    }
}
