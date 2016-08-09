<?php

use yii\db\Migration;

class m160808_101935_faq_grouping extends Migration
{
    public function up()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
          $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('whitebook_faq_group', [
            'faq_group_id' => $this->primaryKey(),
            'group_name' => $this->string()->notNull(),
            'group_name_ar' => $this->string()->notNull(),
            'sort_order' => $this->integer()
        ], $tableOptions);

        $this->insert('whitebook_faq_group', [
            'group_name' => 'About Us',
            'group_name_ar' => 'معلومات عنا',
            'sort_order' => '0'
        ]);

        $this->insert('whitebook_faq_group', [
            'group_name' => 'My White Book',
            'group_name_ar' => 'بلدي الكتاب الأبيض',
            'sort_order' => '0'
        ]);

        $this->insert('whitebook_faq_group', [
            'group_name' => 'Membership',
            'group_name_ar' => 'عضوية',
            'sort_order' => '0'
        ]);

        $this->insert('whitebook_faq_group', [
            'group_name' => 'Vendor',
            'group_name_ar' => 'بائع',
            'sort_order' => '0'
        ]);

        $this->insert('whitebook_faq_group', [
            'group_name' => 'Orders',
            'group_name_ar' => 'أوامر',
            'sort_order' => '0'
        ]);

        $this->insert('whitebook_faq_group', [
            'group_name' => 'Payment',
            'group_name_ar' => 'دفع',
            'sort_order' => '0'
        ]);

        $this->insert('whitebook_faq_group', [
            'group_name' => 'Delivery',
            'group_name_ar' => 'خدمه توصيل',
            'sort_order' => '0'
        ]);

        $this->addColumn(
            'whitebook_faq',
            'faq_group_id',
            $this->integer()->after('faq_id')
        );

        $this->truncateTable('whitebook_faq');

        $this->insert('whitebook_faq', [
            'faq_group_id' => '1',
            'question' => 'What is The White Book?',
            'question_ar' => 'ما هو الكتاب الأبيض؟',
            'answer' => 'It\’s the only online platform that assists users in planning their events.',
            'answer_ar' => 'انها المنصة الوحيدة على الانترنت التي تساعد المستخدمين في التخطيط المناسبات الخاصة بهم .',
            'faq_status' => 'Active',
            'sort' => '0',
            'created_by' => '0',
            'modified_by' => '0',
            'created_datetime' => date('Y-d-m h:i:s'),
            'modified_datetime' => date('Y-d-m h:i:s'),
            'trash' => 'Default'
        ]);

        $this->insert('whitebook_faq', [
            'faq_group_id' => '1',
            'question' => 'What does it take to plan an event?',
            'question_ar' => 'ما الذي يتطلبه الأمر ل وضع الخطة المناسبة ؟',
            'answer' => 'For every event, you need to find a venue, print invitations, design decor, purchase supplies, choose your food and beverage, entertain your guests, hire service providers, and at the end say “Thank You” to your guests for sharing your occasion.',
            'answer_ar' => 'لكل حدث ، تحتاج إلى العثور على مكان ، دعوات طباعة ، تصميم ديكور ، شراء اللوازم ، اختيار المواد الغذائية و المشروبات الخاصة بك ، ترفيه عن الضيوف الخاص بك ، واستئجار مقدمي الخدمات، و في نهاية يقول " شكرا " لضيوفك ل تقاسم المناسبة الخاصة بك .',
            'faq_status' => 'Active',
            'sort' => '0',
            'created_by' => '0',
            'modified_by' => '0',
            'created_datetime' => date('Y-d-m h:i:s'),
            'modified_datetime' => date('Y-d-m h:i:s'),
            'trash' => 'Default'
        ]);

