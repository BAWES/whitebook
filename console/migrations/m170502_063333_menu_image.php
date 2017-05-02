<?php

use yii\db\Migration;

class m170502_063333_menu_image extends Migration
{
    public function up()
    {
        $this->addColumn('whitebook_vendor_draft_item_menu_item', 'image', $this->string(255)->after('menu_item_name_ar'));

        $this->addColumn('whitebook_vendor_item_menu_item', 'image', $this->string(255)->after('menu_item_name_ar'));
    }
}
