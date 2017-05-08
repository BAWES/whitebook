<?php

namespace api\modules\v1\controllers;

use common\models\CustomerToken;
use Yii;
use yii\rest\Controller;
use yii\filters\auth\HttpBasicAuth;
use yii\helpers\Url;
use api\models\Customer;

/**
 * Auth controller provides the initial access token that is required for further requests
 * It initially authorizes via Http Basic Auth using a base64 encoded username and password
 */
class AuthController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // remove authentication filter for cors to work
        unset($behaviors['authenticator']);

        // Allow XHR Requests from our different subdomains and dev machines
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className(),
            'cors' => [
                'Origin' => Yii::$app->params['allowedOrigins'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                'Access-Control-Request-Headers' => ['*'],
                'Access-Control-Allow-Credentials' => null,
                'Access-Control-Max-Age' => 86400,
                'Access-Control-Expose-Headers' => [],
            ],
        ];

        // Basic Auth accepts Base64 encoded username/password and decodes it for you
        $behaviors['authenticator'] = [
            'class' => HttpBasicAuth::className(),
            'except' => ['options'],
            'auth' => [$this, 'auth']
        ];
        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
        // also avoid for public actions like registration and password reset
        $behaviors['authenticator']['except'] = [
            'options',
            'create-account',
            'request-reset-password',
        ];

        return $behaviors;
    }


    /**
     * @inheritdoc
     */
    public function actions()
    {
        $actions = parent::actions();

        // Return Header explaining what options are available for next request
        $actions['options'] = [
            'class' => 'yii\rest\OptionsAction',
            // optional:
            'collectionOptions' => ['GET', 'POST', 'HEAD', 'OPTIONS'],
            'resourceOptions' => ['GET', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
        ];
        return $actions;
    }

    public function Auth($email, $password)
    {
        $user = Customer::findOne(['customer_email'=>$email]);
        if ($user && $user->validatePassword($password)) {
            return $user;
        }
        return null;
    }

    /**
     * Perform validation on the agent account (check if he's allowed login to platform)
     * If everything is alright,
     * Returns the BEARER access token required for futher requests to the API
     * @return array
     */
    public function actionLogin()
    {
        $user = Yii::$app->user->identity;

        // Email and password are correct, check if his email has been verified
        // If agent email has been verified, then allow him to log in
        if($user->customer_activation_status == Customer::ACTIVATION_FALSE){
            return [
                "operation" => "error",
                "errorType" => "email-not-verified",
                "message" => "Please click the verification link sent to you by email to activate your account",
            ];
        }

        // Return agent access token if everything valid
        $accessToken = $user->accessToken->token_value;
        return [
            "operation" => "success",
            "token" => $accessToken
        ];
    }

    /**
     * Creates new agent account manually
     * @return array
     */
    public function actionCreateAccount()
    {
        $model = new Customer();
        $model->scenario = 'signup';

        $model->customer_name           = Yii::$app->request->getBodyParam("first_name");
        $model->customer_last_name      = Yii::$app->request->getBodyParam("last_name");
        $model->customer_email          = Yii::$app->request->getBodyParam("email");
        $model->customer_dateofbirth    = Yii::$app->request->getBodyParam("date_of_birth");
        $model->customer_gender         = Yii::$app->request->getBodyParam("gender");
        $model->customer_mobile         = Yii::$app->request->getBodyParam("mobile_number");
        $model->customer_password       = Yii::$app->getSecurity()->generatePasswordHash(Yii::$app->request->getBodyParam('customer_password'));
        $model->customer_activation_key = \frontend\models\Users::generateRandomString();
        $model->created_datetime = date('Y-m-d H:i:s');

        if ($model->validate() && $model->save()) {

            //Send Email to user
            Yii::$app->mailer->htmlLayout = 'layouts/empty';
            Yii::$app->mailer->compose("customer/confirm",
                [
                    "user" => $model->customer_name,
                    "confirm_link" => Url::to(['/users/confirm_email', 'key' => $model->customer_activation_key], true),
                    "logo_1" => Url::to("@web/images/twb-logo-horiz-white.png", true),
                    "logo_2" => Url::to("@web/images/twb-logo-trans.png", true),
                ])
                ->setFrom(Yii::$app->params['supportEmail'])
                ->setTo($model['customer_email'])
                ->setSubject('Welcome to The White Book')
                ->send();

            //Send Email to admin
            Yii::$app->mailer->htmlLayout = 'layouts/html';

            $message_admin = $model->customer_name.' registered in TheWhiteBook';

            $send_admin = Yii::$app->mailer->compose("customer/user-register",
                [
                    'confirm_link' => Url::to(['/users/confirm_email', 'key' => $model->customer_activation_key], true),
                    'logo_1' => Url::to("@web/images/twb-logo-horiz-white.png", true),
                    'logo_2' => Url::to("@web/images/twb-logo-trans.png", true),
                    'model' => $model
                ]
            );

            $send_admin
                ->setFrom(Yii::$app->params['supportEmail'])
                ->setTo(Yii::$app->params['adminEmail'])
                ->setSubject('User registered')
                ->send();

            return [
                "operation" => "success",
                "code" => "1",
                "message" => "Please click on the link sent to you by email to verify your account"
            ];

        } else {
            return [
                "operation" => "error",
                "code" => "0",
                "message" => $model->getErrorMessage($model->errors)
            ];

        }
    }

    /**
     * Sends password reset email to user
     * @return array
     */
    public function actionRequestResetPassword()
    {
        $email = Yii::$app->request->getBodyParam("email");

        if ($email == ' ') {
            return [
                'operation' => 'error',
                'code' => '0',
                'message' => 'Invalid Email'
            ];
        }

        $model = Customer::findOne(['customer_email'=>$email]);
        if ($model) {

            $time = date('Y-m-d H:i:s');
            $model->modified_datetime = $time;
            $model->save();

            $message = 'Your requested password reset.</br><a href='.Url::to(["/users/reset_confirm", "cust_id" => $model->customer_activation_key], true).' title="Click Here">Click here </a> to reset your password';
            $send = Yii::$app->mailer->compose("customer/password-reset",
                ["message" => $message, "user" => "Customer"])
                ->setFrom(Yii::$app->params['supportEmail'])
                ->setTo($email)
                ->setSubject('Requested forgot Password')
                ->send();
            if ($send) {
                return [
                    'operation' => 'success',
                    'code' => '1',
                    'message' => 'Password reset link sent, please check your email for further instructions.'
                ];
            } else {
                return [
                    'operation' => 'error',
                    'code' => '0',
                    'message' => 'Server issue. Please try again'
                ];
            }
        } else {
            return [
                'operation' => 'error',
                'code' => '0',
                'message' => 'Email Does Not Exist'
            ];
        }
    }
}
