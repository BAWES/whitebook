<?php

use yii\db\Migration;

class m160803_085346_cms_arabic_field extends Migration
{
    public function up()
    {
        $this->addColumn(
            '{{%cms}}', 
            'page_name_ar', 
            $this->string()->notNull()->after('page_name')
        );

        $this->addColumn(
            '{{%cms}}', 
            'page_content_ar', 
            $this->string()->notNull()->after('page_content')
        );
    }

    public function down()
    {
        echo "m160803_085346_cms_arabic_field cannot be reverted.\n";

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
