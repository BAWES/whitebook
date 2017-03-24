<?php

use yii\db\Migration;

class m160801_105457_feature_group_name_arabic extends Migration
{
    public function up()
    {
        $this->addColumn(
            '{{%feature_group}}', 
            'group_name_ar', 
            $this->string()->notNull()->after('group_name')
        );
    }

    public function down()
    {
        echo "m160801_105457_feature_group_name_arabic cannot be reverted.\n";

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
