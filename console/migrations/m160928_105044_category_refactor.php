<?php

use yii\db\Migration;
use common\models\VendorItem;

class m160928_105044_category_refactor extends Migration
{
    public function up()
    {
        $this->createTable('{{%vendor_item_to_category}}', [
            'id' => $this->primaryKey(),
            'item_id' => $this->integer(11),
            'category_id' => $this->integer(11)
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');

        //fill table 
        $items = VendorItem::find()->all();

        foreach ($items as $key => $value) {
            Yii::$app->db->createCommand()->batchInsert(
                '{{%vendor_item_to_category}}', 
                [
                    'item_id', 
                    'category_id'
                ], 
                [
                    [
                        $value->item_id,
                        $value->category_id
                    ],
                    [
                        $value->item_id,
                        $value->subcategory_id
                    ],
                    [
                        $value->item_id,
                        $value->child_category
                    ]      
                ])
            ->execute();    
        }
    }

    public function down()
    {

    }
}
