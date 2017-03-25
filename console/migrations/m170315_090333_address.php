<?php

use yii\db\Migration;

class m170315_090333_address extends Migration
{
    public function up()
    {
        $this->dropTable('{{%customer_address_response}}');
        $this->dropTable('{{%address_question}}');
        
        $this->dropColumn('{{%customer_address}}', 'address_data');
            
        $this->addColumn('{{%customer_address}}', 'block', $this->string(100)->after('area_id'));
        $this->addColumn('{{%customer_address}}', 'street', $this->string(250)->after('block'));
        $this->addColumn('{{%customer_address}}', 'avenue', $this->string(250)->after('street'));
        $this->addColumn('{{%customer_address}}', 'building', $this->string(150)->after('avenue'));
        $this->addColumn('{{%customer_address}}', 'floor', $this->string(100)->after('building'));
        $this->addColumn('{{%customer_address}}', 'apartment', $this->string(100)->after('floor'));
        $this->addColumn('{{%customer_address}}', 'extra_details', $this->text()->after('apartment'));
        $this->addColumn('{{%customer_address}}', 'recipient_number', $this->string(100)->after('extra_details'));
        
        $this->alterColumn('{{%customer_address}}', 'created_by', $this->integer(11));
        $this->alterColumn('{{%customer_address}}', 'modified_by', $this->integer(11));
    }   
}
