<?php 

namespace frontend\controllers;

//use yii;
use yii\web\Controller;
use frontend\components\AuthHandler;

class AuthenticateController extends Controller
{
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
        (new AuthHandler($client))->handle();

        return $this->redirect(['users/account_settings']);
    }
}