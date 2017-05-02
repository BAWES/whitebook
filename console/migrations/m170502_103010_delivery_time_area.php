<?php

use yii\db\Migration;

class m170502_103010_delivery_time_area extends Migration
{
    public function up()
    {        
        $this->dropForeignKey('cart_area_fk', 'whitebook_customer_cart');

        $this->dropColumn('whitebook_customer_cart', 'area_id');
        $this->dropColumn('whitebook_customer_cart', 'time_slot');
        $this->dropColumn('whitebook_customer_cart', 'cart_delivery_date');
    }
}