        $this->insert('whitebook_faq', [
            'faq_group_id' => '1',
            'question' => 'What\'s Plan, Shop, and Experience?',
            'question_ar' => 'ما \'ق خطة للتسوق و الخبرة ؟',
            'answer' => 'Plan is where you browse, get ideas, and plan your event. Shop is where you purchase,
customise, and schedule delivery of your products and services. Experience is a list of value
added services provided by The White Book’s Team.',
            'answer_ar' => 'الخطة حيث يمكنك تصفح و الحصول على أفكار ، و تخطيط هذا الحدث الخاص بك . المحل هو حيث قمت بشراء ،
تخصيص ، و تسليم الجدول الزمني من المنتجات والخدمات الخاصة بك . الخبرة هي قائمة من قيمة
الخدمات ذات القيمة المضافة التي يقدمها فريق الكتاب الأبيض .',
            'faq_status' => 'Active',
            'sort' => '0',
            'created_by' => '0',
            'modified_by' => '0',
            'created_datetime' => date('Y-d-m h:i:s'),
            'modified_datetime' => date('Y-d-m h:i:s'),
            'trash' => 'Default'
        ]);

        $this->insert('whitebook_faq', [
            'faq_group_id' => '1',
            'question' => 'What\'s your contact details?',
            'question_ar' => 'ما \'ق تفاصيل الاتصال الخاصة بك ؟',
            'answer' => 'blabla',
            'answer_ar' => 'blabla',
            'faq_status' => 'Active',
            'sort' => '0',
            'created_by' => '0',
            'modified_by' => '0',
            'created_datetime' => date('Y-d-m h:i:s'),
            'modified_datetime' => date('Y-d-m h:i:s'),
            'trash' => 'Default'
        ]);

        $this->insert('whitebook_faq', [
            'faq_group_id' => '1',
            'question' => 'Suggestions and Complaints',
            'question_ar' => 'الاقتراحات و الشكاوى',
            'answer' => 'bablabla email us at another blabla',
            'answer_ar' => 'bablabla مراسلتنا على blabla ل آخر',
            'faq_status' => 'Active',
            'sort' => '0',
            'created_by' => '0',
            'modified_by' => '0',
            'created_datetime' => date('Y-d-m h:i:s'),
            'modified_datetime' => date('Y-d-m h:i:s'),
            'trash' => 'Default'
        ]);

        $this->insert('whitebook_faq', [
            'faq_group_id' => '2',
            'question' => 'What is “My White Book”?',
            'question_ar' => 'ما هو " بلدي الكتاب الأبيض " ؟',
            'answer' => 'A virtual book where users create a page for every event. Users will populate these pages with categorised items available from our wide variety of vendors. Users then know what is missing from their event and are able to search for the missing parts.',
            'answer_ar' => 'كتاب الظاهري حيث للمستخدمين إنشاء صفحة لكل حدث . وسيكون للمستخدمين ملء هذه الصفحات مع العناصر تصنيفها المتاحة من دينا مجموعة واسعة من البائعين. المستخدمين ثم تعرف ما هو مفقود من الحدث ، و تكون قادرة على البحث عن الأجزاء المفقودة .',
            'faq_status' => 'Active',
            'sort' => '0',
            'created_by' => '0',
            'modified_by' => '0',
            'created_datetime' => date('Y-d-m h:i:s'),
            'modified_datetime' => date('Y-d-m h:i:s'),
            'trash' => 'Default'
        ]);

        $this->insert('whitebook_faq', [
            'faq_group_id' => '2',
            'question' => 'How does it assist users in planning their events?',
            'question_ar' => 'كيف تساعد المستخدمين في التخطيط لأحداث بهم؟',
            'answer' => 'It organises your thoughts and simplifies the process of planning your event. How do we do that? Through “My White Book”.',
            'answer_ar' => 'وتنظم أفكارك و يبسط عملية التخطيط الحدث . كيف نفعل ذلك؟ من خلال " بلدي الكتاب الأبيض " .',
            'faq_status' => 'Active',
            'sort' => '0',
            'created_by' => '0',
            'modified_by' => '0',
            'created_datetime' => date('Y-d-m h:i:s'),
            'modified_datetime' => date('Y-d-m h:i:s'),
            'trash' => 'Default'
        ]);

