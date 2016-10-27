<?php

use yii\db\Migration;

class m161027_063642_admin_access_control extends Migration
{
    public function up()
    {
        $this->dropForeignKey('access_control_admin_fk', 'whitebook_access_control');

        $this->dropColumn('whitebook_access_control', 'admin_id');
        $this->dropColumn('whitebook_access_control', 'create');
        $this->dropColumn('whitebook_access_control', 'update');
        $this->dropColumn('whitebook_access_control', 'delete');
        $this->dropColumn('whitebook_access_control', 'manage');
        $this->dropColumn('whitebook_access_control', 'view');
        $this->dropColumn('whitebook_access_control', 'default');

        $this->addColumn('whitebook_access_control', 'method', $this->string(100)->after('controller'));
    }

    public function down()
    {
        echo "m161027_063642_admin_access_control cannot be reverted.\n";

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
