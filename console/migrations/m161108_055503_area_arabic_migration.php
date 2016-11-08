<?php

use yii\db\Migration;

class m161108_055503_area_arabic_migration extends Migration
{
    public function up()
    {
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'عبد الله سالم' WHERE `whitebook_location`.`id` = 14;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'العديلية' WHERE `whitebook_location`.`id` = 15;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'الراعي' WHERE `whitebook_location`.`id` = 16;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'بنيد القار' WHERE `whitebook_location`.`id` = 17;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'الدعية' WHERE `whitebook_location`.`id` = 18;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'الدسمة' WHERE `whitebook_location`.`id` = 19;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'دسمان' WHERE `whitebook_location`.`id` = 20;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'الدوحة' WHERE `whitebook_location`.`id` = 21;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'الفيحاء' WHERE `whitebook_location`.`id` = 22;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'Ghornata' WHERE `whitebook_location`.`id` = 23;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'جابر الأحمد' WHERE `whitebook_location`.`id` = 24;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'كيفان' WHERE `whitebook_location`.`id` = 25;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'الخالدية' WHERE `whitebook_location`.`id` = 26;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'مدينة الكويت' WHERE `whitebook_location`.`id` = 27;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'المنصورية' WHERE `whitebook_location`.`id` = 28;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'المرقاب' WHERE `whitebook_location`.`id` = 29;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'النهضة' WHERE `whitebook_location`.`id` = 30;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'النزهة' WHERE `whitebook_location`.`id` = 31;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'القادسية' WHERE `whitebook_location`.`id` = 32;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'القيروان' WHERE `whitebook_location`.`id` = 33;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'القبلة' WHERE `whitebook_location`.`id` = 34;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'قرطبة' WHERE `whitebook_location`.`id` = 35;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'الروضة' WHERE `whitebook_location`.`id` = 36;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'الصالحية' WHERE `whitebook_location`.`id` = 37;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'الصوابر' WHERE `whitebook_location`.`id` = 38;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'الشامية' WHERE `whitebook_location`.`id` = 39;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'الشرق' WHERE `whitebook_location`.`id` = 40;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'الشويخ' WHERE `whitebook_location`.`id` = 41;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'الصليبخات' WHERE `whitebook_location`.`id` = 42;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'السرة' WHERE `whitebook_location`.`id` = 43;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'اليرموك' WHERE `whitebook_location`.`id` = 44;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'آل بديع' WHERE `whitebook_location`.`id` = 45;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'بيان' WHERE `whitebook_location`.`id` = 46;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'حولي' WHERE `whitebook_location`.`id` = 47;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'حطين' WHERE `whitebook_location`.`id` = 48;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'الجابرية' WHERE `whitebook_location`.`id` = 49;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'ميدان حولي' WHERE `whitebook_location`.`id` = 50;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'مشرف' WHERE `whitebook_location`.`id` = 51;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'مبارك العبد الله' WHERE `whitebook_location`.`id` = 52;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'الرميثية' WHERE `whitebook_location`.`id` = 53;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'السلام' WHERE `whitebook_location`.`id` = 54;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'السالمية' WHERE `whitebook_location`.`id` = 55;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'سلوى' WHERE `whitebook_location`.`id` = 56;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'الشعب' WHERE `whitebook_location`.`id` = 57;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'الشهداء' WHERE `whitebook_location`.`id` = 58;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'الصديق' WHERE `whitebook_location`.`id` = 59;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'الزهراء' WHERE `whitebook_location`.`id` = 60;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'العباسية' WHERE `whitebook_location`.`id` = 61;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'عبد الله المبارك' WHERE `whitebook_location`.`id` = 62;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'أبرق خيطان ' WHERE `whitebook_location`.`id` = 63;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'مطار' WHERE `whitebook_location`.`id` = 64;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'الأندلس' WHERE `whitebook_location`.`id` = 65;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'العارضية' WHERE `whitebook_location`.`id` = 66;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'العارضية الصناعية الصغيرة' WHERE `whitebook_location`.`id` = 67;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'منطقة التخزين العارضية' WHERE `whitebook_location`.`id` = 68;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'الضجيج' WHERE `whitebook_location`.`id` = 69;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'المعارض - جنوب خيطان' WHERE `whitebook_location`.`id` = 70;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'الفروانية' WHERE `whitebook_location`.`id` = 71;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'فردوس' WHERE `whitebook_location`.`id` = 72;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'Ishbiliya' WHERE `whitebook_location`.`id` = 73;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'جليب الشيوخ' WHERE `whitebook_location`.`id` = 74;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'خيطان' WHERE `whitebook_location`.`id` = 75;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'العمرية' WHERE `whitebook_location`.`id` = 76;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'ربيعة' WHERE `whitebook_location`.`id` = 77;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'الراي الصناعية' WHERE `whitebook_location`.`id` = 78;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'الريغي' WHERE `whitebook_location`.`id` = 79;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'رحاب' WHERE `whitebook_location`.`id` = 80;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'صباح الناصر ' WHERE `whitebook_location`.`id` = 81;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'يذكر أن شادية' WHERE `whitebook_location`.`id` = 82;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'أبو Fatira' WHERE `whitebook_location`.`id` = 83;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'أبو الحصانية' WHERE `whitebook_location`.`id` = 84;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'عدن' WHERE `whitebook_location`.`id` = 85;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'علي صباح السالم' WHERE `whitebook_location`.`id` = 86;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'ساحل قطاع B' WHERE `whitebook_location`.`id` = 87;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'الفنطاس' WHERE `whitebook_location`.`id` = 88;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'الفنيطيس' WHERE `whitebook_location`.`id` = 89;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'المسيلة' WHERE `whitebook_location`.`id` = 90;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'مبارك الكبير ' WHERE `whitebook_location`.`id` = 91;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'القرين' WHERE `whitebook_location`.`id` = 92;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'القصور' WHERE `whitebook_location`.`id` = 93;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'صباح السالم' WHERE `whitebook_location`.`id` = 94;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'صبحان الصناعية' WHERE `whitebook_location`.`id` = 95;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'جنوب سطى' WHERE `whitebook_location`.`id` = 96;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'غرب أبو Fatira' WHERE `whitebook_location`.`id` = 97;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'وسطى' WHERE `whitebook_location`.`id` = 98;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'العبدلي' WHERE `whitebook_location`.`id` = 99;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'أمغرة' WHERE `whitebook_location`.`id` = 100;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'الجهراء' WHERE `whitebook_location`.`id` = 101;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'Mutla' WHERE `whitebook_location`.`id` = 102;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'نعيم' WHERE `whitebook_location`.`id` = 103;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'نسيم' WHERE `whitebook_location`.`id` = 104;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'العيون' WHERE `whitebook_location`.`id` = 105;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'القيروان - جنوب الدوحة' WHERE `whitebook_location`.`id` = 106;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'قصر' WHERE `whitebook_location`.`id` = 107;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'سعد العبد الله' WHERE `whitebook_location`.`id` = 108;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'الصبية' WHERE `whitebook_location`.`id` = 109;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'الشيخ سعد العبدالله مطار' WHERE `whitebook_location`.`id` = 110;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'الصليبية' WHERE `whitebook_location`.`id` = 111;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'مزارع الصليبية' WHERE `whitebook_location`.`id` = 112;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'الصليبية الصناعية' WHERE `whitebook_location`.`id` = 113;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'الواحة' WHERE `whitebook_location`.`id` = 114;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'أبو حليفة' WHERE `whitebook_location`.`id` = 115;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'الأحمدي' WHERE `whitebook_location`.`id` = 116;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'علي صباح السالم' WHERE `whitebook_location`.`id` = 117;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'ضاهر' WHERE `whitebook_location`.`id` = 118;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'الشرق الأحمدي' WHERE `whitebook_location`.`id` = 119;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'Egaila' WHERE `whitebook_location`.`id` = 120;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'فهد الأحمد' WHERE `whitebook_location`.`id` = 121;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'الفحيحيل' WHERE `whitebook_location`.`id` = 122;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'الفنطاس' WHERE `whitebook_location`.`id` = 123;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'الهدية' WHERE `whitebook_location`.`id` = 124;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'جابر العلي' WHERE `whitebook_location`.`id` = 125;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'الخيران' WHERE `whitebook_location`.`id` = 126;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'المقوع' WHERE `whitebook_location`.`id` = 127;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'المهبولة' WHERE `whitebook_location`.`id` = 128;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'Mangafox' WHERE `whitebook_location`.`id` = 129;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'مينا عبد الله' WHERE `whitebook_location`.`id` = 130;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'ميناء الأحمدي' WHERE `whitebook_location`.`id` = 131;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'الرقة' WHERE `whitebook_location`.`id` = 132;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'صباح الأحمد مدينة' WHERE `whitebook_location`.`id` = 133;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'الصباحية' WHERE `whitebook_location`.`id` = 134;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'ميناء الشعيبة' WHERE `whitebook_location`.`id` = 135;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'وفرة' WHERE `whitebook_location`.`id` = 136;")->execute();
        Yii::$app->db->createCommand("UPDATE `whitebook_location` SET `location_ar` = 'الشرق لل' WHERE `whitebook_location`.`id` = 138;")->execute();
    }

    public function down()
    {
        echo "m161108_055503_area_arabic_migration cannot be reverted.\n";

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
