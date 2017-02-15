<?php

use yii\db\Migration;

class m170214_082333_menu_dirty_field extends Migration
{
    public function up()
    {
        $this->addColumn('whitebook_vendor_draft_item_menu', 'menu_id', $this->integer(11)->after('draft_menu_id'));
        $this->addColumn('whitebook_vendor_draft_item_menu_item', 'menu_item_id', $this->integer(11)->after('draft_menu_item_id'));
    }
}
