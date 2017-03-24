<?php

use yii\db\Migration;

class m160430_130653_whitebook_customer extends Migration
{
    public function up()
    {        
        $this->dropColumn('{{%customer}}', 'customer_address');
        $this->dropColumn('{{%customer}}', 'country');
        $this->dropColumn('{{%customer}}', 'area');
        $this->dropColumn('{{%customer}}', 'block');
        $this->dropColumn('{{%customer}}', 'street');
        $this->dropColumn('{{%customer}}', 'juda');
        $this->dropColumn('{{%customer}}', 'phone');
        $this->dropColumn('{{%customer}}', 'extra');
    }
}
