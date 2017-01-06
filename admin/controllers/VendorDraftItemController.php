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
use common\models\VendorItemPricing;
use common\models\VendorItemToCategory;
use common\models\VendorDraftImage;
use common\models\VendorDraftItemToCategory;
use common\models\VendorDraftItemPricing;

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

        $imagedata = VendorDraftImage::find()
            ->where('item_id = :id', [':id' => $model->item_id])
            ->orderby(['vendorimage_sort_order' => SORT_ASC])
            ->all();

        $categories = VendorDraftItemToCategory::find()
            ->with('category')
            ->Where(['item_id' => $model->item_id])
            ->all();

        $price_table = VendorDraftItemPricing::find()
            ->where(['item_id' => $model->item_id])
            ->all();

        return $this->render('view', [
            'model' => $model,
            'dataProvider1' => $dataProvider1, 
            'imagedata' => $imagedata,
            'categories' => $categories,
            'price_table' => $price_table
        ]);
    }

    public function actionApprove($id){

        $draft = VendorDraftItem::findOne($id);

        $attributes = $draft->attributes;

        //unset sort from draft to keep sort from vendor item list 
        unset($attributes['sort']);
        
        //copy to item from draft 
        $item = VendorItem::findOne($draft->item_id);
        $item->attributes = $attributes;
        $item->item_approved = 'Yes';
        $item->hide_from_admin = 0;
        $item->save();

        //remove from draft 
        $draft->delete();

        //remove old price table data 

        VendorItemPricing::deleteAll(['item_id' => $item->item_id]);

        //add new price table data 

        $pricing = VendorDraftItemPricing::findAll(['item_id' => $item->item_id]);

        foreach ($pricing as $key => $value) 
        {
            $vip = new VendorItemPricing;
            $vip->attributes = $value->attributes;
            $vip->pricing_quantity_ordered = 0;
            $vip->trash = 'Default';
            $vip->save();
        }
        
        //remove old categories 

        VendorItemToCategory::deleteAll(['item_id' => $item->item_id]);

        //add new categories 
        
        $categories = VendorDraftItemToCategory::findAll(['item_id' => $item->item_id]);

        foreach ($categories as $key => $value) 
        {
            $vic = new VendorItemToCategory;
            $vic->attributes = $value->attributes;
            $vic->save();
        }

        //remove old images 

        Image::deleteAll(['item_id' => $item->item_id]);

        //add new images 

        $images = VendorDraftImage::findAll(['item_id' => $item->item_id]);

        foreach ($images as $key => $value) 
        {
            $image = new Image;
            $image->attributes = $value->attributes;
            $image->item_id = $value->item_id;
            $image->image_user_type = 'vendor';
            $image->module_type = 'vendor_item';
            $image->image_status = 1;
            $image->trash = 'Default';
            $image->save();
        }

        Yii::$app->session->setFlash('success', 'Item approved successfully!');

        return $this->redirect(['index']);
    }
}
