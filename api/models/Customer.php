<?php

namespace api\models;

use common\models\CustomerToken;
use Yii;

class Customer extends \common\models\Customer
{
    /**
     * @inheritdoc
     */
    public static function findIdentity($id) {
        return static::findOne(['customer_id' => $id]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null) {
        $token = CustomerToken::find()->where(['token_value' => $token])->with('customer')->one();
        if($token){
            return $token->customer;
        }
    }


    public function getAccessToken(){
        // Return existing inactive token if found
        $token = CustomerToken::findOne([
            'agent_id' => $this->agent_id,
            'token_status' => AgentToken::STATUS_ACTIVE
        ]);
        if($token){
            return $token;
        }

        // Create new inactive token
        $token = new AgentToken();
        $token->agent_id = $this->agent_id;
        $token->token_value = Yii::$app->security->generateRandomString();
        $token->token_status = AgentToken::STATUS_ACTIVE;
        $token->save(false);

        return $token;
    }

//    public function generateAuthKey() {
//        $this->customer_auth_key = Yii::$app->security->generateRandomString();
//    }
}
