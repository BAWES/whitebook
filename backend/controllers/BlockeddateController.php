<?php
namespace backend\controllers;

use Yii;
use common\models\Blockeddate;
use common\models\Vendor;
use common\models\BlockeddateSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\helpers\Setdateformat;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * BlockeddateController implements the CRUD actions for Blockeddate model.
 */
class BlockeddateController extends Controller
{
   /**
     * Lists all Blockeddate models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BlockeddateSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Blockeddate model.
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
     * Creates a new Blockeddate model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Blockeddate();
        $model->scenario = 'insert';
        $model->vendor_id = Vendor::getVendor('vendor_id');
        $blockdays=Vendor::Vendorblockeddays($model->vendor_id);
        $block=($blockdays['blocked_days']);
        if($model->load(Yii::$app->request->post()) && $model->validate())
        {
        $model->block_date = Setdateformat::convert($model->block_date);
		$model->save();
            echo Yii::$app->session->setFlash('success', "Blocked date created successfully!");
			return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,'block'=>$block
            ]);
        }
    }

    public function actionCreateweek()
    {
        $model = new Blockeddate();
        $model->vendor_id = Vendor::getVendor('vendor_id');
        $blockdays=Vendor::Vendorblockeddays($model->vendor_id);
        $block=($blockdays['blocked_days']);
        if($model->load(Yii::$app->request->post()))
        {
        if($model->sunday=='7')
        {  $model->sunday='7'.',';}else{$model->sunday=null;}

         if($model->monday!=1)
        { $model->monday=null;}
        else
        {$model->monday='1'.',';}
        if($model->tuesday!=2)
        { $model->tuesday=null;}
        else
        {$model->tuesday='2'.',';}
        if($model->wednesday!=3)
        { $model->wednesday=null;}
        else
        {$model->wednesday='3'.',';}
        if($model->thursday!=4)
        { $model->thursday=null;}
        else
        {$model->thursday='4'.',';}
        if($model->friday!=5)
        { $model->friday=null;}
        else
        {$model->friday='5'.',';}
        if($model->saturday!=6)
        { $model->saturday=null;}
        $days=$model->sunday.$model->monday.$model->tuesday.$model->wednesday.$model->thursday.$model->friday.$model->saturday;
        $sql='UPDATE whitebook_vendor SET blocked_days="'.$days.'" WHERE vendor_id='.$model->vendor_id;
        $command = \Yii::$app->db->createCommand($sql)->execute();
           echo Yii::$app->session->setFlash('success', "Blocked weekday created successfully!");
            return $this->redirect(['createweek']);
        } else {
            return $this->render('blocked_week_form', [
                'model' => $model,'block'=>$block
            ]);
        }
    }



    /**
     * Updates an existing Blockeddate model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		 $model->scenario = 'update';
        $blockdays=Vendor::Vendorblockeddays($model->vendor_id);
        $block=($blockdays['blocked_days']);
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
			$model->block_date = Setdateformat::convert($model->block_date);
			$model->save();
            echo Yii::$app->session->setFlash('success', "Blocked date updated successfully!");
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,'block'=>$block,
            ]);
        }
    }

    /**
     * Deletes an existing Blockeddate model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
		echo Yii::$app->session->setFlash('success', "Blocked date deleted successfully!");
        return $this->redirect(['index']);
    }

    /**
     * Finds the Blockeddate model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Blockeddate the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Blockeddate::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionBlock()
    {
	   if(Yii::$app->request->isAjax)
	   {
	    $data = Yii::$app->request->post();
	    $model = new Blockeddate();
	    $model = Vendor::getVendor('vendor_id');

        } else {
		return $this->render('dateblocked');
	   }
	}


}
