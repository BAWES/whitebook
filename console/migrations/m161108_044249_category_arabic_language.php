<?php

use yii\db\Migration;

class m161108_044249_category_arabic_language extends Migration
{
    public function up()
    {
        Yii::$app->db->createCommand("UPDATE `whitebook_category` SET `category_name_ar` = 'المأكولات و المشروبات' WHERE `whitebook_category`.`slug` = 'food-beverage'")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_category` SET `category_name_ar` = 'ديكور' WHERE `whitebook_category`.`slug` = 'decor';")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_category` SET `category_name_ar` = 'تسلية' WHERE `whitebook_category`.`slug` = 'entertainment'")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_category` SET `category_name_ar` = 'مخبز' WHERE `whitebook_category`.`slug` = 'bakery';")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_category` SET `category_name_ar` = 'الكعك' WHERE `whitebook_category`.`slug` = 'cakes';")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_category` SET `category_name_ar` = 'الديكور مرحلة' WHERE `whitebook_category`.`slug` = 'stage-decoration';")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_category` SET `category_name_ar` = 'الديكور عيد الميلاد' WHERE `whitebook_category`.`slug` = 'birthday-decor';")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_category` SET `category_name_ar` =  'الغابة السوداء' WHERE `whitebook_category`.`slug` = 'black-forest';")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_category` SET `category_name_ar` =  'كعكة eggless' WHERE `whitebook_category`.`slug` = 'egg-less-cake';")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_category` SET `category_name_ar` =  'ألعب لعبة' WHERE `whitebook_category`.`slug` = 'play-game';")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_category` SET `category_name_ar` =  'لعبة ترفيهية' WHERE `whitebook_category`.`slug` = 'entertainment-game';")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_category` SET `category_name_ar` =  'الزواج مرحلة الديكور' WHERE `whitebook_category`.`slug` = 'marriage-stage-decoration';")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_category` SET `category_name_ar` =   'حلوى من سكر أسمر وزبدة' WHERE `whitebook_category`.`slug` = 'butter-scotch';")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_category` SET `category_name_ar` =  'عالي الدقة' WHERE `whitebook_category`.`slug` = 'high-definition';")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_category` SET `category_name_ar` =  'التصوير' WHERE `whitebook_category`.`slug` = 'photography';")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_category` SET `category_name_ar` =  'اللوازم' WHERE `whitebook_category`.`slug` = 'supplies';")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_category` SET `category_name_ar` =  'خدمات' WHERE `whitebook_category`.`slug` = 'services';")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_category` SET `category_name_ar` =  'دعوات' WHERE `whitebook_category`.`slug` = 'invitations';")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_category` SET `category_name_ar` =   'بطاقات الزواج' WHERE `whitebook_category`.`slug` = 'marriage-cards';")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_category` SET `category_name_ar` =  'بطاقات الاستقبال' WHERE `whitebook_category`.`slug` = 'reception-cards';")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_category` SET `category_name_ar` =   'بطاقات الهندوسية' WHERE `whitebook_category`.`slug` = 'hindu-cards';")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_category` SET `category_name_ar` =  'الاستقبال الخاص بالعرس' WHERE `whitebook_category`.`slug` = 'wedding-reception';")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_category` SET `category_name_ar` =  'لابتوب' WHERE `whitebook_category`.`slug` = 'laptop';")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_category` SET `category_name_ar` =   'DELL' WHERE `whitebook_category`.`slug` = 'dell';")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_category` SET `category_name_ar` =  'عينة' WHERE `whitebook_category`.`slug` = 'sample';")->execute();

        Yii::$app->db->createCommand("UPDATE `whitebook_category` SET `category_name_ar` =  'عينة' WHERE `whitebook_category`.`slug` = 'sample';")->execute();

        Yii::$app->db->createCommand("UPDATE `whitebook_category` SET `category_name_ar` =  'قطع الغيار' WHERE `whitebook_category`.`slug` = 'spares';")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_category` SET `category_name_ar` =  'قطع الغيار الدراجة' WHERE `whitebook_category`.`slug` = 'bike-spares';")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_category` SET `category_name_ar` =  'ثمار' WHERE `whitebook_category`.`slug` = 'fruits';")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_category` SET `category_name_ar` =  'البرتقالي' WHERE `whitebook_category`.`slug` = 'orange';")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_category` SET `category_name_ar` =  'بالونات' WHERE `whitebook_category`.`slug` = 'baloons';")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_category` SET `category_name_ar` =  'Vennila' WHERE `whitebook_category`.`slug` = 'vennila';")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_category` SET `category_name_ar` =  'كعكة الملاك' WHERE `whitebook_category`.`slug` = 'angel-cake';")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_category` SET `category_name_ar` =  'الديكور النص' WHERE `whitebook_category`.`slug` = 'Test-Decoration';")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_category` SET `category_name_ar` =  'الحدث الميلاد' WHERE `whitebook_category`.`slug` = 'birthday-event';")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_category` SET `category_name_ar` =  'الديكور عيد الميلاد' WHERE `whitebook_category`.`slug` = 'birthday-decor';")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_category` SET `category_name_ar` =  'رصاصة' WHERE `whitebook_category`.`slug` = 'Bullet';")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_category` SET `category_name_ar` =  'أماكن' WHERE `whitebook_category`.`slug` = 'venues';")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_category` SET `category_name_ar` =  'آخرون' WHERE `whitebook_category`.`slug` = 'others';")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_category` SET `category_name_ar` =  'تلفزيون' WHERE `whitebook_category`.`slug` = 'television';")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_category` SET `category_name_ar` =  'ايفون' WHERE `whitebook_category`.`slug` = 'Iphone';")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_category` SET `category_name_ar` =  'سامسونج' WHERE `whitebook_category`.`slug` = 'Samsung';")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_category` SET `category_name_ar` =  'صفقات' WHERE `whitebook_category`.`slug` = 'deals';")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_category` SET `category_name_ar` =  'بطاقات البيانات' WHERE `whitebook_category`.`slug` = 'Datacards';")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_category` SET `category_name_ar` =  'عروض' WHERE `whitebook_category`.`slug` = 'offers';")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_category` SET `category_name_ar` =  'سماعات الرأس' WHERE `whitebook_category`.`slug` = 'Headphones';")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_category` SET `category_name_ar` =  'قاعة الحزب مع التيار المتردد' WHERE `whitebook_category`.`slug` = 'Party-Hall-With-AC';")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_category` SET `category_name_ar` =   'صالة أفراح بدون AC' WHERE `whitebook_category`.`slug` = 'Party-Hall-Without-AC';")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_category` SET `category_name_ar` =  'هدية الإحسان' WHERE `whitebook_category`.`slug` = 'gift-favors';")->execute();
    }

    public function down()
    {
        Yii::$app->db->createCommand("UPDATE `whitebook_category` SET `category_name_ar` = 'نص وهمية' WHERE 1")->execute();

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
