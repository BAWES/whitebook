<?php

use yii\db\Migration;

class m160801_093010_arabic_field_values extends Migration
{
    public function up()
    {
        //arabic values for vendor - vendor_name_ar
        Yii::$app->db->createCommand('update {{%vendor}} SET vendor_name_ar="بائع" where vendor_name_ar="" OR vendor_name_ar IS NULL')->execute();

        //arabic values for vendor_item - item_name_ar
        Yii::$app->db->createCommand('update {{%vendor_item}} SET item_name_ar="منتج" where item_name_ar="" OR item_name_ar IS NULL')->execute();
    }

    public function down()
    {
        echo "m160801_093010_arabic_field_values cannot be reverted.\n";

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
