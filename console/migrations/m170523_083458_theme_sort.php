<?php

use yii\db\Migration;

class m170523_083458_theme_sort extends Migration
{
    public function up()
    {
        $this->addColumn('{{%theme}}', 'sort', $this->integer(11)->after('theme_status')->notNull()->defaultValue(0));
    }
}
