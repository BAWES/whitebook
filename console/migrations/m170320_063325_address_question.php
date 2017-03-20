<?php

use yii\db\Migration;
use common\models\AddressQuestion;
use common\models\CustomerAddressResponse;

class m170320_063325_address_question extends Migration
{
    public function up()
    {
        $question = AddressQuestion::findOne(['question' => 'PACI No/Zip Code']);

        CustomerAddressResponse::deleteAll(['address_type_question_id' => $question->ques_id]);

        $question->delete();
    }
}
