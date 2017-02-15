<?php

namespace api\models;

use common\models\CustomerToken;
use Yii;

/**
 * Class Customer
 * @package api\models
 */
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


    /**
     * @return CustomerToken|static
     */
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

    /**
     * @param $password
     * @return bool
     */
    public function validatePassword($password){
        if (!Yii::$app->getSecurity()->validatePassword($password,$this->customer_password)){
            return false;
        }
        return true;
    }

    /**
     * @param $error
     * @return string
     */
    public function getErrorMessage($error) {
        if (isset($error['customer_name'])) {
            return $error['customer_name'];
        } else if (isset($error['customer_last_name'])) {
            return $error['customer_name'];
        } else if (isset($error['customer_email'])) {
            return implode(',',$error['customer_email']);
        } else if (isset($error['customer_dateofbirth'])) {
            return implode(',',$error['customer_dateofbirth']);
        } else if (isset($error['customer_gender'])) {
            return implode(',',$error['customer_gender']);
        } else if (isset($error['customer_mobile'])) {
            return implode(',',$error['customer_mobile']);
        } else if (isset($error['customer_password'])) {
            return implode(',',$error['customer_password']);
        }
    }
}
