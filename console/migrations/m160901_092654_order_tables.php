<?php

use yii\db\Migration;

class m160901_092654_order_tables extends Migration
{
    public function up()
    {
        //order status 
        if (in_array('{{%order_status}}', $this->getDb()->schema->tableNames)) {
            $this->dropTable('{{%order_status}}');
        }

        $this->createTable('{{%order_status}}', [
            'order_status_id' => $this->primaryKey(),
            'name' => $this->string(100)->notNull(),
            'name_ar' => $this->string(100)->notNull()
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');

        //insert data 
        $this->batchInsert('{{%order_status}}', ['name', 'name_ar'], [
            ['Processing', 'معالجة'],
            ['Shipped', 'تم شحنه'],
            ['Canceled', 'ألغيت'],
            ['Complete', 'كامل'],
            ['Failed', 'فشل'],
            ['Refunded', 'ردها'],
            ['Reversed', 'عكس'],
            ['Pending', 'ريثما'],
            ['Processed', 'معالجة'],
            ['Expired', 'منتهية الصلاحية']
        ]);

        //payment gateway 
        if (in_array('{{%payment_gateway}}', $this->getDb()->schema->tableNames)) {
            $this->dropTable('{{%payment_gateway}}');
        }
        
        $this->createTable('{{%payment_gateway}}', [
            'gateway_id' => $this->primaryKey(),
            'name' => $this->string(100)->notNull(),
            'name_ar' => $this->string(100)->notNull(),
            'code' => $this->string(100)->notNull(),
            'percentage' => $this->decimal(5,2),
            'order_status_id' => $this->integer(11),
            'under_testing' => $this->smallInteger(1),
            'status' => $this->smallInteger(1)
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');

        //insert data 
        $this->insert('{{%payment_gateway}}', [
            'gateway_id' => 1,
            'name' => 'Cash On Delivery',
            'name_ar' => 'شسلةثف',
            'code' => 'cod',
            'percentage' => '0',
            'order_status_id' => 1,
            'under_testing' => 1,
            'status' => 1
        ]);

        $this->insert('{{%payment_gateway}}', [
            'gateway_id' => 2,
            'name' => 'Credit Card / Debit Card (Tap)',
            'name_ar' => 'بطاقة الائتمان بطاقة الخصم المباشر / ( وات)',
            'code' => 'tap',
            'percentage' => '0',
            'order_status_id' => 1,
            'under_testing' => 1,
            'status' => 1
        ]);

        //order tabel datetime 
        $this->dropColumn('{{%order}}', 'order_datetime');
        $this->dropColumn('{{%suborder}}', 'suborder_datetime');
        $this->dropColumn('{{%suborder_item_purchase}}', 'purchase_datetime');
        $this->alterColumn ('{{%order}}', 'created_date', $this->dateTime());
        $this->renameColumn('{{%order}}', 'created_date', 'created_datetime');
        $this->renameColumn('{{%order}}', 'modified_date', 'modified_datetime');
    }
}
