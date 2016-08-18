<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\Json;
use yii\helpers\Url;
use frontend\models\Vendoritem;
use frontend\models\Vendor;
use frontend\models\Users;
use common\models\Featuregroupitem;
use common\models\Deliverytimeslot;
use common\models\Events;
use common\models\Vendorlocation;
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
    public function actionProduct($slug = '')
    {
        if ($slug != '') {
            $similiar = new Featuregroupitem();
            $similiar_item = $similiar->similiar_details();
            $item = new Vendoritem();
            $model = Vendoritem::findvendoritem($slug);
            $avlbl_stock = Vendoritem::find()->select(['item_amount_in_stock AS stock'])
			->where(['item_approved' => 'Yes'])
			->andwhere(['trash' => 'Default'])
			->andwhere(['item_status' => 'Active'])
			->andwhere(['type_id' => '2'])
			->andwhere(['item_for_sale' => 'Yes'])
			->andwhere(['>','item_amount_in_stock','0'])
			->asArray()
			->all();

            if (empty($model)) {
                throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
            }
            $vendor = new Vendor();
            $vendor_det = Vendor::vendorslug($model['vendor_id']);
            $social_vendor = $vendor->sociallist($model->vendor_id);

			$output = Image::find()->select(['image_path'])
			->where(['item_id' => $model['item_id']])
			//->andwhere(['module_type' => 'vendor_item'])
			->orderby(['vendorimage_sort_order'=>SORT_ASC])
			->asArray()
			->all();

            $baselink = Yii::$app->homeUrl.Yii::getAlias('@vendor_images/').'no_image.jpg';
            foreach ($output as $out) {
                if ($out) {
                    $imglink = Yii::getAlias('@vendor_images/').$out['image_path'];
                    $baselink = Yii::$app->homeUrl.Yii::getAlias('@vendor_images/').$out['image_path'];
                } else {
                    $imglink = Yii::getAlias('@vendor_images/').'no_image.jpg';
                    $baselink = Yii::$app->homeUrl.Yii::getAlias('@vendor_images/').'no_image.jpg';
                }
            }

            /* BEGIN DELIVERY AREAS --VENDOR */

			$vendr_area = Vendorlocation::find()
			->select(['{{%vendor_location}}.area_id','{{%location}}.*'])
			->leftJoin('{{%location}}', '{{%vendor_location}}.area_id = {{%location}}.id')
			->where(['{{%location}}.trash' => 'Default'])
			->asArray()
			->all();
            /* END DELIVERY AREAS --VENDOR */

            $title = 'Whitebook Application '.ucfirst($vendor_det['vendor_name']);
            $url = urlencode(Yii::$app->homeUrl . $_SERVER['REQUEST_URI']);
            $summary = 'Whitebook Application '.ucfirst($vendor_det['vendor_name']).' '.ucfirst($model['item_name']);
            
            $image = $baselink;
            
            \Yii::$app->view->registerMetaTag(['property' => 'og:title', 'content' => $title]);
            \Yii::$app->view->registerMetaTag(['property' => 'og:url', 'content' => $url]);
            \Yii::$app->view->registerMetaTag(['property' => 'og:image', 'content' => $baselink]);
            \Yii::$app->view->registerMetaTag(['property' => 'og:image:width', 'content' => '200']);
            \Yii::$app->view->registerMetaTag(['property' => 'og:image:height', 'content' => '200']);
            \Yii::$app->view->registerMetaTag(['property' => 'og:site_name', 'content' => $summary]);
            \Yii::$app->view->registerMetaTag(['property' => 'og:description', 'content' => $baselink]);

            if (Yii::$app->user->isGuest) {
                
                return $this->render('/product/product_detail', [
                    'avlbl_stock' => $avlbl_stock, 
                    'model' => $model, 
                    'similiar_item' => $similiar_item, 
                    'social_vendor' => $social_vendor,
                    'vendor_area' => $vendr_area
                ]);
                /*return $this->render('/product/product_detail',['avlbl_stock'=>$avlbl_stock,'model'=>$model,'similiar_item'=>$similiar_item,'social_vendor'=>$social_vendor,
                'vendor_area'=>$vendr_area,'vendor_timeslot'=>$vendor_timeslot]);*/
            } else {
                
                $user = new Users();
                
                $customer_events_list = $user->get_customer_wishlist_details(Yii::$app->user->identity->customer_id);

                return $this->render('/product/product_detail', [
                    'model' => $model, 
                    'similiar_item' => $similiar_item,
                    'avlbl_stock' => $avlbl_stock, 
                    'social_vendor' => $social_vendor, 
                    'customer_events_list' => $customer_events_list,
                    'vendor_area' => $vendr_area
                ]);
            }
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
