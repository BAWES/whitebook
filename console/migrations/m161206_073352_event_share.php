<?php

use yii\db\Migration;
use common\models\Events;

class m161206_073352_event_share extends Migration
{
    public function up()
    {
        $this->addColumn('whitebook_events', 'token', $this->string(100).' NULL AFTER no_of_guests');

        //generate token for previously added events 
        $events = Events::find()->all();

        foreach ($events as $key => $value) 
        {
            $value->token = Events::generateToken();
            $value->save();
        }
    }
}
