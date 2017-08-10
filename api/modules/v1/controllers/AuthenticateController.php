<?php 

namespace api\modules\v1\controllers;

use yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use yii\authclient\ClientInterface;
use api\components\AuthHandler;
use api\models\Customer;
use frontend\models\Users;

class AuthenticateController extends Controller
{
    /**
     * @var ClientInterface
     */
    private $client;

    public function actions()
    {
        return [
            'index' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'onAuthSuccess'],
            ],
        ];
    }

    public function onAuthSuccess($client)
    {
        $this->client = $client;

        $attributes = $this->client->getUserAttributes();
        $emails = ArrayHelper::getValue($attributes, 'emails');
        $id = ArrayHelper::getValue($attributes, 'id');
        $nickname = ArrayHelper::getValue($attributes, 'login');
        $name = ArrayHelper::getValue($attributes, 'name');
        $gender = ArrayHelper::getValue($attributes, 'gender');
        $first_name = ArrayHelper::getValue($name, 'givenName');
        $last_name = ArrayHelper::getValue($name, 'familyName');
        $email = $emails[0]['value'];

        $customer = Customer::find()->where([
            'customer_email' => $email
        ])->one();

        if (!$customer) 
        { 
            $customer = new Customer;            
            $customer->customer_name = $first_name;
            $customer->customer_last_name = $last_name;
            $customer->customer_email = $email;
            $customer->customer_gender = $gender;
            //$model->customer_mobile = 
            //$model->customer_dateofbirth 
            $customer->customer_password = Yii::$app->getSecurity()->generatePasswordHash($customer->customer_password);      
            $customer->customer_activation_status = 1;      
            $customer->save(false);
            
            //Send Email to admin 
            Customer::notifyAdmin($customer);
        }

        return $this->redirect([
            'users/account_settings', //dummy url 
            'token' => $customer->accessToken->token_value,
            'email' => $customer->customer_email
        ]);
    }
}