<?php

use yii\db\Migration;

class m161213_085747_package_description_db_length extends Migration
{
    public function up()
    {
        $this->alterColumn('whitebook_package', 'package_description_ar', $this->text());
    }
}
