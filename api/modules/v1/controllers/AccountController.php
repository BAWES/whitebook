<?php

namespace api\modules\v1\controllers;

use api\models\Customer;
use Yii;
use yii\rest\Controller;

/**
 * Account controller will return the actual Instagram Accounts and all controls associated
 */
class AccountController extends Controller
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

        // Bearer Auth checks for Authorize: Bearer <Token> header to login the user
        $behaviors['authenticator'] = [
            'class' => \yii\filters\auth\HttpBearerAuth::className(),
        ];
        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
        $behaviors['authenticator']['except'] = [
            'options',
            'contact',
            'vendor-request'
        ];

        return $behaviors;
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        $actions = parent::actions();
        $actions['options'] = [
            'class' => 'yii\rest\OptionsAction',
            // optional:
            'collectionOptions' => ['GET', 'POST', 'HEAD', 'OPTIONS'],
            'resourceOptions' => ['GET', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
        ];
        return $actions;
    }

    /**
     * Return Current user
     * account detail
     */
    public function actionIndex()
    {
        return $this->currentUser();
    }

    /**
     * Update account detail
     */
    public function actionUpdate()
    {
        $customer_id = Yii::$app->user->getId();

        $first_name = Yii::$app->request->getBodyParam('customer_name');
        $last_name  = Yii::$app->request->getBodyParam('customer_last_name');
        $email      = Yii::$app->request->getBodyParam('customer_email');
        $gender     = Yii::$app->request->getBodyParam('customer_gender');
        $mobile     = Yii::$app->request->getBodyParam('customer_mobile');
        $dob        = Yii::$app->request->getBodyParam('customer_dateofbirth');

        $data = Customer::findOne(['customer_email'=> $email]);
        if ($data) {
            if ($data->customer_id != $customer_id) {
                return [
                    "operation" => "error",
                    "message" => Yii::t('api', "Email already in use."),
                ];
            }
            $model = Customer::findOne($customer_id);
            $model->customer_name = $first_name;
            $model->customer_last_name = $last_name;
            $model->customer_email = $email;
            $model->customer_gender = $gender;
            $model->customer_dateofbirth = $dob;
            $model->customer_mobile = $mobile;
            $model->modified_datetime = date('Y-m-d H:i:s');

            if ($model->save()) {
                return [
                    "operation" => "success",
                    "message" => Yii::t('api', "Profile updated successfully."),
                ];
            } else {
                return [
                    "operation" => "error",
                    "message" => $model->getErrorMessage($model->errors),
                ];
            }
        } else {
            return [
                "operation" => "error",
                "message" => Yii::t('api', "Invalid Profile."),
            ];
        }
    }

    private function currentUser(){
        $customer_id = Yii::$app->user->getId();
        return Customer::findOne($customer_id);
    }

    public function actionContact()
    {
        $subject = 'Enquiry from user';
        $name    = Yii::$app->request->getBodyParam('name');
        $email   = Yii::$app->request->getBodyParam('email');
        $msg     = Yii::$app->request->getBodyParam('msg');
        $topic   = Yii::$app->request->getBodyParam('topic');

        $model = new \api\models\Contacts();
        $model->contact_name = $name;
        $model->contact_email = $email;
        $model->created_datetime = date('Y/m/d');
        $model->message = $msg;
        $model->subject = $subject;

        $body = '<table>
        <tbody>
        <tr>
        <td><b>Username</b></td>
        <td>' . $name . '</td>
        </tr>
        <tr>
        <td><b>Email-id</b></td>
        <td>' . $email . '</td>
        </tr>
        <tr>
        <td><b>Topic</b></td>
        <td>' . $topic . '</td>
        </tr>
        <tr>
        <td><b>Message</b></td>
        <td>' . $msg . '</td>
        </tr>
        </tbody>
        </table>';

        if ($model->validate() && $model->save()) {
            Yii::$app->mailer->compose([
                "html" => "customer/contact-inquiry"
            ], [
                "message" => $body,
                "user" => $name
            ])
                ->setFrom(Yii::$app->params['supportEmail'])
                ->setTo(Yii::$app->params['adminEmail'])
                ->setSubject($subject)
                ->send();

            return [
                "operation" => "success",
                "code" => "1",
                "message" => Yii::t('api', "Mail sent successfully"),
            ];

        } else {

            return [
                "operation" => "error",
                "code" => "1",
                "message" => $model->getErrors()// errorDetail(),
            ];
        }
    }

    public function actionVendorRequest() 
    {        
        $model = new \common\models\VendorRequest();
        $model->business = Yii::$app->request->getBodyParam('business');
        $model->name = Yii::$app->request->getBodyParam('name');
        $model->mobile = Yii::$app->request->getBodyParam('mobile');
        $model->email = Yii::$app->request->getBodyParam('email');
        $model->licence = Yii::$app->request->getBodyParam('licence');
        $model->description = Yii::$app->request->getBodyParam('description');

        if(!$model->validate())
        {
            return [
                'operation' => 'error',
                'message' => $model->errorDetail()
            ];
        }

        Yii::$app->mailer->compose("admin/vendor-request",
            [
                "business" => $model->business,
                "name" => $model->name,
                'mobile' => $model->mobile,
                'email' => $model->email,
                'license' => $model->licence,
                'description' => $model->description
            ])
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->params['SITE_NAME']])
            ->setTo(Yii::$app->params['adminEmail'])
            ->setSubject('Vendor Registration Request')
            ->send();

        return [
            "operation" => "success",
            "message" => Yii::t("api", "Mail sent successfully"),
        ];
    }


    public function actionLogout()
    {
        $user = Yii::$app->user->identity;
        // Email and password are correct, check if his email has been verified
        // If agent email has been verified, then allow him to log in
        if($user->customer_activation_status == Customer::ACTIVATION_FALSE){
            return [
                "operation" => "error",
                "errorType" => "email-not-verified",
                "message" => Yii::t("api", "Please click the verification link sent to you by email to activate your account"),
            ];
        }
        $token = \common\models\CustomerToken::findOne([
            'customer_id' => $user->getId(),
        ]);

        $deleted = \common\models\CustomerToken::deleteAll(['customer_id'=>$user->getId(),'token_value'=>$token->token_value]);

        if ($deleted) {
            return [
                "operation" => "success",
                "code" => "1",
                "message" => Yii::t("api", 'User logged out successfully')
            ];
        } else {
            return [
                "operation" => "error",
                "code" => "0",
                "message" => Yii::t("api", 'Error with User logged out. Please try again.')
            ];
        }
    }
}
