<?php

use yii\db\Migration;

/**
 * Handles the dropping for table `whitebook_session_mgmt`.
 */
class m161019_114409_drop_whitebook_session_mgmt_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->dropTable('whitebook_session_mgmt');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->createTable('whitebook_session_mgmt', [
            'id' => $this->primaryKey(),
        ]);
    }
}
