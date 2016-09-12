<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use frontend\models\Vendoritem;
use frontend\models\Vendor;
use common\models\Featuregroupitem;
use frontend\models\Users;
use common\models\Events;
use common\models\Vendorlocation;
use common\models\Image;
use yii\helpers\Json;
use yii\helpers\Url;

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
        $model = Vendoritem::findOne(['slug'=>$slug,'item_status'=>'Active','item_approved'=>'Yes','trash' => 'Default']);

        if (empty($model)) {
            throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
        }

        $similarProductModel = Vendoritem::find()
            ->where([
                'vendor_id' => $model->vendor_id,
                'item_status' => 'Active',
                'item_approved' => 'Yes',
                'trash' => 'Default'
            ])
            ->andWhere(['<>','item_id', $model->item_id])
            ->all();

        $baselink = Yii::$app->homeUrl.Yii::getAlias('@vendor_images/').'no_image.jpg';

        if (!empty($model->images[0])) {
            $baselink = Yii::getAlias("@s3/vendor_item_images_530/") . $model->images[0]['image_path'];
        } else {
            $baselink = Yii::getAlias("@s3/vendor_item_images_530/") . 'no_image.jpg';
        }

        $vendr_area = Vendorlocation::find()
            ->select(['{{%vendor_location}}.area_id','{{%location}}.*'])
            ->leftJoin('{{%location}}', '{{%vendor_location}}.area_id = {{%location}}.id')
            ->where(['{{%location}}.trash' => 'Default'])
            ->asArray()
            ->all();

        $title = 'Whitebook Application '.ucfirst($model->vendor->vendor_name);
        $url = urlencode(Yii::$app->homeUrl . $_SERVER['REQUEST_URI']);
        $summary = 'Whitebook Application '.ucfirst($model->vendor->vendor_name).' '.ucfirst($model['item_name']);

        $image = $baselink;

        \Yii::$app->view->registerMetaTag(['property' => 'og:title', 'content' => $title]);
        \Yii::$app->view->registerMetaTag(['property' => 'og:url', 'content' => $url]);
        \Yii::$app->view->registerMetaTag(['property' => 'og:image', 'content' => $baselink]);
        \Yii::$app->view->registerMetaTag(['property' => 'og:image:width', 'content' => '200']);
        \Yii::$app->view->registerMetaTag(['property' => 'og:image:height', 'content' => '200']);
        \Yii::$app->view->registerMetaTag(['property' => 'og:site_name', 'content' => $summary]);
        
        \Yii::$app->view->registerMetaTag(['
            property' => 'og:description', 
            'content' => strip_tags($model->item_description)
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

    /* BEGIN DELIVERY TIME SLOT -- VENDOR */
    public function actionGetdeliverytime()
    {
		$vendor_timeslot = Deliverytimeslot::find()
		->select(['timeslot_id','timeslot_start_time','timeslot_end_time'])
		->where(['vendor_id' => $model['vendor_id']])
		->asArray()->all();
    }
    /* END DELIVERY TIME SLOT -- VENDOR */
    public function actionGetdeliverytimeslot()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $string = $data['sel_date'];
            $timestamp = strtotime($string);

		$vendor_timeslot = Deliverytimeslot::find()
		->select(['timeslot_id','timeslot_start_time','timeslot_end_time'])
		->where(['vendor_id' => $model['vendor_id']])
		->andwhere(['timeslot_day' => date("l", $timestamp)])
		->asArray()->all();

            foreach ($vendor_timeslot as $key => $value) {
                echo '<option value="'.$key['timeslot_id'].'">'.$value['timeslot_start_time'].' - '.$value['timeslot_end_time'].'</option>';
            }
        }
    }
}
