<?php

use yii\db\Migration;
use common\models\VendorItemMenu;
use common\models\SuborderItemMenu;
use common\models\VendorItemMenuItem;

class m170131_090237_item_menu_addons extends Migration
{
    public function up()
    {
        // menu_type = addons /options 

        $this->addColumn('whitebook_vendor_item_menu', 'menu_type', "ENUM('addons', 'options') AFTER menu_name_ar");
        $this->addColumn('whitebook_suborder_item_menu', 'menu_type', "ENUM('addons', 'options') AFTER menu_name_ar");

        VendorItemMenu::updateAll(['menu_type' => 'options']);
        SuborderItemMenu::updateAll(['menu_type' => 'options']);

        // options will not have price 

        VendorItemMenuItem::updateAll(['price' => 0]);
    }
}
