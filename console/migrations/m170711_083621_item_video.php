<?php

use yii\db\Migration;

class m170711_083621_item_video extends Migration
{
    public function up()
    {
        $this->createTable('{{%vendor_item_video}}', [
            'video_id' => $this->primaryKey(),
            'item_id' => $this->integer(11) . ' UNSIGNED',
            'video' => $this->string(100),
            'video_sort_order' => $this->integer(11),
            'created_datetime' => $this->dateTime(),
            'modified_datetime' => $this->dateTime(),
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');

        $this->createIndex ('ind-item_video-item_id', '{{%vendor_item_video}}', 'item_id');

        $this->addForeignKey('fk-item_video-item_id', '{{%vendor_item_video}}', 'item_id', '{{%vendor_item}}', 'item_id', 'SET NULL' , 'SET NULL');

        $this->createTable('{{%vendor_draft_item_video}}', [
            'draft_video_id' => $this->primaryKey(),
            'item_id' => $this->integer(11) . ' UNSIGNED',
            'video' => $this->string(100),
            'video_sort_order' => $this->integer(11),
            'created_datetime' => $this->dateTime(),
            'modified_datetime' => $this->dateTime(),
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');

        $this->createIndex ('ind-item_video-item_id', '{{%vendor_draft_item_video}}', 'item_id');

        $this->addForeignKey('fk-draft_item_video-item_id', '{{%vendor_draft_item_video}}', 'item_id', '{{%vendor_item}}', 'item_id', 'SET NULL' , 'SET NULL');
    }
}
