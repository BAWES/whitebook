<?php

use yii\db\Migration;

class m170322_092645_address_required_field extends Migration
{
    public function up()
    {
        // Mandatory fields for House 

        $sql  = 'update {{%address_question}} set required=1 where ';
        $sql .= 'question = "Block" AND '; 
        $sql .= 'address_type_id = 1';

        Yii::$app->db->createCommand($sql);

        $sql  = 'update {{%address_question}} set required=1 where ';
        $sql .= 'question = "Street" AND '; 
        $sql .= 'address_type_id = 1';

        Yii::$app->db->createCommand($sql);

        $sql  = 'update {{%address_question}} set required=1 where ';
        $sql .= 'question = "Building" AND '; 
        $sql .= 'address_type_id = 1';

        Yii::$app->db->createCommand($sql);

        //Mandatory fields for Apartment

        $sql  = 'update {{%address_question}} set required=1 where ';
        $sql .= 'question = "Block" AND '; 
        $sql .= 'address_type_id = 2';

        Yii::$app->db->createCommand($sql);

        $sql  = 'update {{%address_question}} set required=1 where ';
        $sql .= 'question = "Street" AND '; 
        $sql .= 'address_type_id = 2';

        Yii::$app->db->createCommand($sql);

        $sql  = 'update {{%address_question}} set required=1 where ';
        $sql .= 'question = "Building / House No" AND '; 
        $sql .= 'address_type_id = 2';

        Yii::$app->db->createCommand($sql);

        $sql  = 'update {{%address_question}} set required=1 where ';
        $sql .= 'question = "Floor" AND '; 
        $sql .= 'address_type_id = 2';

        Yii::$app->db->createCommand($sql);

        $sql  = 'update {{%address_question}} set required=1 where ';
        $sql .= 'question = "Apartment No" AND '; 
        $sql .= 'address_type_id = 2';

        Yii::$app->db->createCommand($sql);
        
        // Mandatory fields for Office
        
        $sql  = 'update {{%address_question}} set required=1 where ';
        $sql .= 'question = "Block" AND '; 
        $sql .= 'address_type_id = 3';

        Yii::$app->db->createCommand($sql);

        $sql  = 'update {{%address_question}} set required=1 where ';
        $sql .= 'question = "Street" AND '; 
        $sql .= 'address_type_id = 3';

        Yii::$app->db->createCommand($sql);

        $sql  = 'update {{%address_question}} set required=1 where ';
        $sql .= 'question = "Building" AND '; 
        $sql .= 'address_type_id = 3';

        Yii::$app->db->createCommand($sql);

        $sql  = 'update {{%address_question}} set required=1 where ';
        $sql .= 'question = "Floor" AND '; 
        $sql .= 'address_type_id = 3';

        Yii::$app->db->createCommand($sql);
        
        $sql  = 'update {{%address_question}} set required=1 where ';
        $sql .= 'question = "Office Number" AND '; 
        $sql .= 'address_type_id = 3';

        Yii::$app->db->createCommand($sql);

        $sql  = 'update {{%address_question}} set required=1 where ';
        $sql .= 'question = "Company" AND '; 
        $sql .= 'address_type_id = 3';

        Yii::$app->db->createCommand($sql);

        // Mandatory fields for Hospital:
        
        $sql  = 'update {{%address_question}} set required=1 where ';
        $sql .= 'question = "Hospital Name" AND '; 
        $sql .= 'address_type_id = 4';

        Yii::$app->db->createCommand($sql);

        $sql  = 'update {{%address_question}} set required=1 where ';
        $sql .= 'question = "Floor" AND '; 
        $sql .= 'address_type_id = 4';

        Yii::$app->db->createCommand($sql);

        $sql  = 'update {{%address_question}} set required=1 where ';
        $sql .= 'question = "Ward/Wing" AND '; 
        $sql .= 'address_type_id = 4';

        Yii::$app->db->createCommand($sql);

        $sql  = 'update {{%address_question}} set required=1 where ';
        $sql .= 'question = "Room Number" AND '; 
        $sql .= 'address_type_id = 4';

        Yii::$app->db->createCommand($sql);
    }
}
