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
use frontend\models\Eventitemlink;
use yii\helpers\Arrayhelper;
use yii\helpers\Url;
use common\models\Events;
use frontend\models\Users;



/**
* Site controller.
*/

class UsersController extends BaseController
{
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
            //return ($model->login());
            if($model->login() == Customer::SUCCESS_LOGIN) {
                    $return_data['status'] = Customer::SUCCESS_LOGIN;
                    return json_encode($return_data);
                }
                else
                {
                    $return_data['status'] = $model->login();
                    echo json_encode($return_data);
                }
        } else {
            return $this->redirect(Yii::$app->request->referrer);
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
        $model->scenario = 'signup';
        $error = array();
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $model->customer_password = Yii::$app->getSecurity()->generatePasswordHash($data['customer_password']);
            $model->confirm_password=$data['confirm_password'];
            $model->customer_dateofbirth = $data['byear'].'-'.$data['bmonth'].'-'.$data['bday'];
            $model->customer_activation_key = Users::generateRandomString();
            $model->created_datetime = date('Y-m-d H:i:s');
            $model->customer_name=$data['customer_name'];
            $model->customer_last_name=$data['customer_last_name'];
            $model->customer_email=$data['customer_email'];
            $model->customer_gender=$data['customer_gender'];
            $model->customer_mobile=$data['customer_mobile'];
            if ($model->validate() && $model->save()) {
                    $siteinfo = Siteinfo::find()->asArray()->all();
                    $username = $model['customer_name'];
                    Yii::$app->session->set('register', '1');
                    $message = 'Thank you for registration with us.</br><a href='.Url::to(['/users/confirm_email', 'key' => $model->customer_activation_key], true).' title="Click Here">Click here </a> to activate your account.';
                    //Send Email to user
                    $send_user = Yii::$app->mailer->compose
                    (["html"=>"customer/welcome"],
                     ["message"=>$message,"user"=>$model->customer_name])
                    ->setFrom(Yii::$app->params['supportEmail'])
                    ->setTo($model['customer_email'])
                    ->setSubject('TheWhiteBook registration successfull')
                    ->send();
                 //Send Email to admin
                    $message_admin = $model->customer_name.' registered in TheWhiteBook';
                    $send_admin = Yii::$app->mailer->compose
                    (["html"=>"customer/user-register"],
                     ["message"=>$message_admin])
                    ->setFrom(Yii::$app->params['supportEmail'])
                    ->setTo(Yii::$app->params['adminEmail'])
                    ->setSubject('User registered')
                    ->send();
                    $this->redirect(Url::to('site/index'));
                    return Users::SUCCESS;
                    } else {
                    Yii::$app->session->setFlash('error', 'Signup Failed!');
                    return Users::FAILURE;
                    $this->redirect(Url::to('site/index'));
                }
            }
            return $this->render('/users/signup', [
            'model' => $model,
            'error' => $error,
        ]);
    }

    public function actionReset_confirm($cust_id)
    {
        $model = new Users();
        $check = $model->check_customer_validtime($cust_id);
        if (!empty($check)) {
            Yii::$app->session->set('reset_password_mail', $cust_id);
        } else {
            Yii::$app->session->set('reset_password_mail', '1');
        }
        return $this->goHome();
    }

    public function actionConfirm_email($key)
    {
        $model = new Users();
        $check_key = $model->check_valid_key($key);
        if ($check_key == Users::KEY_NOT_MATCH) {
            return $this->goHome();
        } else {
            $login_det = $model->customer_logindetail($key);
            $email = $login_det[0]['customer_email'];
            $authorization = $model->check_authorization($email);
            if($authorization)
            {
                Yii::$app->session->set('key', '1'); // To activate user
                //Consider replacing this with setFlash for a temporary session var 
            }
            return $this->redirect(Url::toRoute('/site/activate'));
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
                $message = 'Your requested password reset.</br><a href='.Url::to(["/users/reset_confirm", "cust_id" => $id[0]["customer_activation_key"]], true).' title="Click Here">Click here </a> to reset your password';
                $send = Yii::$app->mailer->compose("customer/password-reset",
                    ["message"=>$message,"user"=>"Customer"])
                ->setFrom(Yii::$app->params['supportEmail'])
                ->setTo($email)
                ->setSubject('Requested forgot Password')
                ->send();
                Yii::$app->session->setFlash('success', Yii::t('frontend', 'PASS_SENT'));
                return Users::SUCCESS;
            } else {
                return Users::EMAIL_NOT_EXIST;
            }
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
                $user_email = Customer::find()->select('customer_email')->
                where(['customer_activation_key'=>$customer_activation_key])
                ->asArray()
                ->one();
                $check_user = $model->customer_password_reset($password, $customer_activation_key,$user_email);
                $val = Users::FAILURE; // Password reset failure
                if (count($check_user) > 0) {
                    $_POST['email'] = $user_email['customer_email'];
                    $loginResult = $this->actionLogin();
                    $val = Users::SUCCESS; // Password reset successfully
                }
                return $val;
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
                return Users::SUCCESS; // Email exist
            } else {
                return Users::FAILURE; // Email does not exist
            }
        }
    }
    public function actionAccount_settings()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $country = Country::loadcountry();

        $model = new Users();
        $user_detail = $model->get_user_details();

        if (!empty($customer_detail['country_id'])) {
            $city = City::listcityname($customer_detail['country_id']);
        } else {
            $city = City::fullcityname();
        }
        return $this->render('account-settings', ['user_detail' => $user_detail, 'loadcountry' => $country, 'loadcity' => $city]);
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
            if ($update_customer) {
                return Users::SUCCESS; // User profile updated successfully
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
         				$result = Events::EVENT_ALREADY_EXIST;
         			} else {
         			$event_modal=new Events;
         			$event_modal->customer_id=$customer_id;
         			$event_modal->event_name=$event_name;
         			$event_modal->event_date=$event_date1;
         			$event_modal->event_type=$_POST['event_type'];
         			$event_modal->slug=$slug;
         			$event_modal->save();
         			$result=$event_modal->event_id;
         			}
            // Creating event end

            if ($result == Events::EVENT_ALREADY_EXIST) {
                return Events::EVENT_ALREADY_EXIST;
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
           			        return Eventitemlink::EVENT_ITEM_LINK_EXIST;
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
                       return Eventitemlink::EVENT_ITEM_CREATED; 
                   }
                }
                Yii::$app->session->setFlash('success', Yii::t('frontend', 'EVE_CRE_SUCC'));
                return Events::EVENT_CREATED; 
            }
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
            if ($add_event ==  Events::EVENT_ALREADY_EXIST) {
                return  Events::EVENT_ALREADY_EXIST;
            } else {
                Yii::$app->session->setFlash('success', Yii::t('frontend', 'EVENT_UPDATED_SUCCESSFULLY'));
                return $add_event;
            }
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
            if ($insert_item_to_event == Events::EVENT_ALREADY_EXIST) {
                return Events::EVENT_ALREADY_EXIST;
            } elseif ($insert_item_to_event == Events::EVENT_ADDED_SUCCESS) {
                Yii::$app->session->setFlash('success', Yii::t('frontend', 'EVE_CRE_AD_SUCC'));
                return Events::EVENT_CREATED;
            }
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
                return  count($wishlist);
            } else {
                $wishlist = Users::loadCustomerWishlist(Yii::$app->user->identity->customer_id);
                return count($wishlist);
            }
        } else {
            return $this->goHome();
        }
    }

    public function actionEvents($type = '', $events ='', $thingsilike ='')
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        /* Begin events & things i like active tabs */
        $events = ($_GET['slug'] == 'events' ? 'active' : '');
        $thingsilike =  ($_GET['slug'] ==  'thingsilike' ?  'active' : '');
        /* End active tabs */

        $customer_id = Yii::$app->user->identity->customer_id;

        $website_model = new Website();
        $event_type = $website_model->get_event_types();

        $model = new Users();
        $event_limit = 8;
        $wish_limit = 6;
        $offset = 0;

        $customer_events = $model->getCustomerEvents($customer_id, $event_limit, $offset, $type);
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

        return $this->render('events', [
            'event_type' => $event_type,
            'customer_event_type' => $customer_event_type,
            'customer_events' => $customer_events,
            'customer_events_count' => $customer_events_count,
            'customer_wishlist' => $customer_wishlist,
            'customer_wishlist_count' => $customer_wishlist_count,
            'vendor' => $vendor, 'category' => $categorylist,
            'themes' => $themes,
            'customer_unique_events' => $customer_unique_events,
            'categorylist' => $categorylist,
            'vendorlist' => $vendorlist,
            'themelist' => $themelist,
            'slug'=>'events',
            'events'=>$events,
            'thingsilike'=>$thingsilike,
        ]);
    }

    public function actionRemove_from_wishlist()
    {
        if (Yii::$app->request->isAjax) {
            if (isset($_POST['item_id'])) {
                $model = new Users();
                $item_id = $_POST['item_id'];
                $customer_id = Yii::$app->user->identity->customer_id;
                $delete_wishlist = $model->delete_wishlist($item_id, $customer_id);
                if ($delete_wishlist == Users::SUCCESS) {
                    return Users::SUCCESS; // Wish list deleted successfully
                } else {
                  return Users::FAILURE; // Wish list not deleted
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
     		->Where(['{{%vendor_item}}.item_status'=>'Active',
                 '{{%vendor_item}}.trash'=>'Default',
                 '{{%vendor_item}}.item_for_sale'=>'Yes',
                 '{{%vendor_item}}.type_id'=>'2',
                 '{{%event_item_link}}.event_id'=>$event_details[0]['event_id']])
     		->asArray()
     		->all();
        $searchModel = new EventinviteesSearch();

        $dataProvider = $searchModel->loadsearch(Yii::$app->request->queryParams, $slug);

        /* Load level 1 category */
        $cat_exist = \frontend\models\Category::find()
        ->where(['category_level' =>0,'category_allow_sale' =>'Yes','trash' =>'Default','category_level' =>'0'])
        ->orderBy(new \yii\db\Expression('FIELD (category_name, "Venues", "Invitations", "Food & Beverages", "Decor", "Supplies", "Entertainment", "Services", "Others", "Say thank you")'))
        ->asArray()->all();
        return $this->render('event_detail', [
                'slug' => $slug,
                'event_details' => $event_details,
                'customer_events_list' => $customer_events_list,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'cat_exist'=>$cat_exist
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
            return count($cat_list1);
            } else {
                return Users::SUCCESS; // Event item removed successfully
            }
        }
    }

}
