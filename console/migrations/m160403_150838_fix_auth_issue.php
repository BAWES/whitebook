<?php

use yii\db\Migration;

class m160403_150838_fix_auth_issue extends Migration
{
    public function up()
    {
        //Fix columns in `whitebook_auth_item` with issues
        $this->dropColumn("whitebook_auth_item", "created_datetime");
        $this->addColumn('whitebook_auth_item', 'created_at', $this->integer());

        $this->dropColumn("whitebook_auth_item", "modified_datetime");
        $this->addColumn('whitebook_auth_item', 'updated_at', $this->integer());

        //Fix columns in `whitebook_auth_assignment` with issues
        $this->dropColumn("whitebook_auth_assignment", "created_datetime");
        $this->addColumn('whitebook_auth_assignment', 'created_at', $this->integer());

        $this->dropColumn("whitebook_auth_assignment", "modified_datetime");
        $this->addColumn('whitebook_auth_assignment', 'updated_at', $this->integer());
    }

    public function down()
    {
        echo "m160403_150838_fix_auth_issue cannot be reverted.\n";

        return false;
    }
}
