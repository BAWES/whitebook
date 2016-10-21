<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\Json;
use yii\helpers\Url;
use frontend\models\VendorItem;
use frontend\models\Vendor;
use frontend\models\Users;
use common\models\FeatureGroupItem;
use common\models\DeliveryTimeSlot;
use common\models\Events;
use common\models\VendorLocation;
use common\models\Image;

/**
* Site controller.
*/
class ProductController extends BaseController
{
    /**
    * {@inheritdoc}
    */
    public function init()
    {
        parent::init();
    }

    /**
    *
    */
    public function actionProduct($slug)
    {
        $model = VendorItem::findOne(['slug'=>$slug,'item_status'=>'Active','item_approved'=>'Yes','trash' => 'Default']);

        if (empty($model)) {
            throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
        }

        $similarProductModel = VendorItem::more_from_vendor($model);

        $baselink = Yii::$app->homeUrl.Yii::getAlias('@vendor_images/').'no_image.jpg';

        if (!empty($model->images[0])) {
            $baselink = Yii::getAlias("@s3/vendor_item_images_530/") . $model->images[0]['image_path'];
        } else {
            throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
            //$baselink = Yii::getAlias("@s3/vendor_item_images_530/") . 'no_image.jpg';
        }

        $vendr_area = VendorLocation::find()
            ->select(['{{%vendor_location}}.area_id','{{%location}}.*'])
            ->leftJoin('{{%location}}', '{{%vendor_location}}.area_id = {{%location}}.id')
            ->where(['{{%location}}.trash' => 'Default'])
            ->asArray()
            ->all();

        $title = Yii::$app->name. ' - '.ucfirst($model->vendor->vendor_name);
        
        $url = Url::toRoute(["product/product", 'slug' => $model->slug], true);

        $summary = Yii::$app->name.' - '.ucfirst($model['item_name']).' from '.ucfirst($model->vendor->vendor_name);

        $image = $baselink;

        \Yii::$app->view->registerMetaTag(['property' => 'og:title', 'content' => $title]);
        \Yii::$app->view->registerMetaTag(['property' => 'fb:app_id', 'content' => 157333484721518]);
        \Yii::$app->view->registerMetaTag(['property' => 'og:url', 'content' => $url]);
        \Yii::$app->view->registerMetaTag(['property' => 'og:image', 'content' => $baselink]);
        \Yii::$app->view->registerMetaTag(['property' => 'og:image:width', 'content' => '200']);
        \Yii::$app->view->registerMetaTag(['property' => 'og:image:height', 'content' => '200']);
        \Yii::$app->view->registerMetaTag(['property' => 'og:site_name', 'content' => $summary]);
        \Yii::$app->view->registerMetaTag(['property' => 'og:site_name', 'content' => Yii::$app->name.' - ' . ucfirst($model->item_name) .' from '. ucfirst($model->vendor->vendor_name)]);
        \Yii::$app->view->registerMetaTag(['property' => 'og:description', 'content' => trim(strip_tags($model->item_description))]);
        \Yii::$app->view->registerMetaTag(['property' => 'twitter:card', 'content' => 'summary_large_image']);

        \Yii::$app->view->registerMetaTag([
            'property' => 'og:description', 
            'content' => trim(strip_tags($model->item_description))
        ]);

        if (Yii::$app->user->isGuest) {

            return $this->render('product_detail', [
                'model' => $model,
                'similiar_item' => $similarProductModel,
                'vendor_area' => $vendr_area
            ]);
        } else {

            $user = new Users();
            $customer_events_list = $user->get_customer_wishlist_details(Yii::$app->user->identity->customer_id);
            return $this->render('product_detail', [
                'model' => $model,
                'similiar_item' => $similarProductModel,
                'customer_events_list' => $customer_events_list,
                'vendor_area' => $vendr_area
            ]);
        }
    }

    public function actionEvent_slider()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        } else {
            return $this->renderPartial('events_slider');
        }
    }

    public function actionEventdetails()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $edit_eventinfo = Events::find()->where(['event_id' => $data['event_id']])->asArray()->all();
            return $this->renderPartial('edit_event', array('edit_eventinfo' => $edit_eventinfo));
        }
    }
}
