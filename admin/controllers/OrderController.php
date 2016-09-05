<?php

namespace admin\controllers;

use Yii;
use common\models\Order;
use common\models\Suborder;
use common\models\OrderSearch;
use common\models\SuborderItemPurchase;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OrderController implements the CRUD actions for Order model.
 */
class OrderController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Order models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OrderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Order model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        $suborder = Suborder::find()
            ->where(['order_id' => $id])
            ->all();

        return $this->render('view', [
            'model' => $this->findModel($id),
            'suborder' => $suborder
        ]);
    }

    /**
     * Deletes an existing Order model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        Order::updateAll(['trash' => 'Deleted'], 'order_id = ' . $id);

        //delete suborder 
        Suborder::updateAll(['trash' => 'Deleted'], 'order_id = ' . $id);

        //delete items 
        SuborderItemPurchase::updateAll(['trash' => 'Deleted'], 'suborder_id IN (select suborder_id from whitebook_suborder WHERE order_id="'.$id.'")');

        return $this->redirect(['index']);
    }

    /**
     * Finds the Order model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Order the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Order::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
