<?php

namespace api\modules\v1\controllers;

use admin\models\EventType;
use Yii;
use yii\rest\Controller;
use \common\models\Events;
/**
 * Auth controller provides the initial access token that is required for further requests
 * It initially authorizes via Http Basic Auth using a base64 encoded username and password
 */
class EventController extends Controller
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

    /**
     * method to list event
     * @return array
     */
    public function actionEventList($offset)
    {
        return $this->eventList($offset);
    }

    public function actionEventDetail($event_id)
    {
        if ($event_id){
            return Events::find()
                ->select(['event_id', 'event_name', 'event_date', 'event_type','no_of_guests'])
                ->where(['customer_id' => Yii::$app->user->getId(), 'event_id' => $event_id])
                ->one();
        } else {
            return [
                "operation" => "error",
                "message" => "Invalid Event"
            ];
        }
    }

    public function actionEventCreate()
    {
        $customer_id = Yii::$app->user->getId();
        $name = Yii::$app->request->getBodyParam("name");
        $date = Yii::$app->request->getBodyParam("date");
        $type = Yii::$app->request->getBodyParam("type");
        $guest = Yii::$app->request->getBodyParam("no_of_guests");

        if ($name && $type && $date) {
            $exit = Events::find()
                ->where(['customer_id' => $customer_id, 'event_name' => $name])
                ->exists();
            if ($exit) {
                return [
                    "operation" => "error",
                    "message" => "Event Already Exist With Same Name."
                ];
            }
            $model = new Events;
            $model->customer_id = $customer_id;
            $model->event_name = $name;
            $model->event_date = date('Y-m-d', strtotime($date));
            $model->event_type = $type;
            $model->no_of_guests = $guest;
            $model->slug = $this->generateSlug($name);
            if ($model->save()) {
                return [
                    "operation" => "success",
                    "message" => "Event Created Successfully.",
                ];
            } else {
                return [
                    "operation" => "error",
                    "message" => "Error While Saving Error.",
                    "detail" => $model->errors
                ];
            }
        } else {
            return [
                "operation" => "error",
                "message" => "Empty event fields."
            ];
        }

    }

    public function actionEventUpdate()
    {
        $event_id = Yii::$app->request->getBodyParam("event_id");
        $name = Yii::$app->request->getBodyParam("name");
        $date = Yii::$app->request->getBodyParam("date");
        $type = Yii::$app->request->getBodyParam("type");
        $guest = Yii::$app->request->getBodyParam("no_of_guests");

        if ($name && $type && $date && $event_id) {
            $model = Events::findOne($event_id);

            if ($model) {
                $model->event_name = $name;
                $model->event_date = date('Y-m-d', strtotime($date));
                $model->event_type = $type;
                $model->no_of_guests = $guest;
                $model->slug = $this->generateSlug($name);

                if ($model->save()) {
                    return [
                        "operation" => "success",
                        "message" => "Event Saved Successfully.",
                    ];
                } else {
                    return [
                        "operation" => "error",
                        "message" => "Invalid Event.",
                        "detail" => $model->errors,
                    ];
                }
            } else {
                return [
                    "operation" => "error",
                    "message" => "Invalid Event."
                ];
            }
        } else {
            return [
                "operation" => "error",
                "message" => "Invalid Event ID."
            ];
        }
    }

    /*
     * Method will delete user event from event table
     */
    public function actionEventRemove($event_id) {

	if ($event_id) {
            $event = Events::find()
                ->where(['customer_id' => Yii::$app->user->getId(), 'event_id' => $event_id])
                ->one();
            if ($event) {
                $event->delete();
                return [
                    "operation" => "success",
                    "message" => "Event Deleted Successfully.",
                ];
            } else {
                return [
                    "operation" => "error",
                    "message" => "Invalid Event."
                ];
            }
        } else {
            return [
                "operation" => "error",
                "message" => "Invalid Event."
            ];
        }
    }


    /*
     * Method to list all event related with particular user
     */
    private function eventList($offset = 0){
        $limit = $limit = Yii::$app->params['limit'];
        return Events::find()
            ->select(['event_id','event_name','event_date','event_type','no_of_guests'])
            ->where(['customer_id'=>Yii::$app->user->getId()])
            ->offset($offset)
            ->limit($limit)
            ->orderBy('event_id DESC')
            ->all();
    }

    /*
     * Method to list all event type
     */
    public function actionEventTypeList(){

        return EventType::find()
            ->select(['type_name'])
            ->where(['trash'=>'default'])
            ->all();
    }
    /*
     * Common method to generate slug from string
     */
    public function generateSlug($string) {
        $slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $string)); // Removes special chars.
        return $slug.'-'.time();
    }
}
