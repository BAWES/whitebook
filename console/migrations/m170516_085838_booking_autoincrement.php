<?php

use yii\db\Migration;

class m170516_085838_booking_autoincrement extends Migration
{
    public function up()
    {
        Yii::$app->db->createCommand('ALTER TABLE {{%booking}} AUTO_INCREMENT = 1100')->execute();
    }
}
