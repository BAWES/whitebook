<?php

use yii\db\Migration;

class m160819_114305_vendor_table_fields_set_to_null extends Migration
{
    public function up()
    {
        Yii::$app->db->createCommand("ALTER TABLE {{%vendor}} CHANGE `image_id` `image_id` INT(11) UNSIGNED NULL")->execute(); #package_id can be null
        Yii::$app->db->createCommand("ALTER TABLE {{%vendor}} CHANGE `vendor_brief` `vendor_brief` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL")->execute();
        Yii::$app->db->createCommand("ALTER TABLE {{%vendor}} CHANGE `vendor_public_email` `vendor_public_email` VARCHAR(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL")->execute();
        Yii::$app->db->createCommand("ALTER TABLE {{%vendor}} CHANGE `vendor_public_phone` `vendor_public_phone` VARCHAR(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL")->execute();
        Yii::$app->db->createCommand("ALTER TABLE {{%vendor}} CHANGE `vendor_working_hours` `vendor_working_hours` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL")->execute();
        Yii::$app->db->createCommand("ALTER TABLE {{%vendor}} CHANGE `vendor_working_min_to` `vendor_working_min_to` INT(11) NULL")->execute();
        Yii::$app->db->createCommand("ALTER TABLE {{%vendor}} CHANGE `vendor_working_hours_to` `vendor_working_hours_to` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL")->execute();
        Yii::$app->db->createCommand("ALTER TABLE {{%vendor}} CHANGE `vendor_website` `vendor_website` VARCHAR(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT ''")->execute();
        Yii::$app->db->createCommand("ALTER TABLE {{%vendor}} CHANGE `vendor_facebook` `vendor_facebook` VARCHAR(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL")->execute();
        Yii::$app->db->createCommand("ALTER TABLE {{%vendor}} CHANGE `vendor_twitter` `vendor_twitter` VARCHAR(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL")->execute();
        Yii::$app->db->createCommand("ALTER TABLE {{%vendor}} CHANGE `vendor_instagram` `vendor_instagram` VARCHAR(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ")->execute();
        Yii::$app->db->createCommand("ALTER TABLE {{%vendor}} CHANGE `vendor_googleplus` `vendor_googleplus` VARCHAR(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ")->execute();
        Yii::$app->db->createCommand("ALTER TABLE {{%vendor}} CHANGE `vendor_skype` `vendor_skype` VARCHAR(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ")->execute();
        Yii::$app->db->createCommand("ALTER TABLE {{%vendor}} CHANGE `package_start_date` `package_start_date` DATE NULL")->execute();
        Yii::$app->db->createCommand("ALTER TABLE {{%vendor}} CHANGE `package_end_date` `package_end_date` DATE NULL")->execute();
        Yii::$app->db->createCommand("ALTER TABLE {{%vendor}} CHANGE `commision` `commision` FLOAT NULL")->execute();
        Yii::$app->db->createCommand("ALTER TABLE {{%vendor}} CHANGE `vendor_delivery_charge` `vendor_delivery_charge` DECIMAL(11,0) NULL")->execute();
        Yii::$app->db->createCommand("ALTER TABLE {{%vendor}} CHANGE `blocked_days` `blocked_days` VARCHAR(256) CHARACTER SET utf8 COLLATE utf8_general_ci NULL")->execute();
    }

    public function down()
    {
        echo "m160819_114305_vendor_table_fields_set_to_null cannot be reverted.\n";

        return false;
    }
}
