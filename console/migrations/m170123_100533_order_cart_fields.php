<?php

use yii\db\Migration;

class m170123_100533_order_cart_fields extends Migration
{
    public function up()
    {
        // item 

        $this->addColumn(
            'whitebook_vendor_item', 
            'allow_special_request', 
            $this->smallInteger(1)->after('item_price_per_unit')
        );
        
        $this->addColumn(
            'whitebook_vendor_item', 
            'have_female_service', 
            $this->smallInteger(1)->after('item_price_per_unit')
        );

        // cart 

        $this->addColumn(
            'whitebook_customer_cart', 
            'special_request', 
            $this->text()->after('cart_quantity')
        );
        
        $this->addColumn(
            'whitebook_customer_cart', 
            'female_service', 
            $this->smallInteger(1)->after('cart_quantity')
        );

        // suborder item purchase 

        $this->addColumn(
            'whitebook_suborder_item_purchase', 
            'special_request', 
            $this->text()->after('purchase_total_price')
        );

        $this->addColumn(
            'whitebook_suborder_item_purchase', 
            'female_service', 
            $this->smallInteger(1)->after('purchase_total_price')
        );
    }

    public function down() {

        $this->dropColumn(
            'whitebook_vendor_item', 
            'allow_special_request'
        );
        
        $this->dropColumn(
            'whitebook_vendor_item', 
            'have_female_service'
        );

        // cart 

        $this->dropColumn(
            'whitebook_customer_cart', 
            'special_request'
        );
        
        $this->dropColumn(
            'whitebook_customer_cart', 
            'female_service'
        );

        // suborder item purchase 

        $this->dropColumn(
            'whitebook_suborder_item_purchase', 
            'special_request'
        );

        $this->dropColumn(
            'whitebook_suborder_item_purchase', 
            'female_service'
        );
    }
}
