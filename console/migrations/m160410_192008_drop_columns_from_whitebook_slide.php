<?php

use yii\db\Migration;

class m160410_192008_drop_columns_from_whitebook_slide extends Migration
{
    public function up()
    {
        $this->dropColumn('whitebook_slide', 'created_by');
        $this->dropColumn('whitebook_slide', 'modified_by');
    }

    public function down()
    {
        echo "m160410_192008_drop_columns_from_whitebook_slide cannot be reverted.\n";

        return false;
    }
}
