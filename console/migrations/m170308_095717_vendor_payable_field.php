<?php

use yii\db\Migration;

class m170308_095717_vendor_payable_field extends Migration
{
    public function up()
    {
        $this->addColumn('{{%vendor}}', 'vendor_payable', $this->decimal(15, 3)->after('vendor_account_no'));
    }

    public function down()
    {
        $this->dropColumn('{{%vendor}}', 'vendor_payable', $this->integer(11)->after('vendor_id'));
    }
}
