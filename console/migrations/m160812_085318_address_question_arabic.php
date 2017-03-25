<?php

use yii\db\Migration;

class m160812_085318_address_question_arabic extends Migration
{
    public function up()
    {
        $this->addColumn(
            '{{%address_question}}', 
            'question_ar', 
            $this->string()->notNull()->after('question')
        );

        //question
        Yii::$app->db->createCommand('update {{%address_question}} SET question_ar="نص وهمية" where question_ar="" OR question_ar IS NULL')->execute();

        //location
        Yii::$app->db->createCommand('update {{%location}} SET location_ar="نص وهمية" where location_ar="" OR location_ar IS NULL')->execute();
        
        //city
        Yii::$app->db->createCommand('update {{%city}} SET city_name_ar="نص وهمية" where city_name_ar="" OR city_name_ar IS NULL')->execute();
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
