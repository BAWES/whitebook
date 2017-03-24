<?php

use yii\db\Migration;

class m160817_101820_top_category_sorting extends Migration
{
    public function up()
    {
        Yii::$app->db->createCommand('update {{%category}} SET sort="1" where category_name="Venues"')->execute();
        
        Yii::$app->db->createCommand('update {{%category}} SET sort="2" where category_name="Invitations"')->execute();

        Yii::$app->db->createCommand('update {{%category}} SET sort="3" where category_name="Food & Beverages"')->execute();

        Yii::$app->db->createCommand('update {{%category}} SET sort="4" where category_name="Decor"')->execute();

        Yii::$app->db->createCommand('update {{%category}} SET sort="5" where category_name="Supplies"')->execute();

        Yii::$app->db->createCommand('update {{%category}} SET sort="6" where category_name="Entertainment"')->execute();

        Yii::$app->db->createCommand('update {{%category}} SET sort="7" where category_name="Services"')->execute();

        Yii::$app->db->createCommand('update {{%category}} SET sort="8" where category_name="Others"')->execute();
        
        Yii::$app->db->createCommand('update {{%category}} SET sort="9" where category_name="Gift Favors"')->execute();
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
