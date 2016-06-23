<?php

namespace backend\controllers;

use Yii;
use backend\models\Vendoritemcapacityexception;
use backend\models\VendoritemcapacityexceptionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
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

        $startdate=Vendoritemcapacityexception::find()->select('exception_date')
		->where(['trash'=>'Default'])		
		->orderby(['exception_date'=>SORT_ASC])
		->asArray()
		->all();
	   $startdate = date('Y-m-d', strtotime($startdate[0]['exception_date']));


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

    $exist_date=Vendoritemcapacityexception::find()->select('exception_date')
		->where(['created_by'=>$vendor_id])
		->asArray()
		->all();
	
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
			 $model->exception_date = date('Y-m-d', strtotime($model->exception_date));
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
		$exist_date=Vendoritemcapacityexception::find()->select('exception_date')
		->where(['created_by'=>$vendor_id])
		->asArray()
		->all();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
			$item = Yii::$app->request->post('Vendoritemcapacityexception');
			 $model->item_id = implode(',',$model->item_id);
			 
			 $model->exception_date =Yii::$app->formatter->asDate($model->exception_date, 'php:Y-m-d');
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
	$not_exists=Vendoritemcapacityexception::find()->select('item_id')
	->where(['exception_date'=>$exception_date])
	->andwhere(['trash'=>'Default'])
	->asArray()
	->all();
}else{
	$not_exists=Vendoritemcapacityexception::find()->select('item_id')
	->where(['exception_date'=>$exception_date])
	->andwhere(['!=','exception_id',$update])
	->andwhere(['trash'=>'Default'])
	->asArray()
	->all();
}
        $out1[]= array();
        $out2[]= array();
        foreach ($not_exists as $r)
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
