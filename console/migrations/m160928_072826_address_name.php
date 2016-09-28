<?php

use yii\db\Migration;

class m160928_072826_address_name extends Migration
{
    public function up()
    {
        $this->addColumn('whitebook_customer_address', 'address_name', $this->string(100)->after('area_id'));

        //fill all blank entry 
        Yii::$app->db->createCommand('Update whitebook_customer_address set address_name = address_data WHERE address_name="" OR address_name IS NULL')->execute();
    }

    public function down()
    {
       $this->dropColumn('whitebook_customer_address', 'address_name');
    }
}
