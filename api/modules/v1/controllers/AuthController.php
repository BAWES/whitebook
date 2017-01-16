<?php

namespace api\modules\v1\controllers;

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
            'auth' => function ($email, $password) {
                $agent = Agent::findByEmail($email);
                if ($agent && $agent->validatePassword($password)) {
                    return $agent;
                }

                return null;
            }
        ];
        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
        // also avoid for public actions like registration and password reset
        $behaviors['authenticator']['except'] = [
            'options',
            'create-account',
            'request-reset-password',
            'resend-verification-email'
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


    /**
     * Perform validation on the agent account (check if he's allowed login to platform)
     * If everything is alright,
     * Returns the BEARER access token required for futher requests to the API
     * @return array
     */
    public function actionLogin()
    {
        $agent = Yii::$app->user->identity;

        // Email and password are correct, check if his email has been verified
        // If agent email has been verified, then allow him to log in
        if($agent->agent_email_verified != Agent::EMAIL_VERIFIED){
            return [
                "operation" => "error",
                "errorType" => "email-not-verified",
                "message" => "Please click the verification link sent to you by email to activate your account",
            ];
        }

        // Return agent access token if everything valid
        $accessToken = $agent->accessToken->token_value;
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
        $model->confirm_password        = Yii::$app->request->getBodyParam('confirm_password');
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

            $send_admin = Yii::$app->mailer->compose(
                ["html" => "customer/user-register"],
                ["message" => $message_admin]
            );

            $send_admin
                ->setFrom(Yii::$app->params['supportEmail'])
                ->setTo(Yii::$app->params['adminEmail'])
                ->setSubject('User registered')
                ->send();

            return [
                "operation" => "success",
                "message" => "Please click on the link sent to you by email to verify your account"
            ];

        } else {
            return [
                "operation" => "error",
//                "message" => "We've faced a problem creating your account, please contact us for assistance."
                "message" => $model->errors
            ];

        }
    }

    /**
     * Re-send manual verification email to agent
     * @return array
     */
    public function actionResendVerificationEmail()
    {
        $emailInput = Yii::$app->request->getBodyParam("email");

        $agent = Agent::findOne([
            'agent_email' => $emailInput,
        ]);

        $errors = false;

        if ($agent) {
            //Check if this user sent an email in past few minutes (to limit email spam)
            $emailLimitDatetime = new \DateTime($agent->agent_limit_email);
            date_add($emailLimitDatetime, date_interval_create_from_date_string('2 minutes'));
            $currentDatetime = new \DateTime();

            if ($currentDatetime < $emailLimitDatetime) {
                $difference = $currentDatetime->diff($emailLimitDatetime);
                $minuteDifference = (int) $difference->i;
                $secondDifference = (int) $difference->s;

                $errors = Yii::t('app', "Email was sent previously, you may request another one in {numMinutes, number} minutes and {numSeconds, number} seconds", [
                            'numMinutes' => $minuteDifference,
                            'numSeconds' => $secondDifference,
                ]);
            } else if ($agent->agent_email_verified == Agent::EMAIL_NOT_VERIFIED) {
                $agent->sendVerificationEmail();
            }
        }

        // If errors exist show them
        if($errors){
            return [
                'operation' => 'error',
                'message' => $errors
            ];
        }

        // Otherwise return success
        return [
            'operation' => 'success',
            'message' => Yii::t('register', 'Please click on the link sent to you by email to verify your account')
        ];
    }

    /**
     * Sends password reset email to user
     * @return array
     */
    public function actionRequestResetPassword()
    {
        $emailInput = Yii::$app->request->getBodyParam("email");

        $model = new \agent\models\PasswordResetRequestForm();
        $model->email = $emailInput;

        $errors = false;

        if ($model->validate()){

            $agent = Agent::findOne([
                'agent_email' => $model->email,
            ]);

            if ($agent) {
                //Check if this user sent an email in past few minutes (to limit email spam)
                $emailLimitDatetime = new \DateTime($agent->agent_limit_email);
                date_add($emailLimitDatetime, date_interval_create_from_date_string('2 minutes'));
                $currentDatetime = new \DateTime();

                if ($currentDatetime < $emailLimitDatetime) {
                    $difference = $currentDatetime->diff($emailLimitDatetime);
                    $minuteDifference = (int) $difference->i;
                    $secondDifference = (int) $difference->s;

                    $errors = Yii::t('app', "Email was sent previously, you may request another one in {numMinutes, number} minutes and {numSeconds, number} seconds", [
                                'numMinutes' => $minuteDifference,
                                'numSeconds' => $secondDifference,
                    ]);

                } else if (!$model->sendEmail($agent)) {
                    $errors = Yii::t('agent', 'Sorry, we are unable to reset password for email provided.');
                }
            }
        }else if(isset($model->errors['email'])){
            $errors = $model->errors['email'];
        }

        // If errors exist show them
        if($errors){
            return [
                'operation' => 'error',
                'message' => $errors
            ];
        }

        // Otherwise return success
        return [
            'operation' => 'success',
            'message' => Yii::t('agent', 'Password reset link sent, please check your email for further instructions.')
        ];
    }
}
