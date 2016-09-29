<?php

use yii\db\Migration;
use common\models\Category;
use common\models\CategoryPath;

class m160929_080945_working_day_category extends Migration
{
    public function up()
    {
        $this->dropColumn('whitebook_vendor', 'working_days');
        $this->addColumn('whitebook_vendor', 'day_off', $this->string(100)->after('vendor_working_hours_to'));

        // MySQL Hierarchical Data Closure Table Pattern for category 

        $this->createTable('whitebook_category_path', [
            'id' => $this->primaryKey(),
            'category_id' => $this->integer(11),
            'path_id' => $this->integer(11),
            'level' => $this->integer(11)
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');
        
        //fill category path table 
        $categories = Category::find()
            ->orderBy('category_level ASC')
            ->all();

        foreach ($categories as $key => $value) {

            $level = 0;

            $paths = CategoryPath::find()
                        ->where(['category_id' => $value->parent_category_id])
                        ->orderBy('level ASC')
                        ->all();

            foreach ($paths as $path) {

                $cp = new CategoryPath();
                $cp->category_id = $value->category_id;
                $cp->level = $level;
                $cp->path_id = $path->path_id;
                $cp->save();

                $level++;
            }

            $cp = new CategoryPath();
            $cp->category_id = $value->category_id;
            $cp->path_id = $value->category_id;
            $cp->level = $level;
            $cp->save();
        }
    }

    public function down()
    {

    }
}
