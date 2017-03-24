<?php

use yii\db\Migration;

class m160819_110824_vendor_table_package_id_null_allowed extends Migration
{
    public function up()
    {
        Yii::$app->db->createCommand('ALTER TABLE {{%vendor}} CHANGE `package_id` `package_id` INT(11) UNSIGNED NULL')->execute(); #package_id can be null
    }

    public function down()
    {
        echo "m160819_110824_vendor_table_package_id_null_allowed cannot be reverted.\n";

        return false;
    }
}
