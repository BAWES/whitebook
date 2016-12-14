<?php

use yii\db\Migration;

class m161213_054312_package_arabic extends Migration
{
    public function up()
    {
       	$this->addColumn('whitebook_package', 'package_name_ar', $this->string(100)->after('package_name'));
        $this->addColumn('whitebook_package', 'package_description_ar', $this->string(100)->after('package_description'));
        $this->addColumn('whitebook_package', 'package_slug', $this->string(255));
        $this->addColumn('whitebook_package', 'status', $this->smallInteger(1));
    }
}
