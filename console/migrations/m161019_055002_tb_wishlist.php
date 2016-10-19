<?php

use yii\db\Migration;

class m161019_055002_tb_wishlist extends Migration
{
    public function up()
    {
        $this->truncateTable(
            'whitebook_wishlist'
        );

        $this->dropForeignKey(
            'wishlist_to_item_fk',
            'whitebook_wishlist'
        );

        $this->dropForeignKey(
            'wishlist_to_customer_fk',
            'whitebook_wishlist'
        );

        $this->dropIndex(
            'wishlist_to_item_fk',
            'whitebook_wishlist'
        );

        $this->dropIndex(
            'wishlist_to_customer_fk',
            'whitebook_wishlist'
        );

        Yii::$app->db->createCommand("ALTER TABLE whitebook_wishlist ENGINE = InnoDB; ")->execute();

        $this->alterColumn (
            'whitebook_wishlist',
            'item_id',
            $this->integer(11) . ' UNSIGNED NULL'
        );

        $this->addForeignKey (
            'wishlist_to_item_fk',
            'whitebook_wishlist',
            'item_id',
            'whitebook_vendor_item',
            'item_id',
            'SET NULL' ,
            'SET NULL'
        );

        $this->alterColumn (
            'whitebook_wishlist',
            'customer_id',
            $this->integer(11) . ' UNSIGNED NULL'
        );

        $this->addForeignKey (
            'wishlist_to_customer_fk',
            'whitebook_wishlist',
            'customer_id',
            'whitebook_customer',
            'customer_id',
            'SET NULL' ,
            'SET NULL'
        );

    }

    public function down()
    {

        $this->truncateTable(
            'whitebook_wishlist'
        );

        $this->dropForeignKey(
            'wishlist_to_item_fk',
            'whitebook_wishlist'
        );

        $this->dropForeignKey(
            'wishlist_to_customer_fk',
            'whitebook_wishlist'
        );

        $this->dropIndex(
            'wishlist_to_item_fk',
            'whitebook_wishlist'
        );

        $this->dropIndex(
            'wishlist_to_customer_fk',
            'whitebook_wishlist'
        );

        Yii::$app->db->createCommand("ALTER TABLE whitebook_wishlist ENGINE = MyISAM; ")->execute();

        $this->alterColumn (
            'whitebook_wishlist',
            'item_id',
            $this->integer(11) . ' UNSIGNED NULL'
        );

        $this->addForeignKey (
            'wishlist_to_item_fk',
            'whitebook_wishlist',
            'item_id',
            'whitebook_vendor_item',
            'item_id',
            'SET NULL' ,
            'SET NULL'
        );

        $this->alterColumn (
            'whitebook_wishlist',
            'customer_id',
            $this->integer(11) . ' UNSIGNED NULL'
        );

        $this->addForeignKey (
            'wishlist_to_customer_fk',
            'whitebook_wishlist',
            'customer_id',
            'whitebook_customer',
            'customer_id',
            'SET NULL' ,
            'SET NULL'
        );

        return false;
    }
}
