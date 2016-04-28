<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use common\models\Vendoritem;
use common\models\Vendor;
use common\models\Featuregroupitem;
use frontend\models\Users;
use common\models\Location;
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
        Yii::$app->language = 'en-EN';
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
			->andwhere(['module_type' => 'vendor_item'])
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
			->leftJoin('{{%vendor_location}}', '{{%vendor_location}}.area_id = {{%location}}.id')
			->where(['{{%location}}.trash' => 'Default'])
			->asArray()
			->all();
            /* END DELIVERY AREAS --VENDOR */

            $title = 'Whitebook Application '.ucfirst($vendor_det['vendor_name']);
            $url = urlencode(Yii::$app->homeUrl . $_SERVER['REQUEST_URI']);
            $summary = 'Whitebook Application '.ucfirst($vendor_det['vendor_name']).' '.ucfirst($model['item_name']);
            //$image='http://demositeapp.com/backend/web/uploads/vendor_images/445_blueberry_coffee_cake_61.jpg';
            $image = $baselink;
            \Yii::$app->view->registerMetaTag(['property' => 'og:title', 'content' => $title]);
            \Yii::$app->view->registerMetaTag(['property' => 'og:url', 'content' => $url]);
            \Yii::$app->view->registerMetaTag(['property' => 'og:image', 'content' => $baselink]);
            \Yii::$app->view->registerMetaTag(['property' => 'og:image:width', 'content' => '200']);
            \Yii::$app->view->registerMetaTag(['property' => 'og:image:height', 'content' => '200']);
            \Yii::$app->view->registerMetaTag(['property' => 'og:site_name', 'content' => $summary]);
            \Yii::$app->view->registerMetaTag(['property' => 'og:description', 'content' => $baselink]);

            if ($this->customer_id == '') {
                return $this->render('/product/product_detail', ['avlbl_stock' => $avlbl_stock, 'model' => $model, 'similiar_item' => $similiar_item, 'social_vendor' => $social_vendor,
                'vendor_area' => $vendr_area, ]);
                /*return $this->render('/product/product_detail',['avlbl_stock'=>$avlbl_stock,'model'=>$model,'similiar_item'=>$similiar_item,'social_vendor'=>$social_vendor,
                'vendor_area'=>$vendr_area,'vendor_timeslot'=>$vendor_timeslot]);*/
            } else {
                $user = new Users();
                $customer_events_list = $user->get_customer_wishlist_details($this->customer_id);

                return $this->render('/product/product_detail', ['model' => $model, 'similiar_item' => $similiar_item,
                'avlbl_stock' => $avlbl_stock, 'social_vendor' => $social_vendor, 'customer_events_list' => $customer_events_list,
                'vendor_area' => $vendr_area, ]);
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

    public function actionAddevent()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            
            $model = Vendoritem::find()
			->select(['{{%vendor_item}}.item_id','{{%vendor_item}}.item_price_per_unit','{{%vendor_item}}.item_name','{{%vendor}}.vendor_name'])
			->leftJoin('{{%image}}', '{{%vendor_item}}.item_id = {{%image}}.item_id')
			->leftJoin('{{%vendor}}', '{{%vendor}}.vendor_id = {{%vendor_item}}.vendor_id')
			->where(['{{%image}}.module_type' => 'vendor_item'])
			->andwhere(['{{%vendor_item}}.item_id' => $data['item_id']])
			->asArray()
			->all();
			
            $user = new Users();
            $customer_events = Events::find()->where(['customer_id' => Yii::$app->params['CUSTOMER_ID']])->asArray()->all();
            return $this->renderPartial('add_event', array('model' => $model, 'customer_events' => $customer_events));
        }
    }

    public function actionEventdetails()
    {
        $customer_id = Yii::$app->params['CUSTOMER_ID'];
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
