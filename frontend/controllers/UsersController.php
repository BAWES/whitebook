<?php

namespace frontend\controllers;

use Yii;
use yii\db\Query;
use yii\helpers\Arrayhelper;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\web\Controller;
use arturoliveira\ExcelView;
use common\models\Country;
use common\models\Location;
use common\models\CustomerAddress;
use common\models\CustomerAddressResponse;
use common\models\FeatureGroupItem;
use common\models\LoginForm;
use common\models\Vendor;
use common\models\City;
use common\models\Events;
use frontend\models\EventInviteesSearch;
use frontend\models\Website;
use frontend\models\EventItemlink;
use frontend\models\Wishlist;
use frontend\models\Users;
use frontend\models\AddressType;
use frontend\models\AddressQuestion;
use frontend\models\Customer;
use frontend\models\Themes;
use frontend\models\VendorItem;
use common\models\VendorItemToCategory;
use common\models\VendorItemThemes;

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

        $request = Yii::$app->request;

        if ($request->post('email') && $request->post('password')) {

            $model->customer_email = $request->post('email');
            $model->customer_password = $request->post('password');

            if($model->login() == Customer::SUCCESS_LOGIN) {
                $return_data['status'] = Customer::SUCCESS_LOGIN;
                return json_encode($return_data);
            } else {
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

        if (!Yii::$app->request->isAjax) 
        {
            Yii::$app->session->set('show_login_modal', '1');
            return $this->redirect(['site/index']);
        }

        Yii::$app->response->format = 'json';

        $data = Yii::$app->request->post();
        
        if(!empty($data['byear']) && !empty($data['bmonth']) && !empty($data['bday'])) {
            $model->customer_dateofbirth = $data['byear'].'-'.$data['bmonth'].'-'.$data['bday'];    
        }
        
        $model->customer_activation_key = Users::generateRandomString();
        $model->created_datetime = date('Y-m-d H:i:s');
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) 
        {               
            $model->customer_password = Yii::$app->getSecurity()->generatePasswordHash($model->customer_password);
            
            $model->save(false);

            $username = $model['customer_name'];
            
            Yii::$app->session->set('register', '1');
            
            //Send Email to user
            Customer::welcomeMail($model);

            return [
                'operation' => 'success'
            ];
        } 
        else 
        {
            return [
                'operation' => 'error',
                'message' => $model->getErrors()
            ];
        }
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
        $request = Yii::$app->request;

        if ($request->post('_csrf') && $request->post('email')) {

            $model = new Users();
            $email = $request->post('email');
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

                return Users::SUCCESS;
            } else {
                return Users::EMAIL_NOT_EXIST;
            }
        }
    }

    public function actionPassword_reset()
    {
        $request = Yii::$app->request;

        if (Yii::$app->request->isAjax) {

            if ($request->post('id') && $request->post('password')) {
            
                $reset_password = Yii::$app->session->set('reset_password_mail', '');
                $final_reset = Yii::$app->session->set('final_reset', '');
                
                $model = new Users();
                
                $customer_activation_key = $request->post('id');
                $password = $request->post('password');
                
                $user_email = Customer::find()
                    ->select('customer_email')
                    ->where(['customer_activation_key'=>$customer_activation_key])
                    ->asArray()
                    ->one();

                $check_user = $model->customer_password_reset($password, $customer_activation_key, $user_email);
                
                $val = Users::FAILURE; // Password reset failure

                if (count($check_user) > 0) { 

                    $model = new Customer();
                    $model->scenario = 'login';

                    $request = Yii::$app->request;

                    $model->customer_email = $user_email['customer_email'];
                    $model->customer_password = $request->post('password');

                    if($model->login() == Customer::SUCCESS_LOGIN) {
                        $val = Customer::SUCCESS_LOGIN;
                    } else {
                        $val = $model->login();
                    }
                }

                return $val;
            }
        }
    }

    public function actionEmail_check()
    {
        $request = Yii::$app->request;

        if ($request->post('_csrf') && $request->post('email')) {
            $model = new Users();

            $check_email = $model->check_email_exist($request->post('email'));
            
            if (count($check_email) > 0) {
                return Users::SUCCESS; // Email exist
            } else {
                return Users::FAILURE; // Email does not exist
            }
        }
    }

    /**
     * Account settings for login customer   
     */
    public function actionAccount_settings()
    {
        if (Yii::$app->user->isGuest) {
            Yii::$app->session->set('show_login_modal', 1);//to display login modal
            return $this->goHome();
        }

        $model = Customer::findOne(Yii::$app->user->getId());

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            
            Yii::$app->getSession()->setFlash('success', Yii::t(
                'frontend',
                'Success: Account updated successfully!'
            ));
        }

        return $this->render('account-settings', [
            'model' => $model
        ]);
    }


    public function actionAdd_to_wishlist()
    {
        $request = Yii::$app->request;

        if(Yii::$app->user->isGuest || empty($request->post('item_id')))
        {
            throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
        }

        $model = new Users();
        $item_id = $request->post('item_id');
        $customer_id = Yii::$app->user->identity->customer_id;

        $update_wishlist = $model->update_wishlist($item_id, $customer_id);
        
        if ($update_wishlist == 1) {
            $wishlist = Users::loadCustomerWishlist(Yii::$app->user->identity->customer_id);
            return  count($wishlist);
        } else {
            $wishlist = Users::loadCustomerWishlist(Yii::$app->user->identity->customer_id);
            return count($wishlist);
        }
    }

