<?php

use yii\db\Migration;
use common\models\Siteinfo;

class m161028_072258_super_admin_permission extends Migration
{
    public function up()
    {
        $this->addColumn('whitebook_siteinfo', 'super_admin_role_id', $this->integer(11)->defaultValue(1)->after('commision'));
    }
}
