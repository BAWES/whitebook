<?php

namespace admin\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use admin\models\AccessControlList;
use admin\models\VendorDraftItemSearch;
use common\models\VendorDraftItem;
use common\models\VendorItem;

class VendorDraftItemController extends Controller
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
                 //   'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'allow' => AccessControlList::can()
                    ],
                ],
            ],            
        ];
    }

    /**
    * Lists all VendorDraftItem models.
    *
    * @return mixed
    */
    public function actionIndex()
    {
        $searchModel = new VendorDraftItemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }    

    public function actionApprove($id){

        $draft = VendorDraftItem::findOne($id);

        $item = VendorItem::findOne($draft->item_id);
        $item->attributes = $draft->attributes;
        $item->item_approved = 'Yes';
        $item->save();

        $draft->delete();

        Yii::$app->session->setFlash('success', 'Item approved successfully!');
        return $this->redirect(['index']);
    }
}
