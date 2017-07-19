<?php

use yii\db\Migration;

class m170719_071544_vendor_review extends Migration
{
    public function up()
    {    
        $this->createTable('{{%vendor_review}}', [
            'review_id' => $this->primaryKey(),
            'customer_id' => $this->integer(11) . ' UNSIGNED',
            'vendor_id' => $this->integer(11) . ' UNSIGNED',
            'rating' =>  $this->smallInteger(1),
            'review' => $this->text(),
            'approved' => $this->smallInteger(1),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime()
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');

        $this->createIndex ('ind-vendor_review-customer_id', '{{%vendor_review}}', 'customer_id');

        $this->addForeignKey (
            'fk-vendor_review-customer_id',
            'whitebook_vendor_review',
            'customer_id',
            'whitebook_customer',
            'customer_id',
            'SET NULL' ,
            'SET NULL'
        );

        $this->createIndex ('ind-vendor_review-vendor_id', '{{%vendor_review}}', 'vendor_id');

        $this->addForeignKey (
            'fk-vendor_review-vendor_id',
            'whitebook_vendor_review',
            'vendor_id',
            'whitebook_vendor',
            'vendor_id',
            'SET NULL' ,
            'SET NULL'
        );
    }

    public function down()
    {
        echo "m170719_071544_vendor_review cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
