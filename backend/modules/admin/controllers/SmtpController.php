<?php
namespace backend\modules\admin\controllers;

use Yii;
use app\models\Smtp;
use app\models\SmtpSearch;
use yii\web\Controller;
use backend\models\Authitem;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * SmtpController implements the CRUD actions for Smtp model.
 */
class SmtpController extends Controller
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
			'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                   [
                       'actions' => [],             
                       'allow' => true,
                       'roles' =>['?'],
                   ],
                   [
                       'actions' => ['create', 'update','index', 'view','delete'],             
                       'allow' => true,
                       'roles' => ['@'],
                   ],
               ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Smtp models.
     * @return mixed
     */
    public function actionIndex()
    {
		$access=Authitem::AuthitemCheck('4','4');
		if(yii::$app->user->can($access)){
		$model = Smtp::find()->all();
		foreach($model as $key=>$val)
		{
			$first_id = $val['id'];
		}		      
        if(count($model) == 1){
			$this->redirect('smtp/update?id='.$first_id);
		}else{
			$this->redirect('smtp/create');
		}
   }
	else
	{
		echo Yii::$app->session->setFlash('danger', "Your are not allowed to access the page!");
		return $this->redirect(['site/index']);
	}	
    }

    /**
     * Displays a single Smtp model.
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
     * Creates a new Smtp model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		$access=Authitem::AuthitemCheck('1','4');
		if(yii::$app->user->can($access)){
        $model = new Smtp();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			echo Yii::$app->session->setFlash('success', "Application SMTP info created successfully!");
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
	else
	{
		echo Yii::$app->session->setFlash('danger', "Your are not allowed to access the page!");
		return $this->redirect(['site/index']);
	}	
    }

    /**
     * Updates an existing Smtp model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
		$access=Authitem::AuthitemCheck('2','4');
		if(yii::$app->user->can($access)){
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			echo Yii::$app->session->setFlash('success', "Application SMTP info updated successfully!");
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }
	else
	{
		echo Yii::$app->session->setFlash('danger', "Your are not allowed to access the page!");
		return $this->redirect(['site/index']);
	}	
    }

    /**
     * Deletes an existing Smtp model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
		$access=Authitem::AuthitemCheck('3','4');
		if(yii::$app->user->can($access)){
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
	else
	{
		echo Yii::$app->session->setFlash('danger', "Your are not allowed to access the page!");
		return $this->redirect(['site/index']);
	}	
    }

    /**
     * Finds the Smtp model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Smtp the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Smtp::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
