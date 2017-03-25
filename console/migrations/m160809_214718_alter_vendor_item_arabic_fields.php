<?php

use yii\db\Migration;

class m160809_214718_alter_vendor_item_arabic_fields extends Migration
{
    public function up()
    {
        $this->alterColumn("{{%vendor_item}}", "item_name_ar", $this->text());
        $this->alterColumn("{{%vendor_item}}", "item_description_ar", $this->text());
        $this->alterColumn("{{%vendor_item}}", "item_additional_info_ar", $this->text());
        $this->alterColumn("{{%vendor_item}}", "item_customization_description_ar", $this->text());
        $this->alterColumn("{{%vendor_item}}", "item_price_description_ar", $this->text());
    }

    public function down()
    {
        echo "m160809_214718_alter_vendor_item_arabic_fields cannot be reverted.\n";

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
