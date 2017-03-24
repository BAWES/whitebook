<?php

use yii\db\Migration;

class m170316_072553_address_questions extends Migration
{
    public function up()
    {
        $this->execute("SET foreign_key_checks = 0;");

        $this->createTable('{{%address_question}}', [
            'ques_id' => $this->primaryKey(11),
            'address_type_id' => $this->integer(11).' unsigned DEFAULT NULL',
            'question' => $this->string(128),
            'question_ar' => $this->string(255),
            'required' => $this->smallInteger(1),
            'sort' => $this->integer(11),
            'status' => "enum('Active','Deactive') NOT NULL DEFAULT 'Active'",
            'created_by' => $this->integer(11),
            'modified_by' => $this->integer(11),
            'created_datetime' => $this->datetime(),
            'modified_datetime' => $this->datetime(),
            'trash' => "enum('Default','Deleted') NOT NULL"
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');

        $this->addForeignKey ('address_question_type_fk', '{{%address_question}}', 'address_type_id', '{{%address_type}}', 'type_id', 'SET NULL' , 'SET NULL');

        $this->createTable('{{%customer_address_response}}', [
            'response_id' => $this->primaryKey(11),
            'address_id' => $this->integer(11). ' UNSIGNED NULL',
            'address_type_question_id' => $this->integer(11),
            'response_text' => $this->string(250)
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');
    
        $this->addForeignKey ('customer_address_r_a_fk', '{{%customer_address_response}}', 'address_id', '{{%customer_address}}', 'address_id', 'SET NULL' , 'SET NULL');

        $this->addForeignKey ('customer_address_r_q_fk', '{{%customer_address_response}}', 'address_type_question_id', '{{%address_question}}', 'ques_id', 'SET NULL' , 'SET NULL');

        // address types 
        
        $this->truncateTable('{{%address_type}}');

        $this->batchInsert('{{%address_type}}', 
            ['type_name', 'type_name_ar', 'status', 'trash'],
            [
                ['House', 'منزل', 'Active', 'Default'],
                ['Apartment', 'شقة', 'Active', 'Default'],
                ['Office', 'مكتب. مقر. مركز', 'Active', 'Default'],
                ['Hospital', 'مستشفى', 'Active', 'Default']
            ]
        );

        //address question 

        $this->truncateTable('{{%address_question}}');

        $this->batchInsert('{{%address_question}}', 
            ['address_type_id', 'question', 'question_ar', 'required', 'status', 'trash'],
            [
                //house 
                ['1', 'Block', 'منع', 0, 'Active', 'Default'],
                ['1', 'Street', 'شارع', 0, 'Active', 'Default'],
                ['1', 'Jaddah', 'جده', 0, 'Active', 'Default'],
                ['1', 'Building / House No', 'بناء / بيت لا', 1, 'Active', 'Default'],
                ['1', 'PACI No/Zip Code', 'PACI رقم / الرمز البريدي', 0, 'Active', 'Default'],

                //Apartment
                ['2', 'Block', 'منع', 0, 'Active', 'Default'],
                ['2', 'Street', 'شارع', 0, 'Active', 'Default'],
                ['2', 'Jaddah', 'جده', 0, 'Active', 'Default'],
                ['2', 'Building / House No', 'بناء / بيت لا', 1, 'Active', 'Default'],
                ['2', 'Floor', 'أرضية', 0, 'Active', 'Default'],
                ['2', 'Apartment No', 'شقة لا', 1, 'Active', 'Default'],

                //Office
                ['3', 'Block', 'منع', 0, 'Active', 'Default'],
                ['3', 'Street', 'شارع', 0, 'Active', 'Default'],
                ['3', 'Jaddah', 'جده', 0, 'Active', 'Default'],
                ['3', 'Building', 'بناء', 1, 'Active', 'Default'],
                ['3', 'Floor', 'أرضية', 0, 'Active', 'Default'],
                ['3', 'Office Number', 'رقم المكتب', 0, 'Active', 'Default'],
                ['3', 'Company', 'شركة', 0, 'Active', 'Default'],

                //Hospital
                ['4', 'Hospital Name', 'اسم المستشفى', 1, 'Active', 'Default'],
                ['4', 'Floor', 'أرضية', 1, 'Active', 'Default'],
                ['4', 'Room Number', 'رقم الغرفة', 0, 'Active', 'Default'],
                ['4', 'Ward/Wing', 'جناح / الجناح', 0, 'Active', 'Default'],
            ]
        );

        $this->addColumn('{{%customer_address}}', 'address_data', $this->text());
            
        $this->dropColumn('{{%customer_address}}', 'block');
        $this->dropColumn('{{%customer_address}}', 'street');
        $this->dropColumn('{{%customer_address}}', 'avenue');
        $this->dropColumn('{{%customer_address}}', 'building');
        $this->dropColumn('{{%customer_address}}', 'floor');
        $this->dropColumn('{{%customer_address}}', 'apartment');
        $this->dropColumn('{{%customer_address}}', 'extra_details');
        $this->dropColumn('{{%customer_address}}', 'recipient_number');   

        $this->execute("SET foreign_key_checks = 1;");  
    }

    public function down()
    {
        
    }
}
