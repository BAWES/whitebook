<?php

use yii\db\Migration;

class m160817_074411_category_icon_field_update extends Migration
{
    public function up()
    {
        Yii::$app->db->createCommand('update {{%category}} SET icon="venues-category" where category_id="125"')->execute();
        Yii::$app->db->createCommand('update {{%category}} SET icon="venues-category" where category_id="140"')->execute();
        Yii::$app->db->createCommand('update {{%category}} SET icon="invitation-category" where category_id="103"')->execute();
        Yii::$app->db->createCommand('update {{%category}} SET icon="food-category" where category_id="85"')->execute();
        Yii::$app->db->createCommand('update {{%category}} SET icon="decor-category" where category_id="86"')->execute();
        Yii::$app->db->createCommand('update {{%category}} SET icon="supply-category" where category_id="101"')->execute();
        Yii::$app->db->createCommand('update {{%category}} SET icon="enter-category" where category_id="87"')->execute();
        Yii::$app->db->createCommand('update {{%category}} SET icon="service-category" where category_id="102"')->execute();
        Yii::$app->db->createCommand('update {{%category}} SET icon="others-category" where category_id="126"')->execute();
        Yii::$app->db->createCommand('update {{%category}} SET icon="others-category" where category_id="139"')->execute();
        Yii::$app->db->createCommand('update {{%category}} SET icon="saythankyou-category" where category_id="127"')->execute();
    }

    public function down()
    {
        echo "m160817_074411_category_icon_field_update cannot be reverted.\n";

        return false;
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
