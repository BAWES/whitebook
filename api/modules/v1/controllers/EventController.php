<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\rest\Controller;
use \common\models\Events;
/**
 * Auth controller provides the initial access token that is required for further requests
 * It initially authorizes via Http Basic Auth using a base64 encoded username and password
 */
class EventController extends Controller
{
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
     * @return array
     */
    public function actionEventList()
    {
        return $this->eventList();
    }

    public function actionEventDetail($id)
    {
        if ($id){
            $customer_id = 182; // it will be from session
            return Events::find()
                ->select(['event_id', 'event_name', 'event_date', 'event_type'])
                ->where(['customer_id' => $customer_id, 'event_id' => $id])
                ->asArray()
                ->all();
        } else {
            return [
                "operation" => "error",
                "message" => "Invalid Event"
            ];
        }
    }

    public function actionEventCreate()
    {
        $customer_id = 182;
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
                    "message" => "Event Already Exist with same name."
                ];
            }

            $model = new Events;
            $model->customer_id = $customer_id;
            $model->event_name = $name;
            $model->event_date = date('Y-m-d', strtotime($date));
            $model->event_type = $type;
            $model->no_of_guests = $guest;
            $model->slug = $this->generateSlug($name);
            $model->save();

            return $this->eventList();
        } else {

            return [
                "operation" => "error",
                "message" => "Empty event fields."
            ];
        }

    }

    public function actionEventUpdate($id)
    {
        $customer_id = 182;

        $name = Yii::$app->request->getBodyParam("name");
        $date = Yii::$app->request->getBodyParam("date");
        $type = Yii::$app->request->getBodyParam("type");
        $guest = Yii::$app->request->getBodyParam("no_of_guests");

        if ($name && $type && $date) {
            $model = Events::findOne($id);

            if ($model) {
                $model->event_name = $name;
                $model->event_date = date('Y-m-d', strtotime($date));
                $model->event_type = $type;
                $model->no_of_guests = $guest;


                $model->slug = $this->generateSlug($name);
                $model->save();

                return $this->eventList();
            } else {
                return [
                    "operation" => "error",
                    "message" => "Invalid Event."
                ];
            }
        } else {

            return [
                "operation" => "error",
                "message" => "Empty event fields."
            ];
        }
    }

    /*
     * Method will delete user event from event table
     */
    public function actionEventDelete($id) {

        $customer_id = 182; // it will be from session

        $event =  Events::find()
            ->where(['customer_id'=>$customer_id, 'event_id' => $id])
            ->one();
        if ($event) {
            $event->delete();
        } else {
            return [
                "operation" => "error",
                "message" => "Invalid Event."
            ];
        }


        return $this->eventList();
    }

    /*
     * Method to list all event related with particular user
     */
    public function eventList(){

        $customer_id = 182;
        $offset = 0;
        $limit = 10;
        return Events::find()
            ->select(['event_id','event_name','event_date','event_type'])
            ->where(['customer_id'=>$customer_id])
            ->offset($offset)
            ->limit($limit)
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
