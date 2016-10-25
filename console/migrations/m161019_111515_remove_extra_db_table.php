<?php

use yii\db\Migration;

class m161019_111515_remove_extra_db_table extends Migration
{
    public function up()
    {
        $this->dropTable('whitebook_token'); 
        $this->dropTable('whitebook_vendor_item_image'); 
        $this->dropTable('whitebook_social_account'); 

        $this->alterColumn ('whitebook_location', 'country_id', $this->integer(11) . ' NULL');
        $this->alterColumn ('whitebook_location', 'city_id', $this->integer(11) . ' NULL');

        $this->addForeignKey ('location_city_fk', 'whitebook_location', 'city_id', 'whitebook_city', 'city_id', 'SET NULL' , 'SET NULL');
        $this->addForeignKey ('location_country_fk', 'whitebook_location', 'country_id', 'whitebook_country', 'country_id', 'SET NULL' , 'SET NULL');
    }

    public function down()
    {
    }
}
