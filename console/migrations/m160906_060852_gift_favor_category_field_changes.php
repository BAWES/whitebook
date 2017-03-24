<?php

use yii\db\Migration;

class m160906_060852_gift_favor_category_field_changes extends Migration
{
    public function up()
    {
        Yii::$app->db->createCommand('Update {{%category}} set parent_category_id=NULL WHERE `whitebook_category`.`slug` = "gift-favors"')->execute();
    }

    public function down()
    {
        echo "m160906_060852_gift_favor_category_field_changes cannot be reverted.\n";

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
