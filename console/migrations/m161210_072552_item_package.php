<?php

use yii\db\Migration;

class m161210_072552_item_package extends Migration
{
    public function up()
    {        
        $this->dropForeignKey('vendor_to_package_fk', 'whitebook_vendor');
        $this->dropColumn('whitebook_vendor', 'package_id');
        $this->dropColumn('whitebook_vendor', 'package_start_date');
        $this->dropColumn('whitebook_vendor', 'package_end_date');

        $this->dropTable('whitebook_vendor_packages');
        $this->dropTable('whitebook_package');
        
        $this->createTable('whitebook_package', [
            'package_id' => $this->primaryKey(),
            'package_name' => $this->string(100)->notNull(),
            'package_background_image' => $this->string(250),
            'package_description' => $this->text(),
            'package_avg_price' => $this->string(100),
            'package_number_of_guests' => $this->string(100)
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');
    }
}