//    public function actionEvents($type = '', $events ='', $thingsilike ='')
//    {
//        if (Yii::$app->user->isGuest) {
//            Yii::$app->session->set('show_login_modal', 1);//to display login modal
//            return $this->goHome();
//        }
//
//        $request = Yii::$app->request;
//
//        $events = ($request->get('slug') == 'events' ? 'active' : '');
//        $thingsilike = ($request->get('slug') ==  'thingsilike' ?  'active' : '');
//
//        $customer_id = Yii::$app->user->getId();
//
//        $website_model = new Website();
//        $event_type = $website_model->get_event_types();
//
//        $model = new Users();
//        $event_limit = 8;
//        $wish_limit = 6;
//        $offset = 0;
//
//        $customer_events = $model->getCustomerEvents($customer_id, $event_limit, $offset, $type);
//        $customer_events_count = $model->get_customer_events_count($customer_id, $type);
//        $price = $vendor = $avail_sale = $theme = '';
//
//        $themes = $model->get_themes();
//        $customer_unique_events = $website_model->getCustomerEvents($customer_id);
//        $customer_event_type = $website_model->get_user_event_types($customer_id);
//
//        $wishlist = Wishlist::find()
//            ->where(['customer_id' => $customer_id])
//            ->all();
//
//        $arr_item_id = Arrayhelper::map($wishlist, 'item_id', 'item_id');
//
//        $categorylist = VendorItemToCategory::find()
//            ->select('{{%category}}.category_id, {{%category}}.category_name, {{%category}}.category_name_ar')
//            ->joinWith('category')
//            ->where(['IN', '{{%vendor_item_to_category}}.item_id', $arr_item_id])
//            ->asArray()
//            ->all();
//
//        $vendorlist = Vendoritem::find()
//            ->select('{{%vendor}}.vendor_name, {{%vendor}}.vendor_name_ar, {{%vendor}}.vendor_id')
//            ->joinWith('vendor')
//            ->where(['IN', '{{%vendor_item}}.item_id', $arr_item_id])
//            ->asArray()
//            ->all();
//
//        $themelist =  VendorItemThemes::find()
//            ->select('{{%theme}}.theme_id, {{%theme}}.theme_name, {{%theme}}.theme_name_ar')
//            ->joinWith('themeDetail')
//            ->where(['trash' => 'default'])
//            ->where(['IN', '{{%vendor_item_theme}}.item_id', $arr_item_id])
//            ->asArray()
//            ->all();
//
//        $avail_sale = $category_id = $vendor = $theme = '';
//
//        $customer_wishlist = $model->get_customer_wishlist(
//            $customer_id, $wish_limit, $offset, $category_id, $price, $vendor, $avail_sale, $theme);
//
//        $customer_wishlist_count = $model->get_customer_wishlist_count(
//            $customer_id, $category_id, $price, $vendor, $avail_sale, $theme);
//
//        $user_events = Events::find()
//            ->where(['customer_id' => Yii::$app->user->identity->customer_id])
//            ->asArray()
//            ->all();
//
//        return $this->render('events', [
//            'event_type' => $event_type,
//            'customer_event_type' => $customer_event_type,
//            'customer_events' => $customer_events,
//            'customer_events_count' => $customer_events_count,
//            'customer_wishlist' => $customer_wishlist,
//            'customer_wishlist_count' => $customer_wishlist_count,
//            'vendor' => $vendor,
//            'customer_unique_events' => $customer_unique_events,
//            'categorylist' => $categorylist,
//            'vendorlist' => $vendorlist,
//            'themelist' => $themelist,
//            'slug' => 'events',
//            'events' => $events,
//            'thingsilike' => $thingsilike,
//        ]);
//    }

    public function actionLoad_more_events()
    {
        $request = Yii::$app->request;

        $limit = $request->get('limit');
        $offset = $request->get('offset');
        $type = $request->get('type');

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
        $request = Yii::$app->request;

        $limit = $request->get('limit');
        $offset = $request->get('offset');
        $category_id = $request->get('category');
        $price = $request->get('price');
        $vendor = $request->get('vendor');
        $avail_sale = $request->get('available_for_sale');
        $theme = $request->get('theme');

        $customer_id = Yii::$app->user->identity->customer_id;

        $model = new Users();
        
        $customer_wishlist = $model->get_customer_wishlist(
            $customer_id, 
            $limit, 
            $offset, 
            $category_id, 
            $price, 
            $vendor, 
            $avail_sale, 
            $theme
        );

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
                ->asArray()
                ->all();
            
            return $this->renderPartial('user_event_list', ['user_event_list' => $user_event_list]);
        }
    }


    public function actionDeleteeventitem()
    {
        if (!Yii::$app->request->isAjax) {
            throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
        }

        Yii::$app->response->format = 'json';
            
        $data = Yii::$app->request->post();

        //check if login customer's link
        $event = Events::find()
            ->where(['customer_id' => Yii::$app->user->getId()])
            ->one();

        if($event) {

            $model = EventItemlink::find()
                ->where([
                    'link_id' => $data['item_link_id'],
                    'event_id'=> $data['event_id']
                ])
                ->one();

            //to remove multiple entry of same item in same event 
            if($model) 
            {
                EventItemlink::deleteAll([
                    'item_id' => $model->item_id,
                    'event_id'=> $data['event_id']
                ]);    
            }  

            //get item count 
            $count = EventItemlink::find()
                ->leftJoin('{{%vendor_item}}', '{{%event_item_link}}.item_id = {{%vendor_item}}.item_id')
                ->leftJoin('{{%vendor}}', '{{%vendor}}.vendor_id = {{%vendor_item}}.vendor_id')
                ->leftJoin(
                    '{{%vendor_item_to_category}}', 
                    '{{%vendor_item_to_category}}.item_id = {{%vendor_item}}.item_id'
                )                            
                ->leftJoin(
                    '{{%category_path}}', 
                    '{{%category_path}}.category_id = {{%vendor_item_to_category}}.category_id'
                )
                ->where([
                    '{{%vendor_item}}.item_status' => 'Active',
                    '{{%vendor_item}}.trash' => 'Default',
                    '{{%category_path}}.path_id' => $data['category_id'],
                    '{{%event_item_link}}.trash' => 'Default',
                    '{{%event_item_link}}.event_id' => $data['event_id']
                ])
                ->groupBy('{{%event_item_link}}.item_id')
                ->count();

            return [
                'success' => Yii::t('frontend', 'Success! Item removed.'),
                'count' => $count
            ];
        }

        return [
            'error' => Yii::t('frontend', 'Error! Something went wrong.')
        ];
    }

    /**
     * Displays all address
     *
     * @return mixed
     */
    public function actionAddress()
    {
        $customer_id = Yii::$app->user->getId();
        
        if ($customer_id == '') {
            Yii::$app->session->set('show_login_modal', 1);//to display login modal
            return $this->goHome();
        }
        
        if(Yii::$app->request->isPost) {

            $questions = Yii::$app->request->post('question');

            if(!$questions) {
                $questions = array();
            }

            //save address
            $customer_address = new CustomerAddress();          
            $customer_address->load(Yii::$app->request->post());            
            $customer_address->customer_id = $customer_id;
            
            $location = Location::findOne($customer_address->area_id);

            $customer_address->city_id = $location->city_id;
            $customer_address->country_id = $location->country_id;
            $customer_address->save(false);
          
            $address_id = $customer_address->address_id;

            //save address questions 
            foreach ($questions as $key => $value) {
                $customer_address_response = new CustomerAddressResponse();
                $customer_address_response->address_id = $address_id;
                $customer_address_response->address_type_question_id = $key;
                $customer_address_response->response_text = $value;
                $customer_address_response->save();
            }
            Yii::$app->session->setFlash('success','Address Added Successfully');
        }

        $addresses = array();

        $result = CustomerAddress::find()
            ->select('whitebook_city.city_name, whitebook_city.city_name_ar, whitebook_location.location, 
                whitebook_location.location_ar, whitebook_customer_address.*')
            ->leftJoin('whitebook_location', 'whitebook_location.id = whitebook_customer_address.area_id')
            ->leftJoin('whitebook_city', 'whitebook_city.city_id = whitebook_customer_address.city_id')
            ->where('customer_id = :customer_id', [':customer_id' => $customer_id])
            ->asArray()
            ->all();

        foreach($result as $row) {

            $row['questions'] = CustomerAddressResponse::find()
              ->select('aq.question_ar, aq.question, whitebook_customer_address_response.*')
              ->innerJoin('whitebook_address_question aq', 'aq.ques_id = address_type_question_id')
              ->where('address_id = :address_id', [':address_id' => $row['address_id']])
              ->asArray()
              ->all();

            $addresses[] = $row;
        }

        $provider = new \yii\data\ArrayDataProvider([
            'allModels' => $addresses,
            'sort' => [
                'attributes' => ['city_name', 'address_type_id', 'location'],
            ],
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        $customer_address_modal = new CustomerAddress();
        $addresstype = AddressType::loadAddresstype();

        return $this->render('address', [
            'addresses' => $addresses,
            'customer_address_modal' => $customer_address_modal,
            'addresstype' => $addresstype,
            'provider' => $provider
        ]);
    }

    /**
     * Display customer address form to edit and save address on post 
     * @param integer $address_id and POST data 
     */
    public function actionEditAddress($address_id)
    {
        $customer_id = Yii::$app->user->getId();
        
        if ($customer_id == '') {
            Yii::$app->session->set('show_login_modal', 1);//to display login modal
            return $this->goHome();
        }
        
        $customer_address = CustomerAddress::findone([
            'address_id' => $address_id, 
            'customer_id' => $customer_id
        ]);

        if(!$customer_address) {
            throw new \yii\web\NotFoundHttpException();
        }
        
        if(Yii::$app->request->isPost) {

            $questions = Yii::$app->request->post('question');

            if(!$questions) {
                $questions = array();
            }

            //save address               
            $customer_address->load(Yii::$app->request->post());            
            
            $location = Location::findOne($customer_address->area_id);

            $customer_address->city_id = $location->city_id;
            $customer_address->country_id = $location->country_id;
            $customer_address->save(false);
          
            //remove old questions 
            CustomerAddressResponse::deleteAll(['address_id' => $address_id]);

            //save address questions 
            foreach ($questions as $key => $value) {
                $customer_address_response = new CustomerAddressResponse();
                $customer_address_response->address_id = $address_id;
                $customer_address_response->address_type_question_id = $key;
                $customer_address_response->response_text = $value;
                $customer_address_response->save();
            }
            Yii::$app->session->setFlash('success','Address updated successfully');
            return $this->redirect(['users/address']);
        }

        //display edit form 
        return $this->render('address_edit', [
            'address' => $customer_address,
            'address_id' => $address_id,
            'addresstype' => AddressType::loadAddresstype()
        ]);
    }

    public function actionViewAddress($address_id)
    {
        $customer_id = Yii::$app->user->getId();

        if ($customer_id == '') {
            Yii::$app->session->set('show_login_modal', 1);//to display login modal
            return $this->goHome();
        }

        $customer_address = CustomerAddress::findone([
            'address_id' => $address_id,
            'customer_id' => $customer_id
        ]);

        $questions = CustomerAddressResponse::find()
            ->select('aq.question_ar, aq.question, whitebook_customer_address_response.*')
            ->innerJoin('whitebook_address_question aq', 'aq.ques_id = address_type_question_id')
            ->where('address_id = :address_id', [':address_id' => $address_id])
            ->asArray()
            ->all();

        if(!$customer_address) {
            throw new \yii\web\NotFoundHttpException();
        }

        //display edit form
        return $this->render('address-view', [
            'address' => $customer_address,
            'questions' => $questions,
        ]);
    }

    /**
     * Delete customer address
     * @param integer $address_id
     */
    public function actionAddressDelete($address_id)
    {
        $customer_id = Yii::$app->user->identity->customer_id;
        
        if ($customer_id == '') {
            return $this->goHome();
        }
  
        $address_id = yii::$app->request->get('address_id');

        //check if address belong to login customer 
        $exist = CustomerAddress::find()
                    ->where(['address_id' => $address_id, 'customer_id' => $customer_id])
                    ->one();

        if ($exist) {
            CustomerAddressResponse::deleteAll('address_id = ' . $address_id);
            CustomerAddress::deleteAll('address_id = ' . $address_id);    
        }
        Yii::$app->session->setFlash('success','Address deleted successfully');
        return $this->redirect(['users/address']);
    }

    /**
     * Returns the Question form for address for specific address type 
     * @param integer $address_id, integer $address_type_id 
     */
    public function actionQuestions()
    {
        $address_type_id = Yii::$app->request->post('address_type_id');
        $address_id = Yii::$app->request->post('address_id');

        if($address_id) {
       
            $questions = array();

            //get questions
            $result = AddressQuestion::find()
                ->where([
                    'address_type_id' => $address_type_id,
                    'trash' => 'Default',
                    'status' => 'Active'])
                ->asArray()
                ->all();

            //get questions response 
            foreach ($result as $value) {
                
                $response = CustomerAddressResponse::findone([
                    'address_id' => $address_id,
                    'address_type_question_id' => $value['ques_id']
                ]);

                if($response) {
                    $value['response_text'] = $response->response_text;
                }else{
                    $value['response_text'] = '';
                }
                
                $questions[] = $value;
            }       

        } else {
            
            $questions = AddressQuestion::find()
                ->where([
                    'address_type_id' => $address_type_id,
                    'trash' => 'Default',
                    'status' => 'Active'])
                ->asArray()
                ->all();
        }        

        //get area 

        $delivery_location = Yii::$app->session->get('delivery-location');

        $area = Location::findOne($delivery_location);

        //get area name 

        $area_name = '';

        if($area && Yii::$app->language == 'en')
        {
            $area_name = $area->location;
        }

        if($area && Yii::$app->language == 'ar')
        {
            $area_name = $area->location_ar;
        }

        return $this->renderPartial('questions', [
            'questions' => $questions,
            'area_name' => $area_name
        ]);
    }

    public function actionVendorRequest(){
        $request = Yii::$app->request;

        if ($request->post('_csrf') && $request->post('name_of_business')) {
            $business = $request->post('name_of_business');
            $name = $request->post('contact_person');
            $mobile = $request->post('phone');
            $email = $request->post('email');
            $license = $request->post('license');
            $description = $request->post('description');

            return $send = Yii::$app->mailer->compose("admin/vendor-request",
                [
                    "business"=>$business,"name"=>$name,'mobile'=>$mobile,
                    'email'=>$email,'license'=>$license,'description'=>$description
                ])
                ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->params['SITE_NAME']])
                ->setTo(Yii::$app->params['adminEmail'])
                ->setSubject('Vendor Registration Request')
                ->send();

        } else {
            return 0;
            exit;
        }
    }
}