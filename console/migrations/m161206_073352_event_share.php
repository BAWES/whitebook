<?php

use yii\db\Migration;
use common\models\Events;

class m161206_073352_event_share extends Migration
{
    public function up()
    {
        $this->addColumn('whitebook_events', 'token', $this->string(100).' NULL AFTER no_of_guests');

        //generate token for previously added events 
        
        $sql = 'select * from {{%events}}';

        $events = Yii::$app->db->createCommand($sql)->queryAll();

        foreach ($events as $key => $value) 
        {
            $sql = 'update {{%events}} set token = "'.$this->generateToken().' where event_id="'.$value->event_id.'"';
            Yii::$app->db->createCommand($sql)->execute();
        }
    }
}
