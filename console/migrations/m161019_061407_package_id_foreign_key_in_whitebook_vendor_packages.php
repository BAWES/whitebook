<?php

use yii\db\Migration;

class m161019_061407_package_id_foreign_key_in_whitebook_vendor_packages extends Migration
{
    public function up()
    {

        $this->dropIndex(
            'package_id',
            'whitebook_vendor_packages'
        );

        $this->alterColumn (
            'whitebook_vendor_packages',
            'package_id',
            $this->integer(11) . ' UNSIGNED NULL'
        );

        $this->addForeignKey(
            'package_fk',
            'whitebook_vendor_packages',
            'package_id',
            'whitebook_package',
            'package_id',
            'SET NULL' ,
            'SET NULL'
        );
    }

    public function down()
    {
        $this->dropForeignKey(
            'package_id',
            'whitebook_vendor_packages'
        );

        return false;
    }
}
