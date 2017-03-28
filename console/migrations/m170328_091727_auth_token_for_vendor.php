<?php

use yii\db\Migration;

class m170328_091727_auth_token_for_vendor extends Migration
{
    public function up()
    {
        $this->addColumn('whitebook_vendor','auth_token',$this->char(100)->after('slug')->null());
    }

    public function down()
    {
        echo "m170328_091727_auth_token_for_vendor cannot be reverted.\n";

        return false;
    }
}
