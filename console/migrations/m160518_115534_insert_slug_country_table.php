<?php

use yii\db\Migration;

class m160518_115534_insert_slug_country_table extends Migration
{
    public function up()
    {
         $this->addColumn('whitebook_country', 'slug', $this->string(250));
    }

    public function down()
    {
        echo "m160518_115534_insert_slug_country_table cannot be reverted.\n";

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
