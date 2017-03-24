<?php

use yii\db\Schema;
use yii\db\Migration;

class m160308_054156_whitebook_category extends Migration
{
    public function up()
    {
         $this->insert('{{%category}}',array(
                     'parent_category_id'=>0,
                      'category_level'=>0,
                      'category_name'=>'Venues',
                      'category_icon'=>0,
                      'category_url'=>'Venues',
                      'category_allow_sale'=>'yes',
                      'top_ad'=>'',
                      'bottom_ad'=>'yes',
                      'sort'=>'0',
                      'slug'=>'venues',
                      'created_by'=>'0',
                      'modified_by'=>'1',
                      'created_datetime'=>'2016-03-02 00:00:00',
                      'modified_datetime'=>'2016-03-01 00:00:00',
                      'trash'=>'Default',
                      'category_meta_title'=>'',
                      'category_meta_keywords'=>'',
                      'category_meta_description'=>''
                 ));
    }

    public function down()
    {
        echo "m160308_054156_whitebook_category cannot be reverted.\n";

        return false;
    }
}
