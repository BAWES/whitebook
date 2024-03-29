<?php

use yii\db\Migration;

class m170306_095115_cart_update_for_session_id extends Migration
{
    public function up()
    {
        $this->addColumn('{{%customer_cart}}','cart_session_id',$this->string(100)->after('cart_valid')->null());
        $this->alterColumn('{{%customer_cart}}','created_by',$this->integer(11)->null());
        $this->alterColumn('{{%customer_cart}}','modified_by',$this->integer(11)->null());
    }

    public function down()
    {
        $this->dropColumn('{{%customer_cart}}','cart_session_id');
    }
}