        $this->insert('whitebook_faq', [
            'faq_group_id' => '2',
            'question' => 'Do I need to create an account to have a White Book?',
            'question_ar' => 'هل أنا بحاجة لإنشاء حساب ل ديك الكتاب الأبيض ؟',
            'answer' => 'Yes.',
            'answer_ar' => 'نعم فعلا',
            'faq_status' => 'Active',
            'sort' => '0',
            'created_by' => '0',
            'modified_by' => '0',
            'created_datetime' => date('Y-d-m h:i:s'),
            'modified_datetime' => date('Y-d-m h:i:s'),
            'trash' => 'Default'
        ]);

        $this->insert('whitebook_faq', [
            'faq_group_id' => '2',
            'question' => 'Can I Save Items for later?',
            'question_ar' => 'هل يمكنني حفظ العناصر في وقت لاحق ؟',
            'answer' => 'Things I like.',
            'answer_ar' => 'أشياء أحبها.',
            'faq_status' => 'Active',
            'sort' => '0',
            'created_by' => '0',
            'modified_by' => '0',
            'created_datetime' => date('Y-d-m h:i:s'),
            'modified_datetime' => date('Y-d-m h:i:s'),
            'trash' => 'Default'
        ]);

        $this->insert('whitebook_faq', [
            'faq_group_id' => '3',
            'question' => 'Why should I create an account?',
            'question_ar' => 'لماذا يجب أن أقوم بإنشاء حساب؟',
            'answer' => 'By creating an account you receive your own Whitebook which will enable you to add items to “Things I Like”, and create Event pages to collect items as well as creating your guest list.',
            'answer_ar' => 'عن طريق إنشاء حساب تتلقى Whitebook الخاصة بك والتي سوف تمكنك من إضافة عناصر إلى " أشياء أنا أحب " ، و إنشاء صفحات حدث لجمع البنود، وكذلك خلق قائمة الضيوف .',
            'faq_status' => 'Active',
            'sort' => '0',
            'created_by' => '0',
            'modified_by' => '0',
            'created_datetime' => date('Y-d-m h:i:s'),
            'modified_datetime' => date('Y-d-m h:i:s'),
            'trash' => 'Default'
        ]);

        $this->insert('whitebook_faq', [
            'faq_group_id' => '3',
            'question' => 'How do I become a member?',
            'question_ar' => 'كيف تصبح عضوا ؟',
            'answer' => 'blabla',
            'answer_ar' => 'blabla',
            'faq_status' => 'Active',
            'sort' => '0',
            'created_by' => '0',
            'modified_by' => '0',
            'created_datetime' => date('Y-d-m h:i:s'),
            'modified_datetime' => date('Y-d-m h:i:s'),
            'trash' => 'Default'
        ]);

        $this->insert('whitebook_faq', [
            'faq_group_id' => '3',
            'question' => 'I have forgotten my password.',
            'question_ar' => 'نسيت كلمة المرور الخاصة بي.',
            'answer' => 'babl',
            'answer_ar' => 'انها المنصة الوحيدة على الانترنت التي تساعد المستخدمين في التخطيط المناسبات الخاصة بهم .',
            'faq_status' => 'Active',
            'sort' => '0',
            'created_by' => '0',
            'modified_by' => '0',
            'created_datetime' => date('Y-d-m h:i:s'),
            'modified_datetime' => date('Y-d-m h:i:s'),
            'trash' => 'Default'
        ]);

        $this->insert('whitebook_faq', [
            'faq_group_id' => '3',
            'question' => 'How do I change my account settings?',
            'question_ar' => 'كيف يمكنني تغيير إعدادات الحساب الخاص بي؟',
            'answer' => 'wadaw',
            'answer_ar' => 'انها المنصة الوحيدة على الانترنت التي تساعد المستخدمين في التخطيط المناسبات الخاصة بهم .',
            'faq_status' => 'Active',
            'sort' => '0',
            'created_by' => '0',
            'modified_by' => '0',
            'created_datetime' => date('Y-d-m h:i:s'),
            'modified_datetime' => date('Y-d-m h:i:s'),
            'trash' => 'Default'
        ]);

