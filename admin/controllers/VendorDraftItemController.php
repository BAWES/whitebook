<?php

namespace admin\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\Url;
use yii\filters\VerbFilter;
use admin\models\Image;
use admin\models\AccessControlList;
use admin\models\VendorDraftItemSearch;
use common\models\Vendor;
use common\models\VendorItem;
use common\models\PriorityItem;
use common\models\VendorDraftItem;
use common\models\VendorItemPricing;
use common\models\VendorItemToCategory;
use common\models\VendorDraftImage;
use common\models\VendorItemMenu;
use common\models\VendorItemMenuItem;
use common\models\VendorDraftItemMenu;
use common\models\VendorDraftItemPricing;
use common\models\VendorOrderAlertEmails;
use common\models\VendorDraftItemToCategory;
use common\models\VendorDraftItemMenuItem;

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
        Yii::$app->session->setFlash('info', 'Fields mark with (*) are modified.');

        $model = VendorDraftItem::findOne($id);

        //check if have change price table 
        $is_price_table_changed = VendorDraftItem::is_price_table_changed($model->item_id);

        //check if have change images
        $is_images_changed = VendorDraftItem::is_images_changed($model->item_id);

        //check if have change category 
        $is_categories_changed = VendorDraftItem::is_categories_changed($model->item_id);

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

        $vendor_item = VendorItem::findOne($model->item_id);

        return $this->render('view', [
            'model' => $model,            
            'vendor_item' => $vendor_item,
            'dataProvider1' => $dataProvider1, 
            'imagedata' => $imagedata,
            'categories' => $categories,
            'price_table' => $price_table,
            'is_price_table_changed' => $is_price_table_changed,
            'is_images_changed' => $is_images_changed,
            'is_categories_changed' => $is_categories_changed
        ]);
    }

    public function actionApprove($id){

        $draft = VendorDraftItem::findOne($id);

        $attributes = $draft->attributes;

        //unset sort from draft to keep sort from vendor item list 
        unset($attributes['version']);
        unset($attributes['sort']);
        
        //copy to item from draft 
        $item = VendorItem::findOne($draft->item_id);
        $item->attributes = $attributes;
        $item->item_approved = 'Yes';
        $item->hide_from_admin = 0;
        $item->update();

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

        //remove old menu 

        $menues = VendorItemMenu::findAll(['item_id' => $item->item_id]);

        foreach ($menues as $key => $menu) {
            VendorItemMenuItem::deleteAll(['menu_id' => $menu->menu_id]);        
        }

        VendorItemMenu::deleteAll(['item_id' => $item->item_id]);

        //copy menu 

        $menues = VendorDraftItemMenu::findAll(['item_id' => $item->item_id]);

        foreach ($menues as $key => $menu) {

            //add menu 

            $m = new VendorItemMenu;
            $m->attributes = $menu->attributes;
            $m->save();

            //get all menu items 
            
            $items = VendorDraftItemMenuItem::findAll(['draft_menu_id' => $menu->draft_menu_id]);

            //add all items 

            foreach ($items as $key => $item) {
               
                $i = new VendorItemMenuItem;
                $i->attributes = $item->attributes;
                $i->menu_id = $m->menu_id;
                $i->save();
            }

            //remove all draft menu items 

            VendorDraftItemMenuItem::deleteAll(['draft_menu_id' => $menu->draft_menu_id]);
        }

        //remove draft related data 

        VendorDraftItemPricing::deleteAll(['item_id' => $item->item_id]);
        VendorDraftImage::deleteAll(['item_id' => $item->item_id]);
        VendorDraftItemMenu::deleteAll(['item_id' => $item->item_id]);
        VendorDraftItemToCategory::deleteAll(['item_id' => $item->item_id]);

        //remove from draft 
        
        $draft->delete();

        Yii::$app->session->setFlash('success', 'Item approved successfully!');

        return $this->redirect(['index']);
    }

    public function actionReject()
    {
        $draft_item_id = Yii::$app->request->post('draft_item_id');

        $reason = Yii::$app->request->post('reason'); 

        $model = VendorDraftItem::findOne(['draft_item_id' => $draft_item_id]);

        $vendor = Vendor::findOne($model->vendor_id);

        //send mail 
        Yii::$app->mailer->htmlLayout = 'layouts/empty';
        
        $mail = Yii::$app->mailer->compose("admin/item-reject",
            [
                "reason" => $reason,
                "model" => $model,
                "vendor" => $vendor,
                "image_1" => Url::to("@web/twb-logo-trans.png", true),
                "image_2" => Url::to("@web/twb-logo-horiz-white.png", true)
            ])
            ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name ])
            ->setSubject('Item rejected');

        //to contact email  
        $mail 
            ->setTo($vendor->vendor_contact_email)
            ->send();

        //send to all notification mails 
        $vendor_alert_emails = VendorOrderAlertEmails::findAll(['vendor_id' => $vendor->vendor_id]);

        foreach ($vendor_alert_emails as $key => $value) {
            $mail    
                ->setTo($value->email_address)
                ->send();
        }

        //hide draft from admin 
        $model->is_ready = 0;
        $model->save();

        Yii::$app->session->setFlash('success', 'Item rejected and vendor notified by email!');

        Yii::$app->response->format = 'json';
        
        return [
            'location' => Url::to(['vendor-draft-item/index'])
        ];
    }
}
