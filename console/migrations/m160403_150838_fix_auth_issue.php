<?php

use yii\db\Migration;

class m160403_150838_fix_auth_issue extends Migration
{
    public function up()
    {
        //Fix columns in `whitebook_auth_item` with issues
        $this->alterColumn("whitebook_auth_item", "created_datetime", $this->integer());
        $this->renameColumn("whitebook_auth_item", "created_datetime", "created_at");
        $this->alterColumn("whitebook_auth_item", "modified_datetime", $this->integer());
        $this->renameColumn("whitebook_auth_item", "modified_datetime", "updated_at");

        //Fix columns in `whitebook_auth_assignment` with issues
        $this->alterColumn("whitebook_auth_assignment", "created_datetime", $this->integer());
        $this->renameColumn("whitebook_auth_assignment", "created_datetime", "created_at");
        $this->alterColumn("whitebook_auth_assignment", "modified_datetime", $this->integer());
        $this->renameColumn("whitebook_auth_assignment", "modified_datetime", "updated_at");
    }

    public function down()
    {
        echo "m160403_150838_fix_auth_issue cannot be reverted.\n";

        return false;
    }
}
