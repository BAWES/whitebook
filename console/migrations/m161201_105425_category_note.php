<?php

use yii\db\Migration;

class m161201_105425_category_note extends Migration
{
    public function up()
    {
        $this->createTable('whitebook_category_note', [
            'category_note_id' => $this->primaryKey(),
            'customer_id' => $this->integer(11).' UNSIGNED NULL',
            'category_id' => $this->integer(11).' UNSIGNED NULL',
            'note' => $this->text()
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');

        $this->addForeignKey ('cat_note_cat_fk', 'whitebook_category_note', 'category_id', 'whitebook_category', 'category_id', 'SET NULL' , 'SET NULL');

        $this->addForeignKey ('cat_note_customer_fk', 'whitebook_category_note', 'customer_id', 'whitebook_customer', 'customer_id', 'SET NULL' , 'SET NULL');
    }
}
