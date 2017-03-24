<?php

use yii\db\Migration;

class m160830_103608_address_type_arabic extends Migration
{
    public function up()
    {
        $this->addColumn(
            '{{%address_type}}', 
            'type_name_ar', 
            $this->string()->notNull()->after('type_name')
        );

        Yii::$app->db->createCommand('update {{%address_type}} SET type_name_ar="نص وهمية" where type_name_ar="" OR type_name_ar IS NULL')->execute();

        $this->addColumn(
            '{{%country}}', 
            'country_name_ar', 
            $this->string()->notNull()->after('country_name')
        );

        Yii::$app->db->createCommand('update {{%country}} SET country_name_ar="نص وهمية" where country_name_ar="" OR country_name_ar IS NULL')->execute();
    }

    public function down()
    {

    }
}
