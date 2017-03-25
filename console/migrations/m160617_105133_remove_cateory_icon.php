<?php

use yii\db\Migration;

class m160617_105133_remove_cateory_icon extends Migration
{
    public function up()
    {
        $this->dropColumn('{{%category}}','category_icon');
    }

    public function down()
    {
        echo "m160617_105133_remove_cateory_icon cannot be reverted.\n";

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
