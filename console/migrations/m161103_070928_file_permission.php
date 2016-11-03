<?php

use yii\db\Migration;

class m161103_070928_file_permission extends Migration
{
    public function up()
    {
        $old = umask(0);
        chmod(Yii::getAlias('@temp_folder'), 0777);
        umask($old);
    }
}
