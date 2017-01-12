<?php

namespace api\modules\v1\controllers;

use common\models\AddressQuestion;
use common\models\AddressType;
use common\models\Location;
use api\models\CustomerAddress;
use common\models\CustomerAddressResponse;
use Yii;
use yii\rest\Controller;
/**
 * Auth controller provides the initial access token that is required for further requests
 * It initially authorizes via Http Basic Auth using a base64 encoded username and password
 */
class AddressController extends Controller
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
        $behaviors['authenticator']['except'] = ['options'];

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

    /*
     * To show all address type
     */
    public function actionAddressTypeList() {

        return AddressType::find()
            ->select(['type_id', 'type_name', 'type_name_ar'])
            ->where([
                'status' => 'Active',
                'trash' => 'Default'
            ])->all();
    }

    /*
     * Listing of all address
     */
    public function actionAddressList() {

        return $this->listing();
    }

    /*
     * Common method to call for Listing of all address
     */
    public function listing(){

        $customer_id = 182;
        $addresses = array();

        $result = CustomerAddress::find()
            ->select('whitebook_city.city_name, whitebook_city.city_name_ar, whitebook_location.location,
                whitebook_location.location_ar, whitebook_customer_address.*')
            ->leftJoin('whitebook_location', 'whitebook_location.id = whitebook_customer_address.area_id')
            ->leftJoin('whitebook_city', 'whitebook_city.city_id = whitebook_customer_address.city_id')
            ->where('customer_id = :customer_id', [':customer_id' => $customer_id])
            ->asArray()
            ->all();

        foreach($result as $row) {

            $row['questions'] = CustomerAddressResponse::find()
                ->select('aq.question_ar, aq.question, whitebook_customer_address_response.*')
                ->innerJoin('whitebook_address_question aq', 'aq.ques_id = address_type_question_id')
                ->where('address_id = :address_id', [':address_id' => $row['address_id']])
                ->asArray()
                ->all();

            $addresses[] = $row;
        }
        return $addresses;
    }

    /*
     * To add new address
     */
    public function actionAddressAdd() {

        $customer_id = 182;
        $questions = Yii::$app->request->getBodyParam("questions");

        $address_name = Yii::$app->request->getBodyParam("address_name");
        $address_data = Yii::$app->request->getBodyParam("address_data");
        $address_type_id = Yii::$app->request->getBodyParam("address_type_id");
        $address_archived = Yii::$app->request->getBodyParam("address_archived");
        $area_id = Yii::$app->request->getBodyParam("area_id");

        if(!$questions) {
            $questions = array();
        }

        //save address
        $customer_address = new CustomerAddress();
        $customer_address->address_name = $address_name;
        $customer_address->address_data = $address_data;
        $customer_address->address_type_id = $address_type_id;
        $customer_address->address_archived = $address_archived;
        $customer_address->area_id = $area_id;
        $customer_address->customer_id = $customer_id;
        $customer_address->created_by = $customer_id;
        $customer_address->modified_by = $customer_id;

        $location = Location::findOne($customer_address->area_id);

        $customer_address->city_id = $location->city_id;
        $customer_address->country_id = $location->country_id;
        $customer_address->save(false);

        $address_id = $customer_address->address_id;

//        save address questions
        foreach ($questions as $key => $value) {
            $customer_address_response = new CustomerAddressResponse();
            $customer_address_response->address_id = $address_id;
            $customer_address_response->address_type_question_id = $value['address_type_question_id'];
            $customer_address_response->response_text = $value['response_text'];
            $customer_address_response->save();
        }

        return $this->listing();
    }

    /*
     * To edit address
     */
    public function actionAddressUpdate()
    {
        $customer_id = 182;
        $questions  = Yii::$app->request->getBodyParam('questions');
        $address_id = Yii::$app->request->getBodyParam('address_id');

        $address_name = Yii::$app->request->getBodyParam("address_name");
        $address_data = Yii::$app->request->getBodyParam("address_data");
        $address_type_id = Yii::$app->request->getBodyParam("address_type_id");

        $area_id = Yii::$app->request->getBodyParam("area_id");

        if(!$questions) {
            $questions = array();
        }

        $customer_address = CustomerAddress::findone([
            'address_id' => $address_id,
            'customer_id' => $customer_id
        ]);

        //save address
        if ($customer_address) {
            $customer_address->address_name = $address_name;
            $customer_address->address_data = $address_data;
            $customer_address->address_type_id = $address_type_id;
            $customer_address->area_id = $area_id;
            $customer_address->modified_by = $customer_id;

            $location = Location::findOne($customer_address->area_id);

            $customer_address->city_id = $location->city_id;
            $customer_address->country_id = $location->country_id;
            $customer_address->save(false);

            //remove old questions
            CustomerAddressResponse::deleteAll(['address_id' => $address_id]);

            //save address questions
            foreach ($questions as $key => $value) {
                $customer_address_response = new CustomerAddressResponse();
                $customer_address_response->address_id = $address_id;
                $customer_address_response->address_type_question_id = $value['address_type_question_id'];
                $customer_address_response->response_text = $value['response_text'];
                $customer_address_response->save();
            }
        }
        return $this->listing();

    }

    /*
     * To view Particular address detail
     */
    public function actionAddressView($id)
    {
        $customer_id = 182;
        $address_id = $id;
        $combinedAddress = [];
        $combinedAddress['address'] = CustomerAddress::findone([
            'address_id' => $address_id,
            'customer_id' => $customer_id
        ]);

         $address = CustomerAddressResponse::find()
            ->select('aq.question_ar, aq.question, whitebook_customer_address_response.*')
            ->innerJoin('whitebook_address_question aq', 'aq.ques_id = address_type_question_id')
            ->where('address_id = :address_id', [':address_id' => $address_id])
            ->asArray()
            ->all();
        if ($address) {
            $combinedAddress['question'] = $address;
        }
        return $combinedAddress;
    }


    /*
     * To delete address and question/response
     */
    public function actionAddressRemove($id)
    {
        $customer_id = 182;
        $address_id = $id;

        $exist = CustomerAddress::find()
            ->where(['address_id' => $address_id, 'customer_id' => $customer_id])
            ->one();

        if ($exist) {
            CustomerAddressResponse::deleteAll('address_id = ' . $address_id);
            CustomerAddress::deleteAll('address_id = ' . $address_id);
        }

        return $this->listing();
    }

    /*
     * List address type related question
     */
    public function actionAddressQuestions($address_type_id)
    {
        $questions = AddressQuestion::find()
            ->select(['ques_id','address_type_id','question','question_ar'])
            ->where([
                'address_type_id' => $address_type_id,
                'trash' => 'Default',
                'status' => 'Active'])
            ->asArray()
            ->all();
        return $questions;
    }
}
