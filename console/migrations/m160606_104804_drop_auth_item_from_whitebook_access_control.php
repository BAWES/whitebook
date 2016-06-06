<?php

use yii\db\Migration;

/**
 * Handles dropping auth_item from table `whitebook_access_control`.
 */
class m160606_104804_drop_auth_item_from_whitebook_access_control extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->dropColumn('whitebook_access_control', 'auth_item');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        echo "m160606_104804_drop_auth_item_from_whitebook_access_control cannot be reverted.\n";

        return false;
    }
}
