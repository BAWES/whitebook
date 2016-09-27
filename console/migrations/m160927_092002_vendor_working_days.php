<?php

use yii\db\Migration;

class m160927_092002_vendor_working_days extends Migration
{
    public function up()
    {        
        $this->addColumn('whitebook_vendor', 'working_days', $this->string(100)->after('vendor_working_hours_to'));
    }

    public function down()
    {
    }
}
