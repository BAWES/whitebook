<?php

namespace backend\controllers;

use Yii;
use common\models\Order;
use common\models\OrderStatus;
use common\models\Suborder;
use common\models\SubOrderSearch;
use common\models\SuborderItemPurchase;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use kartik\mpdf\Pdf;

/**
 * OrderController implements the CRUD actions for Order model.
 */
class SubOrderController extends Controller
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
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [//allow authenticated users only
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ]
        ];
    }

    /**
     * Lists all Order models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SubOrderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $status = OrderStatus::find()->all();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'status' => $status
        ]);
    }

    /**
     * Displays a single Order model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        $status = OrderStatus::find()->all();

        return $this->render('view', [
            'model' => $this->findModel($id),
            'status' => $status
        ]);
    }

    public function actionInvoice($id)
    {
        $suborder = Suborder::find()
            ->where(['suborder_id' => $id])
            ->one();

        $status = OrderStatus::find()->all();

        $this->layout = 'pdf';

        $content = $this->render('invoice', [
            'model' => $this->findModel($id),
            'suborder' => $suborder,
            'status' => $status
        ]);

        $pdf = new Pdf([
            // A4 paper format
            'format' => Pdf::FORMAT_A4, 
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT, 
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER, 
            // your html content input
            'content' => $content,  
            // any css to be embedded if required
            'cssInline' => '.kv-heading-1{font-size:38px}', 
             // set mPDF properties on the fly
            'options' => [],//['title' => 'Order #'.$id],
             // call mPDF methods on the fly
            'methods' => [ 
                'SetHeader'=>['Order #'.$id], 
                'SetFooter'=>['{PAGENO}'],
            ]
        ]);    

        return $pdf->render();     
    }
    
    public function actionOrderStatus()
    {
        $suborder = Suborder::findOne(Yii::$app->request->post('suborder_id'));
        $suborder->status_id = Yii::$app->request->post('status_id');
        $suborder->save();

        Order::sendStatusEmail($suborder->suborder_id, $suborder->status_id);
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
        if (($model = Suborder::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
