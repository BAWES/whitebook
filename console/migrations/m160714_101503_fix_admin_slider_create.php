<?php

use yii\db\Migration;

class m160714_101503_fix_admin_slider_create extends Migration
{
    public function up()
    {
        $this->addColumn('{{%slide}}', 'created_by', $this->integer()->after('sort'));
        $this->addColumn('{{%slide}}', 'modified_by', $this->integer()->after('sort'));
    }

    public function down()
    {
        $this->dropColumn('{{%slide}}', 'created_by');
        $this->dropColumn('{{%slide}}', 'modified_by');
    }
}
