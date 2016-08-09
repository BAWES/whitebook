<?php

use yii\db\Migration;

class m160804_084531_arabic_dummy_values extends Migration
{
    public function up()
    {
        //category
        Yii::$app->db->createCommand('update whitebook_category SET category_name_ar="نص وهمية" where category_name_ar="" OR category_name_ar IS NULL')->execute();

        //FAQ
        Yii::$app->db->createCommand('update whitebook_faq SET question_ar="نص وهمية" where question_ar="" OR question_ar IS NULL')->execute();
        Yii::$app->db->createCommand('update whitebook_faq SET answer_ar="نص وهمية" where answer_ar="" OR answer_ar IS NULL')->execute();

        //static pages
        Yii::$app->db->createCommand('update whitebook_cms SET page_name_ar="نص وهمية" where page_name_ar="" OR page_name_ar IS NULL')->execute();
        Yii::$app->db->createCommand('update whitebook_cms SET page_content_ar="نص وهمية" where page_content_ar="" OR page_content_ar IS NULL')->execute();

        //Feature group
        Yii::$app->db->createCommand('update whitebook_feature_group SET group_name_ar="نص وهمية" where group_name_ar="" OR group_name_ar IS NULL')->execute();

        //Vendor
        Yii::$app->db->createCommand('update whitebook_vendor SET vendor_return_policy_ar="نص وهمية" where vendor_return_policy_ar="" OR vendor_return_policy_ar IS NULL')->execute();
        Yii::$app->db->createCommand('update whitebook_vendor SET vendor_contact_address_ar="نص وهمية" where vendor_contact_address_ar="" OR vendor_contact_address_ar IS NULL')->execute();
        Yii::$app->db->createCommand('update whitebook_vendor SET short_description_ar="نص وهمية" where short_description_ar="" OR short_description_ar IS NULL')->execute();

        //arabic values for vendor_item - item_name_ar
        Yii::$app->db->createCommand('update whitebook_vendor_item SET item_name_ar="منتج" where item_name_ar="" OR item_name_ar IS NULL')->execute();

        Yii::$app->db->createCommand('update whitebook_vendor_item SET item_description_ar="العديد من المواقع الحديثة العهد في نتائج البحث. على مدى السنين ظهرت نسخ جديدة ومختلفة من نص لوريم إيبسوم، أحياناً عن طريق الصدفة وأحياناً عن عمد كإدخال بعض العبارات الفكاهية إليها." where item_description_ar="" OR item_description_ar IS NULL')->execute();
        Yii::$app->db->createCommand('update whitebook_vendor_item SET item_additional_info_ar="العديد من المواقع الحديثة العهد في نتائج البحث. على مدى السنين ظهرت نسخ جديدة ومختلفة من نص لوريم إيبسوم، أحياناً عن طريق الصدفة وأحياناً عن عمد كإدخال بعض العبارات الفكاهية إليها." where item_additional_info_ar="" OR item_additional_info_ar IS NULL')->execute();

        Yii::$app->db->createCommand('update whitebook_vendor_item SET item_customization_description_ar="العديد من المواقع الحديثة العهد في نتائج البحث. على مدى السنين ظهرت نسخ جديدة ومختلفة من نص لوريم إيبسوم، أحياناً عن طريق الصدفة وأحياناً عن عمد كإدخال بعض العبارات الفكاهية إليها." where item_customization_description_ar="" OR item_customization_description_ar IS NULL')->execute();

        Yii::$app->db->createCommand('update whitebook_vendor_item SET item_price_description_ar="العديد من المواقع الحديثة العهد في نتائج البحث. على مدى السنين ظهرت نسخ جديدة ومختلفة من نص لوريم إيبسوم، أحياناً عن طريق الصدفة وأحياناً عن عمد كإدخال بعض العبارات الفكاهية إليها." where item_price_description_ar="" OR item_price_description_ar IS NULL')->execute();
    }

    public function down()
    {

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
