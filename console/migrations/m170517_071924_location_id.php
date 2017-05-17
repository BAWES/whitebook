<?php

use yii\db\Migration;

class m170517_071924_location_id extends Migration
{
    public function up()
    {
        $this->execute("SET foreign_key_checks = 0;");

        $this->alterColumn ('{{%location}}', 'id', 'int(11) UNSIGNED NOT NULL AUTO_INCREMENT');        
        
        $this->execute("SET foreign_key_checks = 1;");
    }
}
