<?php

use yii\db\Migration;

class m161019_115246_category_parent_relation extends Migration
{
    public function up()
    {
        $this->execute("SET foreign_key_checks = 0;");

        $this->addForeignKey ('category_parent_fk', 'whitebook_category', 'parent_category_id', 'whitebook_category', 'category_id', 'SET NULL' , 'SET NULL');

        $this->execute("SET foreign_key_checks = 1;");
    }

    public function down()
    {
       
    }
}
