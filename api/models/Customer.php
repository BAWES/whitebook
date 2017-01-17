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
            'customer_id' => $this->customer_id,
            'token_status' => CustomerToken::STATUS_ACTIVE
        ]);

        if($token){
            return $token;
        }

        // Create new inactive token
        $token = new CustomerToken();
        $token->customer_id = $this->customer_id;
        $token->token_value = Yii::$app->security->generateRandomString();
        $token->token_status = CustomerToken::STATUS_ACTIVE;
        $token->save(false);

        return $token;
    }

    public function validatePassword($password){
        if (!Yii::$app->getSecurity()->validatePassword($password,$this->customer_password)){
            return false;
        }
        return true;
    }
}
