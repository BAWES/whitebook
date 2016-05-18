<?php

use yii\db\Migration;

class m160518_140231_remove_file_siteinfo extends Migration
{
    public function up()
    {
        $this->dropColumn('whitebook_siteinfo', 'site_logo');
        $this->dropColumn('whitebook_siteinfo', 'site_favicon');
        $this->dropColumn('whitebook_siteinfo', 'site_noimage');
    }

    public function down()
    {
        echo "m160518_140231_remove_file_siteinfo cannot be reverted.\n";

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
