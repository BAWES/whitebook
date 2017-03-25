<?php

use yii\db\Migration;

class m170320_063325_address_question extends Migration
{
    public function up()
    {
    	//remove response for `PACI No/Zip Code` from address question response 

    	$sql = 'select * from {{%address_question}} where question="PACI No/Zip Code"';

    	$question = Yii::$app->db->createCommand($sql)->queryOne();

        if($question['ques_id'])
        {
            $sql = 'delete from {{%customer_address_response}} where address_type_question_id="'.$question['ques_id'].'"';

            Yii::$app->db->createCommand($sql)->execute();    
        }
        
		//remove `PACI No/Zip Code` from address question 

		$sql = 'delete from {{%address_question}} where question="PACI No/Zip Code"';

		Yii::$app->db->createCommand($sql)->execute();
    }
}