        $this->insert('whitebook_faq', [
            'faq_group_id' => '4',
            'question' => 'How can I become a vendor?',
            'question_ar' => 'كيف يمكنني أن أصبح بائع ؟',
            'answer' => 'Contact us at blabla@blala.com or on +965 xxxxxxxx and we’ll get in touch with additional
details.',
            'answer_ar' => 'اتصل بنا على blabla@blala.com أو على +965 XXXXXXXX ونحن سوف تحصل على اتصال مع إضافي
تفاصيل.',
            'faq_status' => 'Active',
            'sort' => '0',
            'created_by' => '0',
            'modified_by' => '0',
            'created_datetime' => date('Y-d-m h:i:s'),
            'modified_datetime' => date('Y-d-m h:i:s'),
            'trash' => 'Default'
        ]);

        $this->insert('whitebook_faq', [
            'faq_group_id' => '4',
            'question' => 'Do I qualify to become a vendor?',
            'question_ar' => 'هل أنا مؤهل ليصبح بائع ؟',
            'answer' => 'Our checklist / process / reqs',
            'answer_ar' => 'لدينا مرجعية / عملية / طلب',
            'faq_status' => 'Active',
            'sort' => '0',
            'created_by' => '0',
            'modified_by' => '0',
            'created_datetime' => date('Y-d-m h:i:s'),
            'modified_datetime' => date('Y-d-m h:i:s'),
            'trash' => 'Default'
        ]);

        $this->insert('whitebook_faq', [
            'faq_group_id' => '5',
            'question' => 'Can I order By Telephone?',
            'question_ar' => 'يمكنني طلب عن طريق الهاتف ؟',
            'answer' => 'kk',
            'answer_ar' => 'انها المنصة الوحيدة على الانترنت التي تساعد المستخدمين في التخطيط المناسبات الخاصة بهم .',
            'faq_status' => 'Active',
            'sort' => '0',
            'created_by' => '0',
            'modified_by' => '0',
            'created_datetime' => date('Y-d-m h:i:s'),
            'modified_datetime' => date('Y-d-m h:i:s'),
            'trash' => 'Default'
        ]);

        $this->insert('whitebook_faq', [
            'faq_group_id' => '5',
            'question' => 'Can I cancel or change my order once it is placed?',
            'question_ar' => 'هل أستطيع إلغاء أو تغيير طلبي مرة واحدة يتم وضعها ؟',
            'answer' => 'dwa',
            'answer_ar' => 'dwa',
            'faq_status' => 'Active',
            'sort' => '0',
            'created_by' => '0',
            'modified_by' => '0',
            'created_datetime' => date('Y-d-m h:i:s'),
            'modified_datetime' => date('Y-d-m h:i:s'),
            'trash' => 'Default'
        ]);

        $this->insert('whitebook_faq', [
            'faq_group_id' => '5',
            'question' => 'What is the Return Policy on my orders?',
            'question_ar' => 'ما هي سياسة عائد على أوامري؟',
            'answer' => 'The return policy is different for each vendor. You can find each vendor’s return policy on their profile page under “Directory”.',
            'answer_ar' => 'عودة السياسة تختلف عن كل بائع . يمكنك العثور على عودة السياسة كل بائع على صفحة ملفهم الشخصي، تحت عنوان " دليل " .',
            'faq_status' => 'Active',
            'sort' => '0',
            'created_by' => '0',
            'modified_by' => '0',
            'created_datetime' => date('Y-d-m h:i:s'),
            'modified_datetime' => date('Y-d-m h:i:s'),
            'trash' => 'Default'
        ]);

        $this->insert('whitebook_faq', [
            'faq_group_id' => '5',
            'question' => 'What happens with Faulty Or Damaged items?',
            'question_ar' => 'ما يحدث مع البنود المعيبة أو التالفة ؟',
            'answer' => 'h.',
            'answer_ar' => 'h.',
            'faq_status' => 'Active',
            'sort' => '0',
            'created_by' => '0',
            'modified_by' => '0',
            'created_datetime' => date('Y-d-m h:i:s'),
            'modified_datetime' => date('Y-d-m h:i:s'),
            'trash' => 'Default'
        ]);

