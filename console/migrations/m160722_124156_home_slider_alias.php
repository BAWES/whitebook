<?php

use yii\db\Migration;

class m160722_124156_home_slider_alias extends Migration
{
    public function up()
    {
        $this->addColumn('whitebook_siteinfo', 'home_slider_alias', $this->string()->notNull());

    }

    public function down()
    {
        echo "m160722_124156_home_slider_alias cannot be reverted.\n";

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
