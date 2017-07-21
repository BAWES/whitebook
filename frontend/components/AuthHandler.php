<?php
namespace frontend\components;

use Yii;
use yii\authclient\ClientInterface;
use yii\helpers\ArrayHelper;
use frontend\models\Customer;
use frontend\models\Users;

/**
 * AuthHandler handles successful authentication via Yii auth component
 */
class AuthHandler
{
    /**
     * @var ClientInterface
     */
    private $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function handle()
    {
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

        if ($customer) // login
        { 
            //$user = $auth->user;
            //$this->updateUserInfo($user);
            //, Yii::$app->params['user.rememberMeDuration']
            Yii::$app->user->login($customer);
        } 
        else // signup
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

            //Yii::$app->session->set('register', '1');
            
            Yii::$app->user->login($customer);

            //Send Email to admin 
            Customer::notifyAdmin($customer);
        }
    }
}