        $this->insert('whitebook_faq', [
            'faq_group_id' => '6',
            'question' => 'Which Payment Methods Do you accept?',
            'question_ar' => 'الذي طرق الدفع هل تقبل؟',
            'answer' => 'sq',
            'answer_ar' => 'sq.',
            'faq_status' => 'Active',
            'sort' => '0',
            'created_by' => '0',
            'modified_by' => '0',
            'created_datetime' => date('Y-d-m h:i:s'),
            'modified_datetime' => date('Y-d-m h:i:s'),
            'trash' => 'Default'
        ]);

        $this->insert('whitebook_faq', [
            'faq_group_id' => '6',
            'question' => 'Is shopping secure?',
            'question_ar' => 'و تسوق آمن ؟',
            'answer' => 'jkml.',
            'answer_ar' => 'jkml.',
            'faq_status' => 'Active',
            'sort' => '0',
            'created_by' => '0',
            'modified_by' => '0',
            'created_datetime' => date('Y-d-m h:i:s'),
            'modified_datetime' => date('Y-d-m h:i:s'),
            'trash' => 'Default'
        ]);

        $this->insert('whitebook_faq', [
            'faq_group_id' => '6',
            'question' => 'When will I be charged?',
            'question_ar' => 'عندما ستتم محاسبتي ؟',
            'answer' => 'dwa.',
            'answer_ar' => 'dwa.',
            'faq_status' => 'Active',
            'sort' => '0',
            'created_by' => '0',
            'modified_by' => '0',
            'created_datetime' => date('Y-d-m h:i:s'),
            'modified_datetime' => date('Y-d-m h:i:s'),
            'trash' => 'Default'
        ]);

        $this->insert('whitebook_faq', [
            'faq_group_id' => '7',
            'question' => 'How much does delivery cost?',
            'question_ar' => 'وكم تكلفة التوصيل ؟',
            'answer' => 'dwa.',
            'answer_ar' => 'dwa.',
            'faq_status' => 'Active',
            'sort' => '0',
            'created_by' => '0',
            'modified_by' => '0',
            'created_datetime' => date('Y-d-m h:i:s'),
            'modified_datetime' => date('Y-d-m h:i:s'),
            'trash' => 'Default'
        ]);

        $this->insert('whitebook_faq', [
            'faq_group_id' => '7',
            'question' => 'I need something urgently. Can you help me?',
            'question_ar' => 'أنا بحاجة الى شيء على وجه السرعة. هل بإمكانك مساعدتي؟',
            'answer' => 'dwa.',
            'answer_ar' => 'dwa.',
            'faq_status' => 'Active',
            'sort' => '0',
            'created_by' => '0',
            'modified_by' => '0',
            'created_datetime' => date('Y-d-m h:i:s'),
            'modified_datetime' => date('Y-d-m h:i:s'),
            'trash' => 'Default'
        ]);

        $this->insert('whitebook_faq', [
            'faq_group_id' => '7',
            'question' => 'Part of my order is missing?',
            'question_ar' => 'جزء من طلبي مفقود ؟',
            'answer' => 'dwa.',
            'answer_ar' => 'dwa.',
            'faq_status' => 'Active',
            'sort' => '0',
            'created_by' => '0',
            'modified_by' => '0',
            'created_datetime' => date('Y-d-m h:i:s'),
            'modified_datetime' => date('Y-d-m h:i:s'),
            'trash' => 'Default'
        ]);

        $this->insert('whitebook_faq', [
            'faq_group_id' => '7',
            'question' => 'What if I have received an incorrect item ?',
            'question_ar' => 'ما إذا كنت قد تلقيت البند غير صحيحة ؟',
            'answer' => 'dwa.',
            'answer_ar' => 'dwa.',
            'faq_status' => 'Active',
            'sort' => '0',
            'created_by' => '0',
            'modified_by' => '0',
            'created_datetime' => date('Y-d-m h:i:s'),
            'modified_datetime' => date('Y-d-m h:i:s'),
            'trash' => 'Default'
        ]);

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
