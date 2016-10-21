<?php

namespace admin\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use admin\models\AuthItem;
use common\models\VendorItemSearch;

/**
* VendorItemPendingController implements the CRUD actions for Vendoritem model.
*/
class VendorItemPendingController extends Controller
{

    public function init()
    {
        parent::init();
        if (Yii::$app->user->isGuest) { // chekck the admin logged in
            $url = Yii::$app->urlManager->createUrl(['admin/site/login']);
            Yii::$app->getResponse()->redirect($url);
        }
    }

    /**
    * Lists all Vendoritem models.
    *
    * @return mixed
    */
    public function actionIndex()
    {
        $access = AuthItem::AuthitemCheck('4', '23');
        
        if (yii::$app->user->can($access)) {

            $searchModel = new VendorItemSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams, 'Pending');

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);

        } else {
            Yii::$app->session->setFlash('danger', 'Your are not allowed to access the page!');
            return $this->redirect(['site/index']);
        }
    }    
}
