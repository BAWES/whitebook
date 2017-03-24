<?php

use yii\db\Migration;

class m160812_075248_area_city_arabic extends Migration
{
    public function up()
    {
        $this->addColumn(
            '{{%city}}', 
            'city_name_ar', 
            $this->string()->notNull()->after('city_name')
        );

        $this->addColumn(
            '{{%location}}', 
            'location_ar', 
            $this->string()->notNull()->after('location')
        );
    }

    public function down()
    {
        
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
