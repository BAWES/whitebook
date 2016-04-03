<?php

namespace backend\controllers;

use Yii;
use common\models\Vendoritemcapacityexception;
use common\models\VendoritemcapacityexceptionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Setdateformat;
use common\models\Vendor;
use yii\filters\AccessControl;

/**
 * VendoritemcapacityexceptionController implements the CRUD actions for Vendoritemcapacityexception model.
 */
class VendoritemcapacityexceptionController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                  //  'delete' => ['post'],
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
     * Lists all Vendoritemcapacityexception models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new VendoritemcapacityexceptionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$startdate = Yii::$app->db->createCommand('SELECT exception_date FROM whitebook_vendor_item_capacity_exception where trash ="Default" order by exception_date asc' )->queryAll();
		$startdate = date ("d-m-Y", strtotime($startdate[0]['exception_date']));

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'startdate' => $startdate,
        ]);
    }

    /**
     * Displays a single Vendoritemcapacityexception model.
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
     * Creates a new Vendoritemcapacityexception model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Vendoritemcapacityexception();
        $vendor_id = Vendor::getVendor('vendor_id');

        $exist_date = \Yii::$app->db->createCommand('Select exception_date FROM whitebook_vendor_item_capacity_exception where created_by='.$vendor_id);
        $exist_date = $exist_date->queryAll();
        if(empty($exist_date))
        {
			$exist_dates = '';
		}	else  {

			foreach($exist_date as $date)
			{
				$dat[] = date('n/j/Y', strtotime($date['exception_date']));

			}

			$exist_dates = implode('","',$dat);
		}

			if ($model->load(Yii::$app->request->post()) && $model->validate()) {
			 $item = Yii::$app->request->post('Vendoritemcapacityexception');
			 $model->item_id = implode(',',$model->item_id);
			 $model->exception_date = Setdateformat::convert($model->exception_date);
			 $model->save();
            echo Yii::$app->session->setFlash('success', "Exception date created successfully!");

             return $this->redirect(['index']);

			} else {
            return $this->render('create', [
                'model' => $model,'exist_dates' => $exist_dates,
            ]);
        }
    }

    /**
     * Updates an existing Vendoritemcapacityexception model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		$model->item_id = explode(',',$model->item_id);
		$vendor_id = Vendor::getVendor('vendor_id');
        $exist_date = \Yii::$app->db->createCommand('Select exception_date FROM whitebook_vendor_item_capacity_exception where created_by='.$vendor_id);
        $exist_date = $exist_date->queryAll();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
			$item = Yii::$app->request->post('Vendoritemcapacityexception');
			 $model->item_id = implode(',',$model->item_id);
			 $model->exception_date = Setdateformat::convert($model->exception_date);
			 $model->save();
             echo Yii::$app->session->setFlash('success', "Exception date updated successfully!");
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Vendoritemcapacityexception model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        echo Yii::$app->session->setFlash('success', "Exception date deleted successfully!");
        return $this->redirect(['index']);
    }

    public static function actionCheckitems()
    {
    if(Yii::$app->request->isAjax)
           $data = Yii::$app->request->post();
           $item_id=$data['item_id'];
           $exception_date=$data['exception_date'];
           $exception_date= date ("Y-m-d",strtotime("0 day", strtotime($exception_date)));
           $exception_date="'".$exception_date."'";
           $update=$data['update'];
if($update==0){
$not_exists = Yii::$app->db->createCommand('SELECT item_id FROM whitebook_vendor_item_capacity_exception where exception_date='.$exception_date.' and trash!="Deleted"');
}else{
    $not_exists = Yii::$app->db->createCommand('SELECT item_id FROM whitebook_vendor_item_capacity_exception where exception_date='.$exception_date.' and exception_id!='.$update.' and trash!="Deleted"');

}
        $result = $not_exists->queryAll();
        $out1[]= array();
        $out2[]= array();
        foreach ($result as $r)
        {
            if(is_numeric($r['item_id']))
            {
                $out1[]= $r['item_id'];
                foreach($item_id as $i)
                {
                    if($i==$r['item_id']){
                        echo $already=2;die;
                    }

                }
            }
            if(!is_numeric($r['item_id']))
            {
             $out2[]= explode(',',$r['item_id']);
            }
        }
        $p=array();
        foreach($out2 as $id)
        {
            foreach($id as $key)
            $p[] = $key;
        }

        foreach($item_id as $i)
        {

if (in_array($i, $p)) {
   echo $already=2;die;
}
}
}
    /**
     * Finds the Vendoritemcapacityexception model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Vendoritemcapacityexception the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Vendoritemcapacityexception::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
