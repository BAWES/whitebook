<?php

use yii\db\Migration;
use yii\db\Expression;

class m161121_062128_other_event_type extends Migration
{
    public function up()
    {
        return $this->insert('whitebook_event_type',[
            'type_name' => 'Other',
            'created_by' => '1',
            'modified_by' => '1',
            'created_datetime' => new Expression('NOW()'),
            'modified_datetime' => new Expression('NOW()'),
            'trash' => 'Default'
        ]);
    }

    public function down()
    {
        echo "m161121_062128_other_event_type cannot be reverted.\n";

        return false;
    }
}
