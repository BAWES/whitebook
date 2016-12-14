<?php

use yii\db\Migration;

class m161210_100139_vendor_item_to_package extends Migration
{
    public function up()
    {
        $this->createTable('whitebook_vendor_item_to_package', [
            'id' => $this->primaryKey(),
            'package_id' => $this->integer(11),
            'item_id' => $this->integer(11).' UNSIGNED NULL '
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');

        $this->addForeignKey(
            'itm_pkg_pkg_fk',
            'whitebook_vendor_item_to_package',
            'package_id',
            'whitebook_package',
            'package_id',
            'SET NULL' ,
            'SET NULL'
        );

        $this->addForeignKey (
            'itm_pkg_itm_fk',
            'whitebook_vendor_item_to_package',
            'item_id',
            'whitebook_vendor_item',
            'item_id',
            'SET NULL' ,
            'SET NULL'
        );
    }
}
