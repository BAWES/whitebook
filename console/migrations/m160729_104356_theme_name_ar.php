<?php

use yii\db\Migration;

class m160729_104356_theme_name_ar extends Migration
{
    public function up()
    {
        $this->addColumn('{{%theme}}', 'theme_name_ar', $this->string()->notNull()->after('theme_name'));
    }

    public function down()
    {
        echo "m160729_104356_theme_name_ar cannot be reverted.\n";

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
