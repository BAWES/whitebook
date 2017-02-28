<?php

namespace frontend\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Response;
use yii\helpers\ArrayHelper;
use common\models\Order;
use common\models\Suborder;
use common\models\CustomerCart;
use common\models\OrderRequestStatus;
use common\models\SuborderItemPurchase;
use common\models\PaymentGateway;

class PaymentController extends BaseController
{
    private $errors = array();

    /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex($token)
    {
        \Yii::$app->view->title = Yii::$app->params['SITE_NAME'].' | Cart';
        \Yii::$app->view->registerMetaTag(['name' => 'description', 'content' => Yii::$app->params['META_DESCRIPTION']]);
        \Yii::$app->view->registerMetaTag(['name' => 'keywords', 'content' => Yii::$app->params['META_KEYWORD']]);

        $request = OrderRequestStatus::findOne(['request_token' => $token]);

        if(!$request) {
            throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
        }

        Yii::$app->session->set('request_id', $request->request_id);

        Yii::$app->session->set('order_id', $request->order_id);

        $items = SuborderItemPurchase::find()
            ->select('{{%vendor_item}}.item_name, {{%vendor_item}}.item_name_ar, {{%vendor_item}}.slug, {{%suborder_item_purchase}}.*')
            ->leftJoin('{{%vendor_item}}', '{{%vendor_item}}.item_id = {{%suborder_item_purchase}}.item_id')
            ->where(['suborder_id' => $request->suborder_id])
            ->asArray()
            ->all();

        $cod = PaymentGateway::find()->where(['code' => 'cod', 'status' => 1])->one();

        $tap = PaymentGateway::find()->where(['code' => 'tap', 'status' => 1])->one();

        return $this->render('index', [
            'items' => $items,
            'cod' => $cod,
            'tap' => $tap
        ]);
    }

    public function actionSuccess() {

        $order_id = Yii::$app->session->get('order_id');

        Yii::$app->session->remove('request_id');
        
        $order = Order::findOne($order_id);

        return $this->render('success', [
            'order_id' => $order_id,
            'order_page' => Url::to(['orders/view', 'order_uid' => $order->order_uid])
        ]);
    }
}



