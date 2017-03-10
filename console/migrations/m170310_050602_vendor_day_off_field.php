<?php

use yii\db\Migration;

class m170310_050602_vendor_day_off_field extends Migration
{
    public function up()
    {
        $this->dropColumn('whitebook_vendor','day_off');
        $this->dropColumn('whitebook_vendor','vendor_working_hours');
        $this->dropColumn('whitebook_vendor','vendor_working_hours_to');
        $this->dropColumn('whitebook_vendor','vendor_working_min_to');
    }

    public function down()
    {
        echo "m170310_050602_vendor_day_off_field cannot be reverted.\n";

        return false;
    }
}
