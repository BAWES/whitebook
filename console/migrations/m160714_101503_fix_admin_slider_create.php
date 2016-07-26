<?php

use yii\db\Migration;

class m160714_101503_fix_admin_slider_create extends Migration
{
    public function up()
    {
        $this->addColumn('whitebook_slide', 'created_by', $this->integer()->after('sort'));
        $this->addColumn('whitebook_slide', 'modified_by', $this->integer()->after('sort'));
    }

    public function down()
    {
        $this->dropColumn('whitebook_slide', 'created_by');
        $this->dropColumn('whitebook_slide', 'modified_by');
    }
}
