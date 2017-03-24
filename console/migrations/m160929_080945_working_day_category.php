<?php

use yii\db\Migration;

class m160929_080945_working_day_category extends Migration
{
    public function up()
    {
        $this->dropColumn('{{%vendor}}', 'working_days');
        $this->addColumn('{{%vendor}}', 'day_off', $this->string(100)->after('vendor_working_hours_to'));

        // MySQL Hierarchical Data Closure Table Pattern for category 

        $this->createTable('{{%category_path}}', [
            'id' => $this->primaryKey(),
            'category_id' => $this->integer(11),
            'path_id' => $this->integer(11),
            'level' => $this->integer(11)
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');
        
        //fill category path table 
        
        $sql  = 'select * from {{%category}} ';
        $sql .= 'order by category_level ASC';

        $categories = Yii::$app->db->createCommand($sql)->queryAll();

        foreach ($categories as $key => $value) {

            $level = 0;

            $sql  = 'select * from {{%category_path}} ';
            $sql .= 'where category_id="'.$value['parent_category_id'].'" ';
            $sql .= 'order by level ASC';

            $paths = Yii::$app->db->createCommand($sql)->queryAll();

            foreach ($paths as $path) {

                $sql = 'insert into {{%category_path}} set category_id="'.$value['category_id'].'", level="'.$level.'", path_id="'.$path['path_id'].'"';

                Yii::$app->db->createCommand($sql)->execute();

                $level++;
            }

            $sql = 'insert into {{%category_path}} set category_id="'.$value['category_id'].'", level="'.$level.'", path_id="'.$value['category_id'].'"';

            Yii::$app->db->createCommand($sql)->execute();
        }
    }
}
