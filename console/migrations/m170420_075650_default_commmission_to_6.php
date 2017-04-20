<?php

use yii\db\Migration;

class m170420_075650_default_commmission_to_6 extends Migration
{
    public function up()
    {
        $this->execute('update whitebook_vendor set commision = 6.0;');
    }

    public function down()
    {
        echo "m170420_075650_default_commmission_to_6 cannot be reverted.\n";

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
