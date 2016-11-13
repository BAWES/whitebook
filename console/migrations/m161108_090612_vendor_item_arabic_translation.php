<?php

use yii\db\Migration;

class m161108_090612_vendor_item_arabic_translation extends Migration
{
    public function up()
    {
        Yii::$app->db->createCommand("UPDATE `whitebook_vendor_item` SET `item_name_ar` = NULL WHERE 1;")->execute();
    }

    public function down()
    {
        echo "m161108_090612_vendor_item_arabic_translation cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
