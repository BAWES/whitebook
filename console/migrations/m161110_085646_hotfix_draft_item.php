<?php

use yii\db\Migration;

class m161110_085646_hotfix_draft_item extends Migration
{
    public function up()
    {
        $this->renameColumn('whitebook_vendor_draft_item', 'drafty_item_id', 'draft_item_id');
         
        $this->alterColumn ('whitebook_image', 'vendorimage_sort_order', $this->integer(11).' NULL');
    }
}
