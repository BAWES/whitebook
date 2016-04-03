<?php

namespace backend\modules\vendor\controllers;

use Yii;
use backend\models\Themes;
use backend\models\themesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * ThemesController implements the CRUD actions for Themes model.
 */
class ThemesController extends Controller
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
                   [  
						'actions' => [''],             
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [   
						'actions' => ['create', 'update','index', 'view','delete'],                     
                        'allow' => true,
                        'roles' => ['@'],
                    ],
               ],
           ],
        ];
    }

    /**
     * Lists all Themes models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new themesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Themes model.
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
     * Creates a new Themes model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Themes();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			
		echo Yii::$app->session->setFlash('success', "Theme Added successfully!");
        return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Themes model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
		echo Yii::$app->session->setFlash('success', "Theme Updated successfully!");
        return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Themes model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
     
     
         public function actionDelete($id)
    {
		$model = $this->findModel($id);
		$model->trash = 'Deleted';
		$model->load(Yii::$app->request->post());
		$model->save();  
		echo Yii::$app->session->setFlash('success', "Theme Deleted successfully!");
        return $this->redirect(['index']);
    }

    /**
     * Finds the Themes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Themes the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Themes::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
        public function actionBlock()
    {		
		if(Yii::$app->request->isAjax)
		$data = Yii::$app->request->post();		
		$status = ($data['status'] == 'Active' ? 'Deactive' : 'Active'); 	
		$command = \Yii::$app->db->createCommand('UPDATE whitebook_theme SET theme_status="'.$status.'" WHERE theme_id='.$data['id']);
		$command->execute();
		if($status == 'Active')
			{
				echo Yii::$app->session->setFlash('success', "Theme status updated!");
				return \Yii::$app->urlManagerBackEnd->createAbsoluteUrl('themes/default/img/active.png');
		 	}
			else
			{
					echo Yii::$app->session->setFlash('success', "Theme status updated!");
					return \Yii::$app->urlManagerBackEnd->createAbsoluteUrl('themes/default/img/inactive.png');
			}
		
	}
}
