<?php

use yii\db\Migration;

class m161108_074536_vendor_name_arabic_translation extends Migration
{
    public function up()
    {
        Yii::$app->db->createCommand("UPDATE `whitebook_vendor` SET `vendor_name_ar` = 'WhiteBook البائع' WHERE `whitebook_vendor`.`slug` = 'WhiteBook-Vendor';")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_vendor` SET `vendor_name_ar` = 'يوحنا' WHERE `whitebook_vendor`.`slug` = 'john';")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_vendor` SET `vendor_name_ar` = 'جون توماس' WHERE `whitebook_vendor`.`slug` = 'John-Thomas-';")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_vendor` SET `vendor_name_ar` = 'جون الصغير' WHERE `whitebook_vendor`.`slug` = 'Little-John';")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_vendor` SET `vendor_name_ar` = 'لوجيتك' WHERE `whitebook_vendor`.`slug` = 'logitech';")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_vendor` SET `vendor_name_ar` = 'بينك بيري' WHERE `whitebook_vendor`.`slug` = 'pinkberry';")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_vendor` SET `vendor_name_ar` = 'paperkraft' WHERE `whitebook_vendor`.`slug` = 'paperkraft';")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_vendor` SET `vendor_name_ar` = 'الفنون الجميلة' WHERE `whitebook_vendor`.`slug` = 'finearts';")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_vendor` SET `vendor_name_ar` = 'سيفا' WHERE `whitebook_vendor`.`slug` = 'siva';")->execute();
    }

    public function down()
    {
        echo "m161108_074536_vendor_name_arabic_translation cannot be reverted.\n";

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
