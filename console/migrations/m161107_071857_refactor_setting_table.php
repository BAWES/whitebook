<?php

use yii\db\Migration;

class m161107_071857_refactor_setting_table extends Migration
{
    public function up()
    {
        $this->dropTable('whitebook_siteinfo'); 

        $this->createTable('whitebook_siteinfo', [
            'id' => $this->primaryKey(),
            'name' => $this->string(100),
            'value' => $this->text()
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');

        Yii::$app->db->createCommand()->batchInsert(
                'whitebook_siteinfo', 
                [
                    'name', 
                    'value'
                ], 
                [
                    [
                        'commission',
                        12.65
                    ],
                    [
                        'super_admin_role_id',
                        1
                    ],
                    [
                        'home_slider_alias',
                        'classic2'
                    ],
                    [
                        'site_location',
                        '85/B Cross Street, New York, USA NA1 42SL'
                    ],
                    [
                        'phone_numbe',
                        '+543 256 256'
                    ]
                ])
            ->execute();    
    }
}
