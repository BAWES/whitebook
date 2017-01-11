<?php

use yii\db\Migration;

class m170111_075510_item_optimistic_lock extends Migration
{
    public function up()
    {
        $this->addColumn(
            'whitebook_vendor_item', 
            'version', 
            $this->bigInteger(1)->after('item_status')->defaultValue(0)
        );

        $this->addColumn(
            'whitebook_vendor_draft_item', 
            'version', 
            $this->bigInteger(1)->after('item_status')->defaultValue(0)
        );
    }
}
