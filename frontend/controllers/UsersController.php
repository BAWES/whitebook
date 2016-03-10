<?php

namespace frontend\controllers;

use Yii;
use arturoliveira\ExcelView;
use frontend\models\Users;
use frontend\models\Signup;
use frontend\models\Basket;
use backend\models\Country;
use backend\models\Customer;
use backend\models\CustomerAddress;
use backend\models\Siteinfo;
use backend\models\Themes;
use backend\models\Vendoritem;
use backend\models\Featuregroupitem;
use backend\models\Vendor;
use backend\models\City;
use frontend\models\Website;
use yii\web\Controller;
use frontend\models\EventinviteesSearch;
use yii\helpers\Arrayhelper;
use backend\models\Events;

/**
* Site controller.
*/
class UsersController extends BaseController
{
public function init()
{
parent::init();
Yii::$app->language = 'en-EN';
}

public function actionIndex()
{
return $this->render('index');
}

public function actionLogin()
{
if (isset($_POST['email']) && isset($_POST['password'])) {
$model = new Users();
$email = $_POST['email'];
$password = $_POST['password'];
// create_event = 1 , add_event=2
$event_status = $_POST['event_status'];
$favourite_status = $_POST['favourite_status'];

$authorization = $model->check_authorization($email, $password);
if ($authorization == -1) {
$return_data['status'] = '-1';
echo json_encode($return_data);
exit;
} elseif ($authorization == -2) {
$return_data['status'] = '-2';
echo json_encode($return_data);
exit;
} elseif ($authorization == -3) {
$return_data['status'] = '-3';
echo json_encode($return_data);
exit;
} else {

if ($event_status == -1) {        
Yii::$app->session->set('create_event', 1);
}
if ($event_status > 0) {
Yii::$app->session->set('event_status', $event_status);
}
if ($event_status == -2) {
Yii::$app->session->set('default', 1);
}

Yii::$app->session->set('customer_id', $authorization[0]['customer_id']);
Yii::$app->session->set('customer_email', $authorization[0]['customer_email']);
Yii::$app->session->set('customer_name', $authorization[0]['customer_name']);
//Yii::$app->session->setFlash('success', Yii::t('frontend','SUCC_LOGIN'));

if ($favourite_status > 0) {
$update_wishlist = $model->update_wishlist_succcess($favourite_status, $authorization[0]['customer_id']);
// add toi favourite
$return_data['status'] = '1';
$vendoritem_model = new Vendoritem();
$item_name = $vendoritem_model->vendoritemname($favourite_status);
$return_data['item_name'] = $item_name;
Yii::$app->session->set('favourite_status', $item_name);
echo json_encode($return_data);
exit;
}

$return_data['status'] = '1';
echo json_encode($return_data);
exit;
}
} else {
return $this->redirect(Yii::$app->request->urlReferrer);
}
}

public function actionLogout()
{
Yii::$app->session->destroy();

return $this->redirect(Yii::$app->params['BASE_URL']);
}

public function actionSignup()
{
$model = new Signup();
$error = array();
//echo $_POST['_csrf'];
if ($_POST['_csrf']) {
$model->attributes = $_POST;

if ($model->validate()) {
$customer_activation_key = $this->generateRandomString();
$signup = $model->signup_customer($model->attributes, $customer_activation_key);
if ($signup) {
$siteinfo = Siteinfo::find()->asArray()->all();
$to = $model['email'];
$username = $model['customer_name'];
Yii::$app->session->set('register', '1');
$message = 'Thank you for registration with '.Yii::$app->params['SITE_NAME'].'.</br><a href='.Yii::$app->params['BASE_URL'].'/confirm-email/'.$customer_activation_key.''." title='Click Here'>Click here </a> to activate your account.";

//$msg="Thank you for registration with ".SITE_NAME.".</br><a href=".BASE_URL."/confirm-email/".$customer_activation_key.''." title='Click Here'>CLICK HERE</a> to activate your account.";
$subject = Yii::$app->params['SIGNUP_SUBJECT'];
$template = 'USER-REGISTER';
$content = 'test';
$body = Yii::$app->params['SIGNUP_TEMPLATE'];
$body = str_replace('%NAME%', $model->customer_name, $body);
$body = str_replace('%MESSAGE%', $message, $body);
$to = 'mariyappan@technoduce.com';

Yii::$app->newcomponent->sendmail($to, $subject, $content, $message, $template);
$this->redirect(Yii::$app->params['BASE_URL']);
echo '1';
die;
} else {
Yii::$app->session->setFlash('error', 'Signup Failed!');
echo '0';
die;
$this->redirect(Yii::$app->params['BASE_URL']);
}
} else {
$error = $model->errors;
print_r($model->getErrors());
die;

die;
}
}

return $this->render('/users/signup', [
'model' => $model,
'error' => $error,
]);
}

public function actionReset_confirm()
{
if (isset($_GET['cust_id'])) {
$model = new Users();
$check = $model->check_customer_validtime($_GET['cust_id']);
if (!empty($check)) {
Yii::$app->session->set('reset_password_mail', $_GET['cust_id']);
} else {
Yii::$app->session->set('reset_password_mail', '1');
}
$this->redirect(Yii::$app->params['BASE_URL']);
}
}
public function actionConfirm_email()
{
if (isset($_GET['key'])) {
$model = new Signup();
$model1 = new Users();
$key = $_GET['key'];
$check_key = $model->check_valid_key($key);
if ($check_key == 1) {
//Yii::$app->session->setFlash('error', "Invalid key!");
$this->redirect(Yii::$app->params['BASE_URL']);
} else {
$login_det = $model->customer_logindetail($key);
$email = $login_det[0]['customer_email'];
$password = $login_det[0]['customer_org_password'];

$authorization = $model1->check_authorization($email, $password);
Yii::$app->session->set('key', '1');
Yii::$app->session->set('customer_id', $authorization[0]['customer_id']);
Yii::$app->session->set('customer_email', $authorization[0]['customer_email']);
Yii::$app->session->set('customer_name', $authorization[0]['customer_name']);
//Yii::$app->session->setFlash('success', Yii::t('frontend','SUCC_LOGIN'));
//print_r ($login_det);die;
$this->redirect(Yii::$app->params['BASE_URL'].'/activate');
//$this->redirect(Yii::$app->params['BASE_URL']);
}
} else {
return $this->redirect(Yii::$app->params['BASE_URL']);
}
}

public function actionForget_password()
{
if (isset($_POST['_csrf']) && isset($_POST['email'])) {
$model = new Users();
$email = $_POST['email'];
$check_email = $model->check_email_exist($email);
$id = $model->check_user_exist($email);
if (count($id) > 0) {
$time = $model->update_datetime_user($id[0]['customer_activation_key']);
$message = 'Your requested password reset. '.Yii::$app->params['SITE_NAME'].'.</br><a href='.Yii::$app->params['BASE_URL'].'/reset/'.$id[0]['customer_activation_key'].''." title='Click Here'>Click here </a> to reset your password.";
$subject = 'Forgot Password?';
$body = 'Forgot Password?';
Yii::$app->maincomponent->sendmail($email, $subject, $body, $message, 'FORGOT-PASSWORD');
Yii::$app->session->setFlash('success', Yii::t('frontend', 'PASS_SENT'));
echo 1;
exit;
} else {
echo -1;
exit;
}
} else {
echo -1;
exit;
}
}

public function actionPassword_reset()
{
if (Yii::$app->request->isAjax) {
if ((isset($_POST['id'])) && (isset($_POST['password']))) {
//$model=new Users();
//$check_user=$model->customer_password_reset($password,$customer_activation_key);
$reset_password = Yii::$app->session->set('reset_password_mail', '');
$final_reset = Yii::$app->session->set('final_reset', '');
$model = new Users();

$customer_activation_key = $_POST['id'];
$password = $_POST['password'];

//$check_user=$model->customer_password_reset($password,$customer_activation_key);
$check_user = $model->customer_password_reset($password, $customer_activation_key);

if (count($check_user) > 0) {
$signup = new Signup();
$login_det = $signup->customer_logindetail($customer_activation_key);
$email = $login_det[0]['customer_email'];
$password = $login_det[0]['customer_org_password'];
$authorization = $model->check_authorization($email, $password);
Yii::$app->session->set('key', '2');
Yii::$app->session->set('customer_id', $authorization[0]['customer_id']);
Yii::$app->session->set('customer_email', $authorization[0]['customer_email']);
Yii::$app->session->set('customer_name', $authorization[0]['customer_name']);
echo '1';
die;
} else {
echo -1;
exit;
}
}
}
}

public function actionEmail_check()
{
if (isset($_POST['_csrf']) && isset($_POST['email'])) {
$model = new Users();
$email = $_POST['email'];
$check_email = $model->check_email_exist($email);
if (count($check_email) > 0) {
echo 1;
die;
} else {
echo 0;
die;
}
}
}
public function actionAccount_settings()
{
$customer_id = Yii::$app->params['CUSTOMER_ID'];
if ($customer_id == '') {
return $this->redirect(Yii::$app->params['BASE_URL']);
}
$country = Country::loadcountry();

$model = new Users();
$user_detail = $model->get_user_details($customer_id);
if (!empty($user_detail[0]['country'])) {
$city = City::listcityname($user_detail[0]['country']);
} else {
$city = City::fullcityname();
}

//echo $count=$user_detail[0]['country'];die;
//		echo $user_detail[0]['customer_gender'];die;
//		print_r ($user_detail);die;
return $this->render('account-settings', ['user_detail' => $user_detail, 'loadcountry' => $country, 'loadcity' => $city]);
}

public function actionEdit_profile()
{
$customer_id = Yii::$app->params['CUSTOMER_ID'];
if ($customer_id == '') {
return $this->redirect(Yii::$app->params['BASE_URL']);
}
$model = new Users();
if (isset($_POST)) {
$post = $_POST;
$update_customer = $model->update_customer_profile($post, $customer_id);
//echo '<pre>';print_r ($update_customer);die;
if ($update_customer) {
echo 1;
//Yii::$app->session->setFlash('success', Yii::t('frontend','SUCC_LOGIN'));
//$this->redirect('account-settings');
}
}
}
public function actionDelivery_address()
{
$customer_id = Yii::$app->params['CUSTOMER_ID'];
if ($customer_id == '') {
return $this->redirect(Yii::$app->params['BASE_URL']);
}
$model = new Users();
if (isset($_POST)) {
$post = $_POST;
$delivery_address = $model->delivery_address_profile($post, $customer_id);
//echo '<pre>';print_r ($update_customer);die;
if ($delivery_address) {
echo 1;
//Yii::$app->session->setFlash('success', Yii::t('frontend','SUCC_LOGIN'));
//$this->redirect('account-settings');
}
}
}

public function actionCustomerdeliveryaddress()
{
$customer_id = Yii::$app->params['CUSTOMER_ID'];
if ($customer_id == '') {
return $this->redirect(Yii::$app->params['BASE_URL']);
}
$model = new Users();
if (Yii::$app->request->isAjax) {
{
$deliveryid = $_POST['deliveryid'];
if ($deliveryid == 0) {
$deliveryid = '';
}
$update_delivery = Yii::$app->db->createCommand()
->update('whitebook_basket', [
'delivery_address_id' => $deliveryid, ], 'customer_id='.$customer_id)
->execute();
if ($update_delivery) {
echo 1;
die;
}
}
}
}
public function actionAddress_info()
{
$customer_id = Yii::$app->params['CUSTOMER_ID'];
if ($customer_id == '') {
return $this->redirect(Yii::$app->params['BASE_URL']);
}
$model = new Users();
if (isset($_POST)) {
$post = $_POST;
$update_customer = $model->update_customer_address($post, $customer_id);
//echo '<pre>';print_r ($update_customer);die;
if ($update_customer) {
echo 1;
//Yii::$app->session->setFlash('success', Yii::t('frontend','SUCC_LOGIN'));
//$this->redirect('account-settings');
}
}
}

public function actionCreate_event()
{
if (Yii::$app->params['CUSTOMER_ID'] == '') {
$this->redirect(Yii::$app->params['BASE_URL']);
}
if (isset($_POST['event_name']) && isset($_POST['event_type']) && isset($_POST['event_date'])) {
$model = new Users();
$event_name = $_POST['event_name'];
$event_type = $_POST['event_type'];
$event_date = $_POST['event_date'];
Yii::$app->session->set('event_name', $event_name);
$customer_id = Yii::$app->params['CUSTOMER_ID'];
$add_event = $model->create_event($event_name, $event_type, $event_date);
if ($add_event == -1) {
echo -1;
exit;
} else {
if (isset($_POST['item_id']) && ($_POST['item_id'] > 0)) {
Yii::$app->session->set('item_name', $_POST['item_name']);
$item_id = $_POST['item_id'];
$event_id = $add_event;
$insert_item_to_event = $model->insert_item_to_event($item_id, $event_id);
if ($insert_item_to_event == -2) {
echo -2;
exit;
} elseif ($insert_item_to_event == 1) {
Yii::$app->session->setFlash('success', Yii::t('frontend', 'EVE_CRE_AD_SUCC'));
echo 2;
exit;
}
}
Yii::$app->session->setFlash('success', Yii::t('frontend', 'EVE_CRE_SUCC'));
echo 1;
exit;
}
} else {
return 1;
exit;
}
}

public function actionUpdate_event()
{
if (Yii::$app->params['CUSTOMER_ID'] == '') {
$this->redirect(Yii::$app->params['BASE_URL']);
}
if (isset($_POST['event_name']) && isset($_POST['event_type']) && isset($_POST['event_date'])) {
$model = new Users();
$event_name = $_POST['event_name'];
$event_type = $_POST['event_type'];
$event_date = $_POST['event_date'];
$event_id = $_POST['event_id'];
$customer_id = Yii::$app->params['CUSTOMER_ID'];
$add_event = $model->update_event($event_name, $event_type, $event_date, $event_id);
if ($add_event == -1) {
echo -1;
exit;
} else {
Yii::$app->session->setFlash('success', Yii::t('frontend', 'EVENT_UPDATED_SUCCESSFULLY'));
echo $add_event;
die;
}
} else {
return 1;
exit;
}
}

public function actionAdd_event()
{
if (Yii::$app->params['CUSTOMER_ID'] == '') {
$this->redirect(Yii::$app->params['BASE_URL']);
}
if (isset($_POST['event_id']) && isset($_POST['item_id'])) {
$model = new Users();
$event_id = $_POST['event_id'];
$item_id = $_POST['item_id'];
$customer_id = Yii::$app->params['CUSTOMER_ID'];
$insert_item_to_event = $model->insert_item_to_event($item_id, $event_id);
if ($insert_item_to_event == -2) {
echo -2;
exit;
} elseif ($insert_item_to_event == 1) {
Yii::$app->session->setFlash('success', Yii::t('frontend', 'EVE_CRE_AD_SUCC'));
echo 1;
exit;
}
} else {
return 1;
exit;
}
}

public function actionAdd_to_wishlist()
{
if (isset($_POST['item_id'])) {
$model = new Users();
$item_id = $_POST['item_id'];
$customer_id = Yii::$app->params['CUSTOMER_ID'];

$update_wishlist = $model->update_wishlist($item_id, $customer_id);
if ($update_wishlist == 1) {
$wishlist = Users::loadcustomerwishlist(Yii::$app->params['CUSTOMER_ID']);
echo count($wishlist);
exit;
} else {
$wishlist = Users::loadcustomerwishlist(Yii::$app->params['CUSTOMER_ID']);
echo count($wishlist);
exit;
}
} else {
$this->redirect(Yii::$app->params['BASE_URL']);
}
}

public function actionEvents()
{
//Yii::$app->params['header1'] = "1"; // uncomment call new header
$customer_id = Yii::$app->params['CUSTOMER_ID'];
if ($customer_id == '') {
$this->redirect(Yii::$app->params['BASE_URL']);
}
$website_model = new Website();
$event_type = $website_model->get_event_types();
$model = new Users();
$event_limit = 8;
$wish_limit = 6;
$offset = 0;
$type = '';
if (isset($_GET['type'])) {
$type = $_GET['type'];
}
$customer_events = $model->get_customer_events($customer_id, $event_limit, $offset, $type);
//print_r ($customer_events);
$customer_events_count = $model->get_customer_events_count($customer_id, $type);
$price = $vendor = $avail_sale = $theme = '';
//print_r ($customer_events_count);
/*$category_id=$price=$vendor=$avail_sale=$theme='';
if(isset($_POST['category']))
{
$category_id=$_POST['category'];
$price=$_POST['price'];
$vendor=$_POST['vendor'];
$avail_sale=$_POST['available_for_sale'];
$theme=$_POST['theme'];
}*/
/*$vendor_list=$model->vendor_list();
$category=$model->get_main_category();
$themes=$model->get_themes(); 	*/
$themes = $model->get_themes();
$customer_unique_events = $website_model->get_user_event_types($customer_id);
//print_r ($customer_unique_events); die;
$customer_event_type = $website_model->get_user_event($customer_id);
//print_r ($customer_events);die;
$customer_category = $model->get_customer_details($customer_id);
//print_r ($customer_category);die;
$vendoritem_model = new Vendoritem();
//print_r ($customer_category);die;
//if(!empty($customer_category)){
$categorylist = $vendoritem_model->get_category_itemlist($customer_category);
//print_r ($categorylist);die;
$vendorlist = $vendoritem_model->get_vendor_itemlist($customer_category);
//echo '<pre>';print_r ($customer_category);//die;
$k = array();
foreach ($customer_category as $c) {
$k[] = $c['item_id'];
}
//print_r ($k);die;
/* start */
$result = Themes::loadthemename_item($k);
$out1[] = array();
$out2[] = array();
foreach ($result as $r) {
if (is_numeric($r['theme_id'])) {
$out1[] = $r['theme_id'];
//$out2[]=0;
}
if (!is_numeric($r['theme_id'])) {
$out2[] = explode(',', $r['theme_id']);

//$out1[]=0;
}
}
$p = array();
foreach ($out2 as $id) {
foreach ($id as $key) {
$p[] = $key;
}
}
if (count($out1)) {
foreach ($out1 as $o) {
if (!empty($o)) {
$p[] = $o;
}
}
}
$p = array_unique($p);
$themelist = Themes::load_all_themename($p);
//print_r ($themelist);die;

//$vendor = Vendor::loadvendor_item($k);

/*  end */

//$themelist=$vendoritem_model->get_theme_itemlist($customer_category);
$avail_sale = $category_id = $vendor = $theme = '';
$customer_wishlist = $model->get_customer_wishlist($customer_id, $wish_limit, $offset, $category_id, $price, $vendor, $avail_sale, $theme);

$customer_wishlist_count = $model->get_customer_wishlist_count($customer_id, $category_id, $price, $vendor, $avail_sale, $theme);

//print_r ($themelist);die;
/*}else{
$categorylist='';
$vendorlist='';
$themelist='';
}*/
//print_r ($vendorlist);die;
/* BEGIN load user events */
$user_events = Events::find()->where(['customer_id' => Yii::$app->params['CUSTOMER_ID']])->asArray()->all();
/* END load user events */

return $this->render('events', ['event_type' => $event_type, 'customer_event_type' => $customer_event_type, 'customer_events' => $customer_events, 'customer_events_count' => $customer_events_count, 'customer_wishlist' => $customer_wishlist, 'customer_wishlist_count' => $customer_wishlist_count, 'vendor' => $vendor, 'category' => $categorylist,
'themes' => $themes, 'customer_unique_events' => $customer_unique_events, 'categorylist' => $categorylist, 'vendorlist' => $vendorlist, 'themelist' => $themelist, ]);
}

public function actionRemove_from_wishlist()
{
if (Yii::$app->request->isAjax) {
if (isset($_POST['item_id'])) {
$model = new Users();
$item_id = $_POST['item_id'];
$customer_id = Yii::$app->params['CUSTOMER_ID'];
$delete_wishlist = $model->delete_wishlist($item_id, $customer_id);
if ($delete_wishlist == 1) {
echo '1';
exit;
} else {
echo 0;
exit;
}
} else {
$this->redirect(Yii::$app->params['BASE_URL']);
}
}
}

public function actionLoad_more_events()
{
$limit = $_GET['limit'];
$offset = $_GET['offset'];
$type = $_GET['type'];
$customer_id = Yii::$app->params['CUSTOMER_ID'];
$model = new Users();
$customer_events = $model->get_customer_events($customer_id, $limit, $offset, $type);
if (count($customer_events) > 0) {
foreach ($customer_events as $ce) {
echo '<div class="col-md-6">
<div class="events_start">
<div class="border_event_list">
<div class="col-md-7">
<h2>'.$ce['event_name'].'</h2>
<h3>'.date('d M Y', strtotime($ce['event_date'])).'</h3>
</div>
<div class="col-md-5">';
if ($ce['item_count'] > 1) {
echo '<a href="#" title="'.$ce['item_count'].' Products">'.$ce['item_count'].' PRODUCTS</a>';
} else {
echo '<a href="#" title="'.$ce['item_count'].' Product">'.$ce['item_count'].' PRODUCT</a>';
}
echo '</div>
<div class="col-md-12">
<h6>'.$ce['event_type'].'</h6>
</div>
</div>
</div>
</div>';
}
$offset_val = $offset + count($customer_events);
echo '---'.$offset_val;
}
}

public function actionLoad_more_wishlist()
{
$limit = $_GET['limit'];
$offset = $_GET['offset'];
$category_id = $_GET['category'];
$price = $_GET['price'];
$vendor = $_GET['vendor'];
$avail_sale = $_GET['available_for_sale'];
$theme = $_GET['theme'];

$customer_id = Yii::$app->params['CUSTOMER_ID'];
$model = new Users();
$customer_wishlist = $model->get_customer_wishlist($customer_id, $limit, $offset, $category_id, $price, $vendor, $avail_sale, $theme);
if (count($customer_wishlist) > 0) {
$i = 1;
foreach ($customer_wishlist as $w) {
echo '<div class="col-md-4" id="favourite_'.$w['item_id'].'" style="  margin-bottom: 10px;">
<div class="items_similar1">
<span class="smil_img">
<a title="" href="#"><img alt="" src="'.IMAGE_PATH.'/similar1.png"></a>
</span>
<div class="similar_descript">
<div class="box_item1">
<h3>'.$w['vendor_name'].'</h3>
<h2>';
if (strlen(strip_tags($w['item_name'])) > 25) {
echo substr(strip_tags($w['item_name']), 0, 25).'..';
} else {
echo strip_tags($w['item_name']);
}
echo '</h2>
<div class="text-center"><span class="borderslid"></span></div>
<h6>';
if (strlen(strip_tags($w['item_description'])) > 100) {
echo substr(strip_tags($w['item_description']), 0, 100).'..';
} else {
echo strip_tags($w['item_description']);
}
echo '</h6>
<div class="favourite">
<div class="favourite_left"><span class="add_but"><a href="#" title="">+</a></span></div>
<span class="bot_prize">'.$w['item_price_per_unit'].Yii::$app->params['CURRENCY_CODE'].'</span>
<div class="favourite_right"><a href="javascript:void(0);" title="Delete" onclick="remove_from_fav('.Yii::$app->params['CUSTOMER_ID'].','.$w['item_id'].');"> <span class="flaticon-paperbin6"></span></a></div>
</div>
</div>
</div>
</div>
</div>';

++$i;
}
$offset_val = $offset + count($customer_wishlist);
echo '---'.$offset_val;
exit;
}
}

public function generateRandomString($length = 10)
{
$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
$charactersLength = strlen($characters);
$randomString = '';
for ($i = 0; $i < $length; ++$i) {
$randomString .= $characters[rand(0, $charactersLength - 1)];
}

return $randomString;
}

public function actionUsereventlist()
{
if (Yii::$app->request->isAjax) {
$data = Yii::$app->request->post();
$command = Yii::$app->DB->createCommand(
'SELECT * FROM whitebook_events where customer_id = '.$data['cid']." AND event_type LIKE '".$data['type']."'");
$user_event_list = $command->queryAll();

return $this->renderPartial('user_event_list', ['user_event_list' => $user_event_list]);
}
}

public function actionBasket_update()
{
if (Yii::$app->request->isAjax) {
$data = Yii::$app->request->post();

$vendoritem_model = new Vendoritem();
$vendoritem_quantity = $vendoritem_model->vendoritem_quantity($data['item_id']);
$amt_stock = $vendoritem_quantity['item_amount_in_stock'];
$default_capacity = $vendoritem_quantity['item_default_capacity'];

if ($data['quantity_val'] > $default_capacity) {
echo '0';
die;
}

if ($data['quantity_val'] > $amt_stock) {
echo '0';
die;
}
$update = Yii::$app->db->createCommand()
->update('whitebook_basket', [
'basket_quantity' => $data['quantity_val'], ], 'item_id='.$data['item_id'])
->execute();
if ($update) {
echo '1';
} else {
echo '0';
}
die;
}
}

public function actionBasket_delete()
{
if (Yii::$app->request->isAjax) {
$data = Yii::$app->request->post();

$command = Yii::$app->DB->createCommand("DELETE FROM whitebook_basket WHERE item_id='".$data['item_id']."'");
if ($command->execute()) {
echo '1';
} else {
echo '0';
}
die;
}
}
public function actionBasket()
{
if (Yii::$app->params['CUSTOMER_ID'] == '') {
$this->redirect(Yii::$app->params['BASE_URL']);
} else {
$customer_id = Yii::$app->params['CUSTOMER_ID'];

$similiar = new Featuregroupitem();
$customer_details = new Customer();
$customer_address = new CustomerAddress();
$cust_details = $customer_details->customer_details($customer_id);
//echo '<pre>';//print_r ($cust_details);die;
$address = $customer_address->customer_address_details($customer_id);
//print_r ($address);die;
$similiar_item = $similiar->similiar_details();
$sql1 = 'select distinct wvi.item_price_per_unit,wi.image_path, wvi.item_price_per_unit,wvi.item_id,wb.basket_quantity, wvi.item_name,wvi.slug, wv.vendor_name ,count(*) as total FROM whitebook_vendor_item as wvi
LEFT JOIN whitebook_image as wi ON wvi.item_id = wi.item_id
LEFT JOIN whitebook_basket as wb ON wvi.item_id = wb.item_id
LEFT JOIN whitebook_vendor as wv ON wv.vendor_id = wvi.vendor_id
WHERE wvi.trash="Default" and wvi.item_approved="Yes" and wvi.item_status="Active" and wvi.type_id="2"
and wvi.item_for_sale="Yes"  AND wi.module_type = "vendor_item" AND wb.customer_id="'.$customer_id.'" Group By wb.basket_id';
$basketData = Yii::$app->db->createCommand($sql1)->queryAll();

return $this->render('/users/basket', ['basketData' => $basketData, 'similiar_item' => $similiar_item, 'customer_details' => $cust_details, 'address' => $address]);
}
}

public function actionPayment()
{
if (Yii::$app->params['CUSTOMER_ID'] == '') {
$this->redirect(Yii::$app->params['BASE_URL']);
} else {
$customer_id = Yii::$app->params['CUSTOMER_ID'];
$command = Yii::$app->DB->createCommand(
"SELECT DISTINCT delivery_address_id FROM {{%basket}} where customer_id = '".$customer_id."'");
$address_id = $command->queryAll();
//echo $address_id[0]['delivery_address_id'];die;
if ($address_id[0]['delivery_address_id'] == 0) {
$command = Yii::$app->DB->createCommand(
"SELECT customer_name,customer_last_name,customer_email,customer_mobile,customer_address,country,area,block,street,juda FROM {{%customer}} where customer_id = '".$customer_id."'");
$customer_details = $command->queryOne();
} else {
$command = Yii::$app->DB->createCommand(
"SELECT customer_name,customer_last_name,customer_email,customer_mobile
FROM {{%customer}} where customer_id = '".$customer_id."'");
$customer_details1 = $command->queryOne();

$command = Yii::$app->DB->createCommand(
"SELECT address_type_id,country_id,city_id,area_id,address_data FROM {{%customer_address}} where address_id = '".$address_id[0]['delivery_address_id']."'");
$address_details = $command->queryOne();
$customer_details = (array_merge($customer_details1, $address_details));
}

return $this->render('/users/payment', ['customer_details' => $customer_details]);
}
}

public function actionCashondelivery()
{
if (Yii::$app->params['CUSTOMER_ID'] == '') {
$this->redirect(Yii::$app->params['BASE_URL']);
} else {
$ip = Yii::$app->request->getUserIP();

// main order to store in db
$customer_id = Yii::$app->params['CUSTOMER_ID'];
$item_details1 = Yii::$app->db->createCommand('SELECT SUM(wb.basket_quantity * wvi.item_price_per_unit) as without_del,SUM(wvi.item_price_per_unit) as deliver_charg,SUM(wb.basket_quantity * wvi.item_price_per_unit+wvi.item_price_per_unit) as final FROM {{%basket}} as wb
INNER JOIN {{%vendor_item}} as wvi ON wvi.item_id = wb.item_id
where wvi.item_status = "Active" AND wvi.trash="Default" AND wvi.item_for_sale="Yes" AND wvi.type_id="2" AND wb.customer_id="'.$customer_id.'"')->queryAll();
echo '<pre>';
print_r($item_details1);

$date = date('Y-m-d h:i:s');
$command = Yii::$app->DB->createCommand()
->insert('{{%order}}', [
'customer_id' => $customer_id,
'order_total_delivery_charge' => $item_details1[0]['without_del'],
'order_total_with_delivery' => $item_details1[0]['final'],
'order_total_without_delivery' => $item_details1[0]['deliver_charg'],
'order_payment_method' => 'COD',
'order_transaction_id' => '123456789',
'order_gateway_percentage' => '2',
'order_gateway_total' => $item_details1[0]['deliver_charg'],
'order_datetime' => $date,
'order_ip_address' => $ip,
'created_by' => $customer_id,
'created_date' => $date,
])
->execute();

$command = Yii::$app->DB->createCommand(
"SELECT order_id FROM {{%order}} where customer_id = '".$customer_id."' order by order_id DESC");
$order_id = $command->queryAll();

$item_details = Yii::$app->db->createCommand('SELECT wb.item_id,wb.basket_quantity * wvi.item_price_per_unit as without_del,wb.basket_quantity * wvi.item_price_per_unit * 10 as with_del,10 as delivery,wvi.vendor_id FROM {{%basket}} as wb
INNER JOIN {{%vendor_item}} as wvi ON wvi.item_id = wb.item_id
where wvi.item_status = "Active" AND wvi.trash="Default" AND wvi.item_for_sale="Yes" AND wvi.type_id="2" AND wb.customer_id="'.$customer_id.'"')->queryAll();
$command = Yii::$app->DB->createCommand(
"SELECT * FROM {{%basket}} where customer_id = '".$customer_id."'");
$basket = $command->queryAll();

$items = array();
foreach ($basket as $bas) {
$items[] = $bas['item_id'];
$item_quantity[] = $bas['basket_quantity'];
$delivery_ids[] = $bas['delivery_address_id'];
$de[] = $bas['delivery_address_id'];
}
$vendor_detail = Vendoritem::get_vendor_itemlist($items);

$price_detail = Vendoritem::get_item_pricelist($items);

echo '<pre>';
$i = 0;
foreach ($vendor_detail as $vid) {
$item_quantity1 = $item_quantity[$i];
$commission = 2;
$delivery_charge = 10;
$without = ($price_detail[$i]['item_price_per_unit'] * $item_quantity1);
$with_deli = ($price_detail[$i]['item_price_per_unit'] * $item_quantity1) + $delivery_charge;
$comm = $with_deli * 2 / 100;
$command = Yii::$app->DB->createCommand()
->insert('{{%suborder}}', [
'order_id' => $order_id[0]['order_id'],
'vendor_id' => $vid['vendor_id'],
'status_id' => 1,
'suborder_delivery_charge' => $delivery_charge,
'suborder_total_without_delivery' => $without,
'suborder_total_with_delivery' => $with_deli,
'suborder_commission_percentage' => $commission,
'suborder_commission_total' => $comm,
'suborder_vendor_total' => $date,
'suborder_datetime' => $date,
'created_by' => $customer_id,
'created_datetime' => $date,
])
->execute();

++$i;
}

$command = Yii::$app->DB->createCommand(
"SELECT * FROM {{%suborder}} where order_id = '".$order_id[0]['order_id']."'");
$suborder = $command->queryAll();
$i = 0;
foreach ($suborder as $so) {


$command = Yii::$app->DB->createCommand()
->insert('{{%suborder_item_purchase}}', [
'suborder_id' => $so['suborder_id'],
'timeslot_id' => '1',
'item_id' => $items[$i],
'address_id' => $delivery_ids[$i],
'purchase_delivery_date' => $date,
'area_id' => '1',
'purchase_quantity' => $item_quantity[$i],
'created_by' => $customer_id,
'created_datetime' => $date,
])
->execute();

++$i;
}
$command = Yii::$app->DB->createCommand(
"DELETE  FROM {{%basket}} where customer_id = '".$customer_id."'");
$basket = $command->queryAll();
$this->redirect(Yii::$app->params['BASE_URL']);

return $this->render('/users/payment', ['customer_details' => $customer_details]);
}
}

public function actionEventdetails($slug = '')
{
$event_details = Events::find()->where(['customer_id' => Yii::$app->params['CUSTOMER_ID'], 'slug' => $slug])->asArray()->all();
if (empty($event_details)) {
throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
}
$customer_events_list = Users::get_customer_wishlist_details(Yii::$app->params['CUSTOMER_ID']);
$eventitem_details = Yii::$app->db->createCommand('SELECT weil.item_id FROM {{%event_item_link}} as weil
INNER JOIN {{%vendor_item}} as wvi ON wvi.item_id = weil.item_id
where wvi.item_status = "Active" AND wvi.trash="Default" AND wvi.item_for_sale="Yes" AND wvi.type_id="2" AND weil.event_id='.$event_details[0]['event_id'])->queryAll();
$searchModel = new EventinviteesSearch();
$dataProvider = $searchModel->loadsearch(Yii::$app->request->queryParams, $slug);

return $this->render('event_detail', ['slug' => $slug, 'event_details' => $event_details, 'customer_events_list' => $customer_events_list,
'searchModel' => $searchModel, 'dataProvider' => $dataProvider, ]);
}

public function actionExcel($slug = '')
{
$event_details = Events::find()->where(['customer_id' => Yii::$app->params['CUSTOMER_ID'], 'slug' => $slug])->asArray()->all();
$customer_events_list = Users::get_customer_wishlist_details(Yii::$app->params['CUSTOMER_ID']);
$searchModel = new EventinviteesSearch();
$dataProvider = $searchModel->loadsearch(Yii::$app->request->queryParams, $slug);

ExcelView::widget([
'dataProvider' => $dataProvider,
'filterModel' => $searchModel,
'filename' => 'Invites list',
'fullExportType' => 'xls', //can change to html,xls,csv and so on
'grid_mode' => 'export',
'columns' => [
['class' => 'yii\grid\SerialColumn'],
'name',
'email',
'phone_number',
],
]);
}

public function actionDeleteeventitem()
{
if (Yii::$app->request->isAjax) {
$data = Yii::$app->request->post();
$command = Yii::$app->DB->createCommand(
'DELETE from whitebook_event_item_link where link_id='.$data['item_link_id']);
if ($command->execute()) {
$cat_list1 = Yii::$app->db->createCommand('SELECT wvi.item_id FROM `whitebook_vendor_item` as wvi INNER JOIN whitebook_event_item_link as wei
ON wvi.item_id = wei.item_id and wei.trash="default" and wvi.category_id ='.$data['category_id'].' and wei.event_id = '.$data['event_id'].'')->queryAll();
echo count($cat_list1);
} else {
echo -1;
}
}
}

/* BEGIN ADD to cart*/
public function actionAddtobasket()
{
if (Yii::$app->request->isAjax) {
$data = Yii::$app->request->post();
$item_id = $data['item_id'];
$cust_id = $data['cust_id'];
$item_exist = Basket::find()->select('basket_id, basket_quantity')->where(['item_id' => $item_id,  'customer_id' => $cust_id])->asArray()->limit(1)->one();
if (!empty($item_exist)) {
$basket = Basket::findIdentity($item_exist['basket_id']);
$basket->basket_quantity = $item_exist['basket_quantity'] + 1;
$basket->save();
} elseif (empty($item_exist)) {
$command = Yii::$app->DB->createCommand()
->insert('{{%basket}}', [
'item_id' => $item_id,
'customer_id' => $cust_id,
'basket_quantity' => 1, ])
->execute();
if ($command) {
echo 1;
} else {
echo 0;
}
}
}
}
/* END ADD to cart*/

public function actionUserorderdetails()
{
if (Yii::$app->params['CUSTOMER_ID'] != '') {
$order_details = Yii::$app->db->createCommand('SELECT wsip.item_id, wvi.item_name,wv.vendor_id,
wv.vendor_name,	wo.order_total_with_delivery, wo.order_datetime,wsip.purchase_quantity, wvi.item_price_per_unit,ws.status_name,wo.order_id from whitebook_suborder_item_purchase as wsip
LEFT JOIN whitebook_suborder as wso ON wso.suborder_id = wsip.suborder_id
LEFT JOIN whitebook_order as wo ON wo.order_id = wso.order_id
LEFT JOIN whitebook_vendor_item as wvi ON wvi.item_id = wsip.item_id
LEFT JOIN whitebook_vendor as wv ON wvi.vendor_id = wv.vendor_id
LEFT JOIN whitebook_status as ws ON ws.status_id = wso.status_id
WHERE wsip.trash="default" AND wo.customer_id ='.CUSTOMER_ID)->queryAll();

return $this->render('user_order_details', ['order_details' => $order_details]);
} else {
throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
}
}
}
