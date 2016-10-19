<?php

use yii\db\Migration;

class m161019_063948_whitebook_order_relation_with_customer extends Migration
{
    public function up()
    {
        $this->dropIndex(
            'customer_id',
            'whitebook_order'
        );

        $this->alterColumn (
            'whitebook_order',
            'customer_id',
            $this->integer(11) . ' UNSIGNED NULL'
        );

        $this->addForeignKey(
            'customer_id_fk',
            'whitebook_order',
            'customer_id',
            'whitebook_customer',
            'customer_id',
            'SET NULL' ,
            'SET NULL'
        );
    }

    public function down()
    {
    }
}
