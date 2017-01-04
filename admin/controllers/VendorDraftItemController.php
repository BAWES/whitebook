<?php

namespace admin\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use admin\models\AccessControlList;
use admin\models\VendorDraftItemSearch;
use common\models\VendorDraftItem;
use common\models\VendorItem;
use common\models\PriorityItem;
use admin\models\Image;
use common\models\VendorItemToCategory;

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

    /**
    * Displays a single VendorItem model.
    *
    * @param string $id
    *
    * @return mixed
    */
    public function actionView($id)
    {
        $model = VendorDraftItem::findOne($id);

        $dataProvider1 = PriorityItem::find()
            ->select(['priority_level','priority_start_date','priority_end_date'])
            ->where(new \yii\db\Expression('FIND_IN_SET(:item_id, item_id)'))
            ->addParams([':item_id' => $model->item_id])
            ->all();

        $imagedata = Image::find()
            ->where('item_id = :id', [':id' => $model->item_id])
            ->orderby(['vendorimage_sort_order' => SORT_ASC])
            ->all();

        $categories = VendorItemToCategory::find()
            ->with('category')
            ->Where(['item_id' => $model->item_id])
            ->all();

        return $this->render('view', [
            'model' => $model,
            'dataProvider1' => $dataProvider1, 
            'imagedata' => $imagedata,
            'categories' => $categories
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
        $item->hide_from_admin = 0;
        $item->save();

        //remove from draft 
        $draft->delete();

        Yii::$app->session->setFlash('success', 'Item approved successfully!');
        return $this->redirect(['index']);
    }
}
