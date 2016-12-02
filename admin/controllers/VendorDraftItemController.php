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
        $searchModel->is_ready = 1;

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }    

    public function actionApprove($id){

        $draft = VendorDraftItem::findOne($id);

        $attributes = $draft->attributes;

        //unset sort and item_status from draft to keep sort and item_status from vendor item list 
        unset($attributes['item_status']);
        unset($attributes['sort']);
        
        //copy to item from draft 
        $item = VendorItem::findOne($draft->item_id);
        $item->attributes = $attributes;
        $item->item_approved = 'Yes';
        $item->save();

        //remove from draft 
        $draft->delete();

        Yii::$app->session->setFlash('success', 'Item approved successfully!');
        return $this->redirect(['index']);
    }
}
