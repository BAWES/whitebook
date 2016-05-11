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
             $start  = date("H:i", strtotime($model->timeslot_start_time));
             $end  = date("H:i", strtotime($model->timeslot_end_time));
             $model->timeslot_start_time=$start;
             $model->timeslot_end_time=$end;
             $model->save();
            echo Yii::$app->session->setFlash('success', "Delivery time slot created successfully!");
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

            echo $model->timeslot_start_time=$start;
            echo $model->timeslot_end_time=$end;
            $model->save();
            echo Yii::$app->session->setFlash('success', "Delivery time slot updated successfully!");
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
        echo Yii::$app->session->setFlash('success', "Delivery time slot deleted successfully!");
        return $this->redirect(['index']);
       }
    }


        public static function actionChecktime()
    {
          if(Yii::$app->request->isAjax)
           $data = Yii::$app->request->post();
           $day='"'.$data['day'].'"';
           $start=$data['start'];
           $end=$data['end'];
            $start_hour=substr($start, 0, 2);
            $end_hour=substr($end, 0, 2);

            $start_minute=substr($start, 3, 2);
            $end_minute=substr($end, 3, 2);

            $start_day=substr($start, 6, 2);
            $end_day=substr($end, 6, 2);

            // convert values into UNIX timestamp integers
            $Ymd = date('Y-m-d'); // just in case we try to process across midnight
            $start_ts = strtotime("$Ymd $start_hour:$start_minute $start_day");
            $end_ts = strtotime("$Ymd $end_hour:$end_minute $end_day");
            // test if end time is later than start time
            if($end_ts < $start_ts) {
                return 1;
            }

		  $update=$data['update'];
          if($update==0){
				$result = Deliverytimeslot::find()->select(["DATE_FORMAT(`timeslot_start_time`,'%h:%i %p') as start","DATE_FORMAT(`timeslot_end_time`,'%h:%i %p') as end1"])
				->where(['timeslot_day' => $day])
				->asArray()
				->all();
            }else{
            	$result = Deliverytimeslot::find()->select(["DATE_FORMAT(`timeslot_start_time`,'%h:%i %p') as start","DATE_FORMAT(`timeslot_end_time`,'%h:%i %p') as end1"])
            		->where(['timeslot_day' => $day])
            		->andwhere(['!=','timeslot_day', $day])
            		->asArray()->all();
            }
                   
             $dt1 = $data['start'];
             $dt2 = $data['end'];
            $k=array();
            foreach ($result as $r)
            {
                $range=range(strtotime($r['start']),strtotime($r['end1']),01*60);
            foreach($range as $time){
                    $k[]=date("h:i a",$time)."\n";
            }
            }

            foreach ($k as $dt) {
            $dt = str_replace(' ', '', $dt);
             $a=(strtotime($dt1));
             $b=(strtotime($dt2));
             $c=(strtotime($dt));
                if($a == $c){
                    return  2;
                }else if($b == $c){
                    return  2;
                }
            }


  }


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
