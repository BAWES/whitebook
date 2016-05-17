<?php

use yii\db\Migration;
use yii\db\Expression;

class m160517_122048_update_package_dates extends Migration
{
    public function up()
    {
        $this->update("whitebook_vendor_packages", [
            'package_start_date' => new Expression('NOW()'),
            'created_datetime' => new Expression('NOW()'),
            'modified_datetime' => new Expression('NOW()'),
            'package_end_date' => '2018-03-29 07:00:00'
        ]);

    }

    public function down()
    {
        echo "m160517_122048_update_package_dates cannot be reverted.\n";

        return false;
    }

}
