<?php

use yii\db\Migration;

class m160831_105756_image_data_update_for_id_271 extends Migration
{
    public function up()
    {
        //Yii::$app->db->createCommand("UPDATE `whitebook_image` SET `module_type` = 'vendor_item' WHERE `whitebook_image`.`item_id` = 271 and `whitebook_image`.`module_type`='admin'")->execute();
    }

    public function down()
    {
        echo "m160831_105756_image_data_update_for_id_271 cannot be reverted.\n";

        return false;
    }
}
