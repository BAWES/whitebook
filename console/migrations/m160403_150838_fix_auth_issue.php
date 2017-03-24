<?php

use yii\db\Migration;

class m160403_150838_fix_auth_issue extends Migration
{
    public function up()
    {
        //Fix columns in {{%auth_item}} with issues
        $this->dropColumn("{{%auth_item}}", "created_datetime");
        $this->addColumn('{{%auth_item}}', 'created_at', $this->integer());

        $this->dropColumn("{{%auth_item}}", "modified_datetime");
        $this->addColumn('{{%auth_item}}', 'updated_at', $this->integer());

        //Fix columns in {{%auth_assignment}} with issues
        $this->dropColumn("{{%auth_assignment}}", "created_datetime");
        $this->addColumn('{{%auth_assignment}}', 'created_at', $this->integer());

        $this->dropColumn("{{%auth_assignment}}", "modified_datetime");
        $this->addColumn('{{%auth_assignment}}', 'updated_at', $this->integer());
    }

    public function down()
    {
        echo "m160403_150838_fix_auth_issue cannot be reverted.\n";

        return false;
    }
}
