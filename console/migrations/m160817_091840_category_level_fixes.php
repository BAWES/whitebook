<?php

use yii\db\Migration;

class m160817_091840_category_level_fixes extends Migration
{
    public function up()
    {
        # for deleting unwanted categories
        Yii::$app->db->createCommand('DELETE FROM `whitebook_category` WHERE `whitebook_category`.`category_id` = 139')->execute(); #delete demo
        Yii::$app->db->createCommand('DELETE FROM `whitebook_category` WHERE `whitebook_category`.`category_id` = 140')->execute(); #delete duplicate venues
        Yii::$app->db->createCommand('DELETE FROM `whitebook_category` WHERE `whitebook_category`.`slug` = "dwa"')->execute(); #delete dwa
        Yii::$app->db->createCommand('DELETE FROM `whitebook_category` WHERE `whitebook_category`.`slug` = "say-thank-you"')->execute(); #delete dwa

        # updating icon for category
        Yii::$app->db->createCommand('update whitebook_category SET icon="saythankyou-category" where slug="gift-favors"')->execute();

        # updating serial for categories
        Yii::$app->db->createCommand('update whitebook_category SET sort="1" where category_id="125"')->execute();
        Yii::$app->db->createCommand('update whitebook_category SET sort="2" where category_id="103"')->execute();
        Yii::$app->db->createCommand('update whitebook_category SET sort="3" where category_id="85"')->execute();
        Yii::$app->db->createCommand('update whitebook_category SET sort="4" where category_id="86"')->execute();
        Yii::$app->db->createCommand('update whitebook_category SET sort="5" where category_id="101"')->execute();
        Yii::$app->db->createCommand('update whitebook_category SET sort="6" where category_id="87"')->execute();
        Yii::$app->db->createCommand('update whitebook_category SET sort="7" where category_id="102"')->execute();
        Yii::$app->db->createCommand('update whitebook_category SET sort="8" where category_id="126"')->execute();
        Yii::$app->db->createCommand('update whitebook_category SET sort="9" where slug="gift-favors"')->execute();
    }

    public function down()
    {
        echo "m160817_091840_category_level_fixes cannot be reverted.\n";

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
