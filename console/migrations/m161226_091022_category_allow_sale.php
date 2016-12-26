<?php

use yii\db\Migration;
use common\models\Category;
use common\models\CategoryPath;

class m161226_091022_category_allow_sale extends Migration
{
    public function up()
    {
        $this->dropColumn('whitebook_category', 'category_allow_sale');

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
                $level = $path->level + 1;
            }
            else
            {
                $level = 0;
            }

            $value->category_level = $level;
            $value->save();
        }
    }
}
