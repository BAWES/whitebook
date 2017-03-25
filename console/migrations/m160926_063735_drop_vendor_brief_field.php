<?php

use yii\db\Migration;

class m160926_063735_drop_vendor_brief_field extends Migration
{
    public function up()
    {
        $this->dropColumn('{{%vendor}}', 'vendor_brief');
    }

    public function down()
    {
        $this->alterColumn('{{%vendor}}', 'vendor_brief', $this->text());

        return false;
    }
}
