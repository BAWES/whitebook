<?php

use yii\db\Migration;

class m160803_100903_category_arabic_field extends Migration
{
    public function up()
    {
        $this->addColumn(
            'whitebook_category', 
            'category_name_ar', 
            $this->string()->notNull()->after('category_name')
        );

        $this->addColumn(
            'whitebook_category', 
            'icon', 
            $this->string()->notNull()->after('category_name_ar')
        );
    }

    public function down()
    {
        echo "m160803_100903_category_arabic_field cannot be reverted.\n";

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
