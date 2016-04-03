<?php

namespace admin\controllers;

use Yii;
use common\models\Vendoritemquestionguide;
use common\models\Vendoritemquestion;
use common\models\VendoritemquestionguideSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;
use common\models\Image;
/**
 * VendoritemquestionguideController implements the CRUD actions for Vendoritemquestionguide model.
 */
class VendoritemquestionguideController extends Controller
{
	public function init()
    {		
        parent::init();	
        if(Yii::$app->user->isGuest){ // chekck the admin logged in
			//$this->redirect('login');
			$url =  Yii::$app->urlManager->createUrl(['admin/site/login']);
				Yii::$app->getResponse()->redirect($url);
		}
       
    }
    
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Vendoritemquestionguide models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new VendoritemquestionguideSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Vendoritemquestionguide model.
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
     * Creates a new Vendoritemquestionguide model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Vendoritemquestionguide();
        $model1 = new Image();
        $base = Yii::$app->basePath;
		$len = rand(1,1000);
        $question = Vendoritemquestion::find()->select(['question_id','question_text'])->all();    
        $questions = ArrayHelper::map($question,'question_id','question_text');   
        $model->question_id = 0;
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
			if($model->save())
			{				
			$file = UploadedFile::getInstances($model, 'guide_image_id');   
			foreach ($file as $files) {
				     $files->saveAs($base.'/web/uploads/vendor_images/' . $files->baseName . '_' . $len .'.' . $files->extension);
                     $image_path = $files->baseName . '_' . $len .'.' . $files->extension;
                } 						
			$image = Yii::$app->db->createCommand()
							->insert('whitebook_image', [
									'image_path' => $image_path,
									'item_id' => $model->question_id,
									'image_user_id' => 1,
									'module_type' =>'guides'])
							->execute();               
			
				return $this->redirect(['index']);
			}
        } else {
            return $this->render('create', [
                'model' => $model,'questions' => $questions,
            ]);
        }
    }

    /**
     * Updates an existing Vendoritemquestionguide model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
             return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,'questions' => $questions,
            ]);
        }
    }

    /**
     * Deletes an existing Vendoritemquestionguide model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Vendoritemquestionguide model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Vendoritemquestionguide the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Vendoritemquestionguide::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
