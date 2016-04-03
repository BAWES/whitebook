<?php
namespace backend\modules\vendor\controllers;
use yii\web\Session;
use Yii;
use common\models\Vendor;
use common\models\Vendoraddress;
use common\models\VendoraddressSearch;
use common\models\VendorSearch;
use common\models\Location;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Security;
/**
 * VendorController implements the CRUD actions for Vendor model.
 */
class vendoraddressController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                 //   'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Vendor models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new VendoraddressSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		return $this->render('index',[
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Vendor model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Vendoraddress();	
       // $model->scenario = 'register';
		$area = Location::loadlocation();
		//print_r ($area);die;
        if ($model->load(Yii::$app->request->post())&&($model->validate())) {
			$model->vendor_id=Yii::$app->user->identity->id;
			$model->save();
			echo Yii::$app->session->setFlash('success', "Vendor address details created successfully!");
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,'area' => $area,
            ]);
        }
    }

    public function actionUpdate($id)
    {
		$model = $this->findModel($id);	
		$area = Location::loadlocation();
		//print_r ($area);die;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			//print_r ($_POST);die;
			echo Yii::$app->session->setFlash('success', "Vendor address details updated successfully!");
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,'area' => $area,
            ]);
        }
    }

    /**
     * Deletes an existing Vendor model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
		echo Yii::$app->session->setFlash('success', "Vendor address details deleted successfully!");
        return $this->redirect(['index']);
    }

    /**
     * Finds the Vendor model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Vendor the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Vendoraddress::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
        public function actionLoadcategory()
    {	
		  if(Yii::$app->request->isAjax)
		  $data = Yii::$app->request->post();		 		
		  $categoryid = Vendor::find()->select('category_id')->where(['vendor_id' => $data['id']])->one();
		  $k=$categoryid->category_id;
		  $category = Category::find()->select('category_id,category_name')->where(['category_id' => $k])->all();
		  echo  '<option value="">Select...</option>';
		 foreach($category as $key=>$val)
		 {
			echo  '<option value="'.$val['category_id'].'">'.$val['category_name'].'</option>';
		 }  
	}
    public function actionLoadsubcategory()
    {	
		  if(Yii::$app->request->isAjax)
		  $data = Yii::$app->request->post();		 	
		  $subcategory = Category::find()->select('category_id,category_name')->where(['parent_category_id' => $data['id']])->all();
		  echo  '<option value="">Select...</option>';
		 foreach($subcategory as $key=>$val)
		 {
			echo  '<option value="'.$val['category_id'].'">'.$val['category_name'].'</option>';
		 }  
	}
		public function actionBlock()
    {			
		if(Yii::$app->request->isAjax)
		$data = Yii::$app->request->post();		
		$status = ($data['status'] == 'Active' ? 'Deactive' : 'Active'); 	
		$command = \Yii::$app->db->createCommand('UPDATE whitebook_vendor SET vendor_status="'.$status.'" WHERE vendor_id='.$data['id']);
		$command->execute();

		if($status == 'Active')
			{
			echo Yii::$app->session->setFlash('success', "Vendor Address Status Updated!");
			return \Yii::$app->params['appImageUrl'].'active.png';
		 	}
			else
			{
			echo Yii::$app->session->setFlash('success', "Vendor Address Status Updated!");
			return \Yii::$app->params['appImageUrl'].'inactive.png';
			}
	}
	
		
    
}
