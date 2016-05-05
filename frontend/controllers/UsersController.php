<?php

namespace frontend\controllers;

use Yii;
use arturoliveira\ExcelView;
use frontend\models\Basket;
use common\models\Country;
use frontend\models\Customer;
use common\models\CustomerAddress;
use common\models\Siteinfo;
use frontend\models\Themes;
use frontend\models\Vendoritem;
use common\models\Featuregroupitem;
use common\models\LoginForm;
use common\models\Vendor;
use common\models\City;
use frontend\models\Website;
use yii\web\Controller;
use frontend\models\EventinviteesSearch;
use yii\helpers\Arrayhelper;
use yii\helpers\Url;
use common\models\Events;
use frontend\models\Users;

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
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new Customer();
        $model->scenario = 'login';

        if (isset($_POST['email']) && isset($_POST['password'])) {
            $model->customer_email = $_POST['email'];
            $model->customer_password = $_POST['password'];
            $event_status = $_POST['event_status'];
            $favourite_status = $_POST['favourite_status'];
            if($model->login() == 1) {
                    Customer::setEventSession($event_status,$model->customer_email);
                    if ($favourite_status > 0) {
                        $userModel = new Users();
                        $update_wishlist = $userModel->update_wishlist_succcess($favourite_status, $authorization[0]['customer_id']);
                        // add to favourite
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
                else
                {
                    $return_data['status'] = $model->login();
                    echo json_encode($return_data);
                }
        } else {
            return $this->redirect(Yii::$app->request->urlReferrer);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    public function actionSignup()
    {
        $model = new Customer();
        $model->scenarios = 'signup';
        $error = array();
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $model->customer_password = Yii::$app->getSecurity()->generatePasswordHash($data['password']);
            $model->customer_dateofbirth = $data['byear'].'-'.$data['bmonth'].'-'.$data['bday'];
            $model->customer_activation_key = $this->generateRandomString();
            $model->created_datetime = date('Y-m-d H:i:s');
            $model->customer_name=$data['customer_name'];
            $model->customer_last_name=$data['customer_last_name'];
            $model->customer_email=$data['customer_email'];
            $model->customer_gender=$data['customer_gender'];
            $model->customer_mobile=$data['customer_phone'];
            if ($model->validate() && $model->save()) {
                    $siteinfo = Siteinfo::find()->asArray()->all();
                    $to = $model['customer_email'];
                    $username = $model['customer_name'];
                    Yii::$app->session->set('register', '1');
                    $message = 'Thank you for registration with us.</br><a href='.Url::to('/users/confirm_email/'.$customer_activation_key).' title="Click Here">Click here </a> to activate your account.';
                    $body = Yii::$app->params['SIGNUP_TEMPLATE'];
                    $body .= str_replace('%NAME%', $model->customer_name, $body);
                    $body .= str_replace('%MESSAGE%', $message, $body);
                    $send = Yii::$app->mailer->compose("mail-template/mail",["message"=>$body,"user"=>"Admin"])
                    ->setFrom(Yii::$app->params['supportEmail'])
                    ->setTo(Yii::$app->params['adminEmail'])
                    ->setSubject('USER-REGISTER')
                    ->send();
                    $this->redirect(Url::to('site/index'));
                    echo '1';
                    die;
                    } else {
                    Yii::$app->session->setFlash('error', 'Signup Failed!');
                    echo '0';
                    die;
                    $this->redirect(Url::to('site/index'));
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
            return $this->goHome();
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
                return $this->goHome();
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
                return $this->redirect(Url::toRoute('/site/activate'));
                //return $this->goHome();
            }
        } else {
            return $this->goHome();
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
                $message = 'Your requested password reset.</br><a href='.Yii::$app->urlManager->createAbsoluteUrl("/users/reset_confirm/".$id[0]["customer_activation_key"]).' title="Click Here">Click here </a> to reset your password';
                $send = Yii::$app->mailer->compose("mail-template/mail",["message"=>$message,"user"=>"Customer"])
                ->setFrom(Yii::$app->params['supportEmail'])
                ->setTo($email)
                ->setSubject('Requested forgot Password')
                ->send();
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
                $reset_password = Yii::$app->session->set('reset_password_mail', '');
                $final_reset = Yii::$app->session->set('final_reset', '');
                $model = new Users();
                $customer_activation_key = $_POST['id'];
                $password = $_POST['password'];
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
        $customer_id = Yii::$app->user->identity->customer_id;
        if ($customer_id == '') {
            return $this->goHome();
        }
        $country = Country::loadcountry();

        $model = new Users();
        $user_detail = $model->get_user_details($customer_id);
        $customer_details = array_merge($user_detail, $user_detail['customerAddress'][0]);
        unset($customer_details['customerAddress']);
        

        if (!empty($user_detail['country'])) {
            $city = City::listcityname($user_detail['country']);
        } else {
            $city = City::fullcityname();
        }
        return $this->render('account-settings', ['user_detail' => $customer_details, 'loadcountry' => $country, 'loadcity' => $city]);
    }

    public function actionEdit_profile()
    {
        $customer_id = Yii::$app->user->identity->customer_id;
        if ($customer_id == '') {
            return $this->goHome();
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
        $customer_id = Yii::$app->user->identity->customer_id;
        if ($customer_id == '') {
            return $this->goHome();
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


    public function actionAddress_info()
    {
        $customer_id = Yii::$app->user->identity->customer_id;
        if ($customer_id == '') {
            return $this->goHome();
        }
        $model = new Users();
        if (isset($_POST)) {
            $post = $_POST;
            $update_customer = $model->update_customer_address($post, $customer_id);
            //echo '<pre>';print_r ($update_customer);die;
            if ($update_customer) {
                echo 1;
                //Yii::$app->session->setFlash('success', Yii::t('frontend','SUCC_LOGIN'));
                //return $this->redirect('account-settings');
            }
        }
    }

    public function actionCreate_event()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        if (isset($_POST['event_name']) && isset($_POST['event_type']) && isset($_POST['event_date'])) {
            $model = new Users();
            $event_name = $_POST['event_name'];
            $event_type = $_POST['event_type'];
            $event_date = $_POST['event_date'];
            Yii::$app->session->set('event_name', $event_name);
            $customer_id = Yii::$app->user->identity->customer_id;
            // Creating event start
            
            $customer_id = Yii::$app->user->identity->customer_id;
			$event_date1 = date('Y-m-d', strtotime($event_date));
			$string = str_replace(' ', '-', $event_name); // Replaces all spaces with hyphens.
			$slug = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
			$check = Events::find()->select('event_id')->where(['customer_id'=>$customer_id,'event_name'=>$event_name])->asArray()->all();
			if (count($check) > 0) {
				$result='-1';
			} else {
			$event_modal=new Events;
			$event_modal->customer_id=$customer_id;
			$event_modal->event_name=$event_name;
			$event_modal->event_date=$event_date;
			$event_modal->event_type=$event_type;
			$event_modal->slug=$slug;
			$event_modal->save();
			$result=$event_modal->event_id;
			}
            // Creating event end
            
            if ($result == -1) {
                echo -1;
                exit;
            } else {
                if (isset($_POST['item_id']) && ($_POST['item_id'] > 0)) {
                    Yii::$app->session->set('item_name', $_POST['item_name']);
                    $item_id = $_POST['item_id'];
                    $event_id = $add_event;
                   $check = Eventitemlink::find()->select(['link_id'])
                   ->where(['event_id'=> $event_id])
                   ->andwhere(['item_id'=> $item_id])
                   ->count();
        if($check > 0) {
			 echo -2;
			 exit;
        } else {
            $event_date = date('Y-m-d H:i:s');
			$event_item_modal=new Eventitemlink;
			$event_item_modal->event_id=$event_id;
			$event_item_modal->item_id=$item_id;
			$event_item_modal->link_datetime=$event_date;
			$event_item_modal->created_datetime=$event_date;
			$event_item_modal->modified_datetime=$event_date;
			$event_item_modal->save();
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
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        if (isset($_POST['event_name']) && isset($_POST['event_type']) && isset($_POST['event_date'])) {
            $model = new Users();
            $event_name = $_POST['event_name'];
            $event_type = $_POST['event_type'];
            $event_date = $_POST['event_date'];
            $event_id = $_POST['event_id'];
            $customer_id = Yii::$app->user->identity->customer_id;
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
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        if (isset($_POST['event_id']) && isset($_POST['item_id'])) {
            $model = new Users();
            $event_id = $_POST['event_id'];
            $item_id = $_POST['item_id'];
            $customer_id = Yii::$app->user->identity->customer_id;
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
            $customer_id = Yii::$app->user->identity->customer_id;

            $update_wishlist = $model->update_wishlist($item_id, $customer_id);
            if ($update_wishlist == 1) {
                $wishlist = Users::loadCustomerWishlist(Yii::$app->user->identity->customer_id);
                echo count($wishlist);
                exit;
            } else {
                $wishlist = Users::loadCustomerWishlist(Yii::$app->user->identity->customer_id);
                echo count($wishlist);
                exit;
            }
        } else {
            return $this->goHome();
        }
    }

    public function actionEvents()
    {

        $customer_id = Yii::$app->user->identity->customer_id;
        if ($customer_id == '') {
            return $this->goHome();
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
        $customer_events = $model->getCustomerEvents($customer_id, $event_limit, $offset, $type);
        //print_r ($customer_events);
        $customer_events_count = $model->get_customer_events_count($customer_id, $type);
        $price = $vendor = $avail_sale = $theme = '';

        $themes = $model->get_themes();
        $customer_unique_events = $website_model->get_user_event_types($customer_id);

        $customer_event_type = $website_model->get_user_event($customer_id);

        $customer_category = $model->get_customer_details($customer_id);

        $vendoritem_model = new Vendoritem();

        $categorylist = $vendoritem_model->get_category_itemlist($customer_category);

        $vendorlist = $vendoritem_model->get_vendor_itemlist($customer_category);

        $k = array();
        foreach ($customer_category as $c) {
            $k[] = $c['item_id'];
        }

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

        /*  end */
        $avail_sale = $category_id = $vendor = $theme = '';
        $customer_wishlist = $model->get_customer_wishlist($customer_id, $wish_limit, $offset, $category_id, $price, $vendor, $avail_sale, $theme);

        $customer_wishlist_count = $model->get_customer_wishlist_count($customer_id, $category_id, $price, $vendor, $avail_sale, $theme);

        /* BEGIN load user events */
        $user_events = Events::find()->where(['customer_id' => Yii::$app->user->identity->customer_id])->asArray()->all();
        /* END load user events */

        return $this->render('events', ['event_type' => $event_type, 'customer_event_type' => $customer_event_type, 'customer_events' => $customer_events, 'customer_events_count' => $customer_events_count, 'customer_wishlist' => $customer_wishlist, 'customer_wishlist_count' => $customer_wishlist_count, 'vendor' => $vendor, 'category' => $categorylist,
        'themes' => $themes, 'customer_unique_events' => $customer_unique_events, 'categorylist' => $categorylist, 'vendorlist' => $vendorlist, 'themelist' => $themelist,'slug'=>'events' ]);
    }

    public function actionRemove_from_wishlist()
    {
        if (Yii::$app->request->isAjax) {
            if (isset($_POST['item_id'])) {
                $model = new Users();
                $item_id = $_POST['item_id'];
                $customer_id = Yii::$app->user->identity->customer_id;
                $delete_wishlist = $model->delete_wishlist($item_id, $customer_id);
                if ($delete_wishlist == 1) {
                    echo '1';
                    exit;
                } else {
                    echo 0;
                    exit;
                }
            } else {
                return $this->goHome();
            }
        }
    }

    public function actionLoad_more_events()
    {
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $type = $_GET['type'];
        $customer_id = Yii::$app->user->identity->customer_id;
        $model = new Users();
        $customer_events = $model->getCustomerEvents($customer_id, $limit, $offset, $type);
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

        $customer_id = Yii::$app->user->identity->customer_id;
        $model = new Users();
        $customer_wishlist = $model->get_customer_wishlist($customer_id, $limit, $offset, $category_id, $price, $vendor, $avail_sale, $theme);
        if (count($customer_wishlist) > 0) {
            $i = 1;
            foreach ($customer_wishlist as $w) {
                echo '<div class="col-md-4" id="favourite_'.$w['item_id'].'" style="  margin-bottom: 10px;">
                <div class="items_similar1">
                <span class="smil_img">
                <a title="" href="#"><img alt="" src="'.Url::to("@web/images/similar1.png").'"></a>
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
                <div class="favourite_right"><a href="javascript:void(0);" title="Delete" onclick="remove_from_fav('.Yii::$app->user->identity->customer_id.','.$w['item_id'].');"> <span class="flaticon-paperbin6"></span></a></div>
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
            $user_event_list = Events::find()
            ->where(['customer_id' => $data['cid']])
            ->andwhere(['like','event_type',$data['type']])
            ->asArray()->all();
            return $this->renderPartial('user_event_list', ['user_event_list' => $user_event_list]);
        }
    }




        public function actionEventdetails($slug = '')
        {
            $event_details = Events::find()->where(['customer_id' => Yii::$app->user->identity->customer_id, 'slug' => $slug])->asArray()->all();
            if (empty($event_details)) {
                throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
            }
            $customer_events_list = Users::get_customer_wishlist_details(Yii::$app->user->identity->customer_id);
			
			$eventitem_details = Eventitemlink::find()->select(['{{%event_item_link}}.item_id'])
			->innerJoin('{{%vendor_item}}', '{{%vendor_item}}.item_id = {{%event_item_link}}.item_id')
			->Where(['{{%vendor_item}}.item_status'=>'Active','{{%vendor_item}}.trash'=>'Default','{{%vendor_item}}.item_for_sale'=>'Yes','{{%vendor_item}}.type_id'=>'2','{{%event_item_link}}.event_id'=>$event_details[0]['event_id']])
			->asArray()
			->all();
            $searchModel = new EventinviteesSearch();
            $dataProvider = $searchModel->loadsearch(Yii::$app->request->queryParams, $slug);

            return $this->render('event_detail', [
                    'slug' => $slug,
                    'event_details' => $event_details,
                    'customer_events_list' => $customer_events_list,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                ]);
        }

            public function actionExcel($slug = '')
            {
                $event_details = Events::find()->where(['customer_id' => Yii::$app->user->identity->customer_id, 'slug' => $slug])->asArray()->all();
                $customer_events_list = Users::get_customer_wishlist_details(Yii::$app->user->identity->customer_id);
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
                    $command = Eventitemlink::deleteAll('link_id='.$data['item_link_id']);
                    if ($command) {
						$cat_list1 = Eventitemlink::find()->select(['{{%event_item_link}}.item_id'])
						->innerJoin('{{%vendor_item}}', '{{%vendor_item}}.item_id = {{%event_item_link}}.item_id')
						->Where(['{{%vendor_item}}.item_status'=>'Active','{{%vendor_item}}.trash'=>'Default','{{%vendor_item}}.item_for_sale'=>'Yes','{{%vendor_item}}.type_id'=>'2','{{%vendor_item}}.category_id'=>$data['category_id'],'{{%event_item_link}}.event_id'=>$data['event_id']])
						->asArray()
						->all();
                            echo count($cat_list1);
                    } else {
                        echo -1;
                    }
                }
            }

}
