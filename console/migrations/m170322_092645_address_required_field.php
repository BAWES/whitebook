<?php

use yii\db\Migration;
use common\models\AddressQuestion;

class m170322_092645_address_required_field extends Migration
{
    public function up()
    {
        // Mandatory fields for House 

        AddressQuestion::updateAll(['required' => 1], [
                'question' => 'Block', 
                'address_type_id' => 1
            ]);

        AddressQuestion::updateAll(['required' => 1], [
                'question' => 'Street', 
                'address_type_id' => 1
            ]);

        AddressQuestion::updateAll(['required' => 1], [
                'question' => 'Building', 
                'address_type_id' => 1
            ]);

        //Mandatory fields for Apartment

        AddressQuestion::updateAll(['required' => 1], [
                'question' => 'Block', 
                'address_type_id' => 2
            ]);

        AddressQuestion::updateAll(['required' => 1], [
                'question' => 'Street', 
                'address_type_id' => 2
            ]);

        AddressQuestion::updateAll(['required' => 1], [
                'question' => 'Building / House No', 
                'address_type_id' => 2
            ]);

        AddressQuestion::updateAll(['required' => 1], [
                'question' => 'Floor', 
                'address_type_id' => 2
            ]);

        AddressQuestion::updateAll(['required' => 1], [
                'question' => 'Apartment No', 
                'address_type_id' => 2
            ]);
        
        // Mandatory fields for Office
        
        AddressQuestion::updateAll(['required' => 1], [
                'question' => 'Block', 
                'address_type_id' => 3
            ]);

        AddressQuestion::updateAll(['required' => 1], [
                'question' => 'Street', 
                'address_type_id' => 3
            ]);

        AddressQuestion::updateAll(['required' => 1], [
                'question' => 'Building', 
                'address_type_id' => 3
            ]);

        AddressQuestion::updateAll(['required' => 1], [
                'question' => 'Floor', 
                'address_type_id' => 3
            ]);

        AddressQuestion::updateAll(['required' => 1], [
                'question' => 'Office Number', 
                'address_type_id' => 3
            ]);
        
        AddressQuestion::updateAll(['required' => 1], [
                'question' => 'Company', 
                'address_type_id' => 3
            ]);
        
        // Mandatory fields for Hospital:
        
        AddressQuestion::updateAll(['required' => 1], [
                'question' => 'Hospital Name', 
                'address_type_id' => 4
            ]);        
        
        AddressQuestion::updateAll(['required' => 1], [
                'question' => 'Floor', 
                'address_type_id' => 4
            ]);
        
        AddressQuestion::updateAll(['required' => 1], [
                'question' => 'Ward/Wing', 
                'address_type_id' => 4
            ]);
        
        AddressQuestion::updateAll(['required' => 1], [
                'question' => 'Room Number', 
                'address_type_id' => 4
            ]);
    }

    public function down()
    {
        echo "m170322_092645_address_required_field cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
