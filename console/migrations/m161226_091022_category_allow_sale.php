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

        $sql = 'select * from {{%category}}';

        $categories = Yii::$app->db->createCommand($sql)->queryAll();

        foreach ($categories as $key => $value) {
            
            //find level 
            
            $sql  = 'select * from {{%category_path}} where ';
            $sql .= 'category_id="'.$value['category_id'].'" AND ';
            $sql .= 'path_id="'.$value['category_id'].'"';

            $path = Yii::$app->db->createCommand($sql)->queryOne();

            if($path) 
            {
                $level = $path['level'] + 1;
            }
            else
            {
                $level = 0;
            }

            //save level 

            $sql = 'update {{%category}} set category_level="'.$level.'" where category_id="'.$value['category_id'].'"';

            Yii::$app->db->createCommand($sql)->execute();
        }
    }
}
