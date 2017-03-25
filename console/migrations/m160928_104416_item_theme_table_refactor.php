<?php

use yii\db\Migration;

class m160928_104416_item_theme_table_refactor extends Migration
{
    public function up()
    {
        $this->dropColumn('{{%vendor_item_theme}}', 'vendor_id');
        $this->dropColumn('{{%vendor_item_theme}}', 'category_id');
        $this->dropColumn('{{%vendor_item_theme}}', 'subcategory_id');
        $this->dropColumn('{{%vendor_item_theme}}', 'theme_start_date');
        $this->dropColumn('{{%vendor_item_theme}}', 'theme_end_date');
        $this->dropColumn('{{%vendor_item_theme}}', 'created_by');
        $this->dropColumn('{{%vendor_item_theme}}', 'modified_by');
        $this->alterColumn('{{%vendor_item_theme}}', 'item_id', $this->integer(11));
        $this->alterColumn('{{%vendor_item_theme}}', 'theme_id', $this->integer(11));
    }

    public function down()
    {
        $this->addColumn('{{%vendor_item_theme}}', 'vendor_id',$this->integer(11));
        $this->addColumn('{{%vendor_item_theme}}', 'category_id',$this->integer(11));
        $this->addColumn('{{%vendor_item_theme}}', 'subcategory_id',$this->integer(11));
        $this->addColumn('{{%vendor_item_theme}}', 'theme_start_date',$this->date());
        $this->addColumn('{{%vendor_item_theme}}', 'theme_end_date',$this->date());
        $this->addColumn('{{%vendor_item_theme}}', 'created_by',$this->integer(11));
        $this->addColumn('{{%vendor_item_theme}}', 'modified_by',$this->integer(11));
        return false;
    }
}
