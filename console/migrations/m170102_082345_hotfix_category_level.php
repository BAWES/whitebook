<?php

use yii\db\Migration;
use common\models\Category;
use common\models\CategoryPath;

class m170102_082345_hotfix_category_level extends Migration
{
    public function up()
    {
        //update category_level 
        $categories = Category::find()->all();

        foreach ($categories as $key => $value) {
            
            //find level 
            $path = CategoryPath::find()
                ->where([
                    'category_id' => $value->category_id, 
                    'path_id' => $value->category_id
                ])
                ->one();

            if($path) 
            {
                $level = $path->level;
            }
            else
            {
                $level = 0;
            }

            $value->category_level = $level;
            $value->save();
        }
    }

    public function down()
    {
        echo "m170102_082345_hotfix_category_level cannot be reverted.\n";

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
