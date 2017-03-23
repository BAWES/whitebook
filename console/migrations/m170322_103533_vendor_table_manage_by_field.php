<?php

use yii\db\Migration;

class m170322_103533_vendor_table_manage_by_field extends Migration
{
    public function up()
    {
        Yii::$app->db->createCommand("ALTER TABLE `whitebook_vendor` ADD `vendor_booking_managed_by` ENUM('admin','vendor') NOT NULL DEFAULT 'admin' AFTER `vendor_payable`")->execute();
    }

    public function down()
    {
        echo "m170322_103533_vendor_table_manage_by_field cannot be reverted.\n";
        return false;
    }
}
