<?php

use yii\db\Migration;
use yii\db\Expression;

class m160817_101533_gift_shop_migrate extends Migration
{
    public function up()
    {
        Yii::$app->db->createCommand('DELETE FROM `whitebook_category` WHERE `whitebook_category`.`slug` = "say-thank-you"')->execute(); #delete say thank you
        Yii::$app->db->createCommand('DELETE FROM `whitebook_category` WHERE `whitebook_category`.`slug` = "gift-favors"')->execute(); #delete gift favors

        $this->insert('whitebook_category',array(
            'parent_category_id'=>0,
            'category_level'=>0,
            'category_name'=>'Gift Favor',
            'category_name_ar'=>'نص وهمية',
            'icon'=>'saythankyou-category',
            'category_allow_sale'=>'yes',
            'category_meta_title'=>'Whitebook | Gift Favor',
            'category_meta_keywords'=>'Whitebook | Gift Favor',
            'top_ad'=>'',
            'bottom_ad'=>'yes',
            'sort'=>'9',
            'slug'=>'gift-favors',
            'category_meta_description'=>'Whitebook | Gift Favor',
            'created_by'=>'1',
            'modified_by'=>'1',
            'created_datetime' => new Expression('NOW()'),
            'modified_datetime' => new Expression('NOW()'),
            'trash'=>'Default',
        ));
    }

    public function down()
    {
        echo "m160817_101533_gift_shop_migrate cannot be reverted.\n";

        return false;
    }
}
