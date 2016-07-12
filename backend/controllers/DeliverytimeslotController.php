<?php

namespace backend\controllers;

use Yii;
use backend\models\Vendor;
use backend\models\Deliverytimeslot;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\i18n\Formatter;
use yii\filters\AccessControl;

/**
 * DeliverytimeslotController implements the CRUD actions for Deliverytimeslot model.
 */
class DeliverytimeslotController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    //'delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [//allow authenticated users only
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Deliverytimeslot models.
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Displays a single Deliverytimeslot model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Deliverytimeslot model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Deliverytimeslot();
        $vendor = Vendor::loadvendorname();
        $model->vendor_id = Vendor::getVendor('vendor_id');
        $day = array('Sunday'=>'Sunday','Monday'=>'Monday','Tuesday'=>'Tuesday','Wednesday'=>'Wednesday','Thursday'=>'Thursday','Friday'=>'Friday','Saturday'=>'Saturday');

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $start = date("H:i", strtotime($model->timeslot_start_time));
            $end = date("H:i", strtotime($model->timeslot_end_time));
            $model->timeslot_start_time=$start;
            $model->timeslot_end_time=$end;
            $model->save();
            
            Yii::$app->session->setFlash('success', "Delivery time slot created successfully!");

            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,'vendor'=>$vendor,'days'=>$day
            ]);
        }
    }

    /**
     * Updates an existing Deliverytimeslot model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $vendor = Vendor::loadvendorname();
        $days = array('Sunday'=>'Sunday','Monday'=>'Monday','Tuesday'=>'Tuesday','Wednesday'=>'Wednesday','Thursday'=>'Thursday','Friday'=>'Friday','Saturday'=>'Saturday');

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            $start  = date("H:i", strtotime($model->timeslot_start_time));
            $end  = date("H:i", strtotime($model->timeslot_end_time));

            $model->timeslot_start_time = $start;
            $model->timeslot_end_time = $end;
            $model->save();
            
            Yii::$app->session->setFlash('success', "Delivery time slot updated successfully!");
            
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,'vendor'=>$vendor,'days'=>$days,
            ]);
        }
    }

    /**
     * Deletes an existing Deliverytimeslot model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id = false)
    {
        if(Yii::$app->request->isAjax)
        {
            $data = Yii::$app->request->post();
            $this->findModel($data['id'])->delete();
            
            Yii::$app->session->setFlash('success', "Delivery time slot deleted successfully!");
            
            return $this->redirect(['index']);
        }
    }

    /*  Check if given timeslot collide with other timeslots in same day 
        @param string day:Thursday
        @param string start:04:15PM
        @param string end:04:45PM
        @param string update:25 
    */
    public static function actionChecktime()
    {
        $status = 1;
        $message = '';

        $data = Yii::$app->request->post();

        $start_time = strtotime($data['start']);
        $end_time = strtotime($data['end']);

        //get all timeslot for given day and current vendor and not current record
        if($data['update']){
            $timeslots = Deliverytimeslot::find()
            ->where(['timeslot_day' => $data['day'], 'vendor_id'=>Yii::$app->user->getId()])
            ->andwhere(['!=','timeslot_id', $data['update']])
            ->asArray()
            ->all();
        }else{
            $timeslots = Deliverytimeslot::find()
            ->where(['timeslot_day' => $data['day'], 'vendor_id'=>Yii::$app->user->getId()])
            ->asArray()
            ->all();
        }

        foreach ($timeslots as $row) {
            
            $timeslot_start_time = strtotime($row['timeslot_start_time']);
            $timeslot_end_time = strtotime($row['timeslot_end_time']);

            if($start_time >= $timeslot_start_time && $start_time <= $timeslot_end_time) {
                $status = 0;
                $message = 'Timeslot colide with '.date('h:i A', $timeslot_start_time).' - '.date('h:i A', $timeslot_end_time);
                break;
            }

            if($end_time >= $timeslot_start_time && $end_time <= $timeslot_end_time) {
                $status = 0;
                $message = 'Timeslot collision with '.date('h:i A', $timeslot_start_time).' - '.date('h:i A', $timeslot_end_time);
                break;
            }
        }

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        return [
            'message' => $message,
            'status' => $status,
        ];    
        
    }//end of function 


    /**
     * Finds the Deliverytimeslot model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Deliverytimeslot the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Deliverytimeslot::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
