<?php

use yii\db\Migration;

class m161222_103721_vendor_category_cat_index extends Migration
{
    public function up()
    {
        $this->createIndex(
            'idx-vendor-category-category_id',
            'whitebook_vendor_category',
            'category_id'
        );

        $this->dropForeignKey(
            'vendor_category_to_category_fk',
            'whitebook_vendor_category'
        );

        $this->addForeignKey ('vendor_category_to_category_fk', 'whitebook_vendor_category', 'category_id', 'whitebook_category', 'category_id', 'SET NULL' , 'SET NULL');
    }
}
