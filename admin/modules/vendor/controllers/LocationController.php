<?php
namespace backend\modules\vendor\controllers;

use Yii;
use common\models\Location;
use common\models\City;
use common\models\Authitem;
use common\models\LocationSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Country;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;

/**
 * LocationController implements the CRUD actions for Location model.
 */
class LocationController extends Controller
{	
	
    public function behaviors()
    {
        return [
			'access' => [
                'class' => AccessControl::className(),
               'rules' => [
                   [
                       'actions' => [],             
                       'allow' => true,
                       'roles' =>['?'],
                   ],
                   [             
                       'actions'=>['create', 'update','index', 'view','delete','block','city','area'],          
                       'allow' => true,
                       'roles' => ['@'],
                   ],
               ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                 //   'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Location models.
     * @return mixed
     */
    public function actionIndex()
    {
		
        $searchModel = new LocationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Location model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Location model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Location();	
		
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            echo Yii::$app->session->setFlash('success', "Area info created successfully!");
            return $this->redirect(['index']);
        } else {
			$countries=Country::find()->all();			
			$country=ArrayHelper::map($countries,'country_id','country_name');
            return $this->render('create', [
                'model' => $model,'country' => $country,            
            ]);
        }
    }

    /**
     * Updates an existing Location model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			echo Yii::$app->session->setFlash('success', "Area info updated successfully!");
            return $this->redirect(['index']);
             } else {
			$countries=Country::find()->all();			
			$country=ArrayHelper::map($countries,'country_id','country_name');
			$cities=City::find()->all();			
			$city=ArrayHelper::map($cities,'city_id','city_name');			
            return $this->render('update', [
                'model' => $model,'city' => $city, 'country' => $country, 
            ]);
        }
    }

    /**
     * Deletes an existing Location model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
   
    }

    /**
     * Finds the Location model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Location the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Location::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    public function actionCity()
    {
		if(Yii::$app->request->isAjax)
		 $data = Yii::$app->request->post();		 		
		 $city = City::find()->select('city_id,city_name')->where(['country_id' => $data['country_id']])->all();
		 echo  '<option value="">Select</option>';
		 foreach($city as $key=>$val)
		 {
			echo  '<option value="'.$val['city_id'].'">'.$val['city_name'].'</option>';
		 }		 
	}
		
	public function actionBlock()
    {			
		if(Yii::$app->request->isAjax)
		$data = Yii::$app->request->post();		
		$status = ($data['status'] == 'Active' ? 'Deactive' : 'Active'); 	
		$command = \Yii::$app->db->createCommand('UPDATE whitebook_location SET status="'.$status.'" WHERE id='.$data['lid']);
		$command->execute();
		if($status == 'Active')
			{
			echo Yii::$app->session->setFlash('success', "Area status updated!");
			return \Yii::$app->params['appImageUrl'].'active.png';
		 	}
			else
			{
			echo Yii::$app->session->setFlash('success', "Area status updated!");
			return \Yii::$app->params['appImageUrl'].'inactive.png';
			}
	}
	public function actionArea()
    {	
		if(Yii::$app->request->isAjax)
		 $data = Yii::$app->request->post();		 		
		 $location = Location::find()->select('id,location')->where(['city_id' => $data['city_id']])->all();
		 echo  '<option value="">Select</option>';
		 foreach($location as $key=>$val)
		 {
			echo  '<option value="'.$val['id'].'">'.$val['location'].'</option>';
		 }
	}
}
