<?php

use yii\db\Migration;

class m170131_090237_item_menu_addons extends Migration
{
    public function up()
    {
        // menu_type = addons /options 

        $this->addColumn('{{%vendor_item_menu}}', 'menu_type', "ENUM('addons', 'options') AFTER menu_name_ar");
        $this->addColumn('{{%suborder_item_menu}}', 'menu_type', "ENUM('addons', 'options') AFTER menu_name_ar");

        Yii::$app->db->createCommand('update {{%vendor_item_menu}} set menu_type="options"')->execute();
        
        // options will not have price 

        Yii::$app->db->createCommand('update {{%vendor_item_menu_item}} set price="0"')->execute();
    }
}
