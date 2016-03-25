<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use backend\models\Vendoritem;
use backend\models\Vendor;
use backend\models\Featuregroupitem;
use frontend\models\Users;
use backend\models\Location;

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
$customer_id = Yii::$app->params['CUSTOMER_ID'];
if ($slug != '') {
$similiar = new Featuregroupitem();
$similiar_item = $similiar->similiar_details();
$item = new Vendoritem();
$model = Vendoritem::findvendoritem($slug);
$avlbl_stock = Yii::$app->db->createCommand('select wvi.item_amount_in_stock as stock FROM whitebook_vendor_item as wvi
WHERE wvi.trash="Default" and wvi.item_approved="Yes" and wvi.item_status="Active" and wvi.type_id="2"
and wvi.item_for_sale="Yes" AND wvi.item_amount_in_stock > 0
AND wvi.slug = "'.$slug.'"')->queryOne();

if (empty($model)) {
throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
}
$vendor = new Vendor();
$vendor_det = Vendor::vendorslug($model['vendor_id']);
$social_vendor = $vendor->sociallist($model->vendor_id);

$sql = 'SELECT image_path FROM whitebook_image WHERE item_id='.$model['item_id'].' and module_type="vendor_item" order by vendorimage_sort_order';
$command = Yii::$app->DB->createCommand($sql);
$output = $command->queryAll();
$baselink = Yii::$app->params['BASE_URL'].Yii::getAlias('@vendor_image/').'no_image.jpg';
foreach ($output as $out) {
if ($out) {
$imglink = Yii::getAlias('@vendor_image/').$out['image_path'];
$baselink = Yii::$app->params['BASE_URL'].Yii::getAlias('@vendor_image/').$out['image_path'];
} else {
$imglink = Yii::getAlias('@vendor_image/').'no_image.jpg';
$baselink = Yii::$app->params['BASE_URL'].Yii::getAlias('@vendor_image/').'no_image.jpg';
}
}

/* BEGIN DELIVERY AREAS --VENDOR */
$sql = 'SELECT wl.location, wvl.area_id FROM `whitebook_vendor_location` as wvl
LEFT JOIN whitebook_location as wl ON wl.id = wvl.area_id
where wl.trash="Default" limit 100';
$vendr_area = Yii::$app->db->createCommand($sql)->queryAll();
/* END DELIVERY AREAS --VENDOR */

$title = 'Whitebook Application '.ucfirst($vendor_det['vendor_name']);
$url = urlencode(Yii::$app->params['BASE_URL'].$_SERVER['REQUEST_URI']);
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

if (Yii::$app->params['CUSTOMER_ID'] == '') {
return $this->render('/product/product_detail', ['avlbl_stock' => $avlbl_stock, 'model' => $model, 'similiar_item' => $similiar_item, 'social_vendor' => $social_vendor,
'vendor_area' => $vendr_area, ]);
/*return $this->render('/product/product_detail',['avlbl_stock'=>$avlbl_stock,'model'=>$model,'similiar_item'=>$similiar_item,'social_vendor'=>$social_vendor,
'vendor_area'=>$vendr_area,'vendor_timeslot'=>$vendor_timeslot]);*/
} else {
$user = new Users();
$customer_events_list = $user->get_customer_wishlist_details($customer_id);

return $this->render('/product/product_detail', ['model' => $model, 'similiar_item' => $similiar_item,
'avlbl_stock' => $avlbl_stock, 'social_vendor' => $social_vendor, 'customer_events_list' => $customer_events_list,
'vendor_area' => $vendr_area, ]);
}
}
}

public function actionEvent_slider()
{
if (Yii::$app->params['CUSTOMER_ID'] == '') {
$this->redirect(Yii::$app->params['BASE_URL']);
} else {
return $this->renderPartial('events_slider');
}
}

public function actionAddevent()
{
if (Yii::$app->request->isAjax) {
$data = Yii::$app->request->post();
$model = Yii::$app->db->createCommand('select wvi.item_id,wi.image_path, wvi.item_price_per_unit, wvi.item_name, wv.vendor_name FROM whitebook_vendor_item as wvi
LEFT JOIN whitebook_image as wi ON wvi.item_id = wi.item_id  and wi.module_type="vendor_item"
LEFT JOIN whitebook_vendor as wv ON wv.vendor_id = wvi.vendor_id where wvi.item_id='.$data['item_id'].' limit 1')->queryAll();
$user = new Users();
$customer_events = Yii::$app->db->createCommand('select * from whitebook_events where customer_id='.Yii::$app->params['CUSTOMER_ID'])->queryAll();

return $this->renderPartial('add_event', array('model' => $model, 'customer_events' => $customer_events));
}
}

public function actionEventdetails()
{
$customer_id = Yii::$app->params['CUSTOMER_ID'];
if (Yii::$app->request->isAjax) {
$data = Yii::$app->request->post();
$edit_eventinfo = Yii::$app->db->createCommand('select * from whitebook_events where event_id='.$data['event_id'])->queryAll();

return $this->renderPartial('edit_event', array('edit_eventinfo' => $edit_eventinfo));
}
}

/* BEGIN DELIVERY TIME SLOT -- VENDOR */
public function actionGetdeliverytime()
{
$sql1 = 'SELECT timeslot_id,timeslot_start_time,timeslot_end_time FROM {{%vendor_delivery_timeslot}} WHERE vendor_id='.$model['vendor_id'];
$vendor_timeslot = Yii::$app->db->createCommand($sql1)->queryAll();
}
/* END DELIVERY TIME SLOT -- VENDOR */
}
