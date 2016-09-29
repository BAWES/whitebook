<?php

use yii\db\Migration;

class m160929_044235_item_group_refactor extends Migration
{
    public function up()
    {
        $this->dropColumn('whitebook_feature_group_item', 'featured_start_date');
        $this->dropColumn('whitebook_feature_group_item', 'featured_end_date');
        $this->dropColumn('whitebook_feature_group_item', 'featured_sort');
        $this->dropColumn('whitebook_feature_group_item', 'created_by');
        $this->dropColumn('whitebook_feature_group_item', 'modified_by');
        $this->dropColumn('whitebook_feature_group_item', 'subcategory_id');
        $this->dropColumn('whitebook_feature_group_item', 'category_id');
    }

    public function down()
    {
        $this->addColumn('whitebook_feature_group_item', 'featured_start_date',$this->date());
        $this->addColumn('whitebook_feature_group_item', 'featured_end_date',$this->date());
        $this->addColumn('whitebook_feature_group_item', 'featured_sort',$this->integer(11));
        $this->addColumn('whitebook_feature_group_item', 'created_by',$this->integer(11));
        $this->addColumn('whitebook_feature_group_item', 'modified_by',$this->integer(11));
        $this->addColumn('whitebook_feature_group_item', 'subcategory_id',$this->integer(11));
        $this->addColumn('whitebook_feature_group_item', 'category_id',$this->integer(11));
        return false;
    }
}
