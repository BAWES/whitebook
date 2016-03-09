<?php

namespace backend\modules\admin\controllers;

use Yii;
use backend\models\Activitylog;
use backend\models\Authitem;
use backend\models\ActivitylogSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * ActivitylogController implements the CRUD actions for Activitylog model.
 */
class ActivitylogController extends Controller
{
	public function init()
    {		
        parent::init();	
        if(Yii::$app->user->isGuest){ // chekck the admin logged in
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
                //    'delete' => ['post'],
                ],
            ],
               'access' => [
               'class' => AccessControl::className(),
               'rules' => [
                   [
                       'actions' => [],             
                       'allow' => true,
                       'roles' =>['?'],
                   ],
                   [             
                       'actions'=>['index','delete'],          
                       'allow' => true,
                       'roles' => ['@'],
                   ],
               ],
           ],
        ];
    }

    /**
     * Lists all Activitylog models.
     * @return mixed
     */
    public function actionIndex()
    {	
		
		$access=Authitem::AuthitemCheck('4','28');
		if(yii::$app->user->can($access)){
        $searchModel = new ActivitylogSearch();        
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
	else
	{
		echo Yii::$app->session->setFlash('danger', "Your are not allowed to access the page!");
		return $this->redirect(['site/index']);
	}
	}

    public function actionDelete($id)
    {
		$access=Authitem::AuthitemCheck('3','28');
		if(yii::$app->user->can($access)){
        $this->findModel($id)->delete();
		echo Yii::$app->session->setFlash('success', "Activity log deleted successfully!");
        return $this->redirect(['index']);
       } else
	{
		echo Yii::$app->session->setFlash('danger', "Your are not allowed to access the page!");
		return $this->redirect(['site/index']);
	}
    }

    /**
     * Finds the Activitylog model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Activitylog the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Activitylog::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
