<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use common\models\Booking;
use common\models\CustomerCart;
use common\models\CustomerAddress;
use common\models\CustomerAddressResponse;
use common\models\Country;
use common\models\Location;
use common\models\PaymentGateway;
use common\models\CustomerCartMenuItem;
use common\models\Order;
use frontend\models\AddressType;
use frontend\models\Customer;

/**
 * Checkout controller.
 */
class CheckoutController extends BaseController
{
	public function actionIndex()
	{
		\Yii::$app->view->title = Yii::$app->params['SITE_NAME'].' | Checkout';
		\Yii::$app->view->registerMetaTag(['name' => 'description', 'content' => Yii::$app->params['META_DESCRIPTION']]);
		\Yii::$app->view->registerMetaTag(['name' => 'keywords', 'content' => Yii::$app->params['META_KEYWORD']]);

		//validate cart
		foreach (CustomerCart::items() as $item) {
            
            $menu_items = CustomerCartMenuItem::find()->cartID($item['cart_id'])->all();

    		$errors = CustomerCart::validate_item([
    			'item_id' => $item['item_id'],
                'time_slot' => Yii::$app->session->get('event_time'),
                'delivery_date' => Yii::$app->session->get('delivery-date'),
                'area_id' => Yii::$app->session->get('delivery-location'),
    			'quantity' => $item['cart_quantity'],
                'menu_item' => ArrayHelper::map($menu_items, 'menu_item_id', 'quantity')
    		], true);

    		if($errors) { 
    			$this->redirect(['cart/index']);
    		}
    	}

        return $this->render('index');
	}

    //Display form to login 
    public function actionLogin(){

        $model = new Customer();
        $model->scenario = 'login';

        $request = Yii::$app->request;

        if ($request->post('type') == 'login' && $model->load($request->post())) {

            if($model->login() == Customer::SUCCESS_LOGIN) {
                $json['status'] = Customer::SUCCESS_LOGIN;
            } else {
                $json['status'] = 0;
                $json['errors'] = $model->getErrors();
            }  

            Yii::$app->response->format = 'json';
            return $json;
        }

        if ($request->post('type') == 'guest') {

            $model->customer_email = $request->post('email');
            $model->customer_password = $request->post('password');

            if($model->login() == Customer::SUCCESS_LOGIN) {
                $json['status'] = Customer::SUCCESS_LOGIN;
            } else {
                $json['status'] = $model->login();
            }

            Yii::$app->response->format = 'json';
            return $json;
        } 

        return $this->renderPartial('login', ['model' => $model]);
    }

	//Display form to select delivery address 
	public function actionAddress(){
		
		$items = CustomerCart::items();

		foreach ($items as $item) {
            
            $menu_items = CustomerCartMenuItem::find()->cartID($item['cart_id'])->all();

            $error = CustomerCart::validate_item([
    			'item_id' => $item['item_id'],
                'time_slot' => Yii::$app->session->get('event_time'),
    			'delivery_date' => Yii::$app->session->get('delivery-date'),
                'area_id' => Yii::$app->session->get('delivery-location'),
    			'quantity' => $item['cart_quantity'],
                'menu_item' => ArrayHelper::map($menu_items, 'menu_item_id', 'quantity')
    		], true);

    		if($error) {
    			return null;
    		}
		}

		$customer_address_modal = new CustomerAddress();
        $addresstype = AddressType::loadAddresstype();
        $country = Country::loadcountry();

        $customer_address_modal->address_type_id = 1;

        if(Yii::$app->user->isGuest) 
        {
            $template = 'address_guest';
        } 
        else 
        {
            $template = 'address';            
        }

        return $this->renderPartial($template, [
            'items' => $items,
            'customer_address_modal' => $customer_address_modal,
            'addresstype' => $addresstype,
            'country' => $country
        ]);   
	}

	public function actionAddAddress() {

        $area_id = Yii::$app->session->get('delivery-location');

		$json = array();

        $questions = Yii::$app->request->post('question');

        if(!$questions) {
            $questions = array();
        }

		$customer_address = new CustomerAddress();
          
        $customer_address->load(Yii::$app->request->post());
          
        $customer_address->customer_id = Yii::$app->user->getId();

        //get are id from cart_id 
        $cart_item = CustomerCart::find()
            ->customer(Yii::$app->user->getId())
            ->one();

        $customer_address->area_id = $area_id;

        //get city & country from area 
        $location = Location::findOne($customer_address->area_id);

        $customer_address->city_id = $location->city_id;
        $customer_address->country_id = $location->country_id;

        if ($customer_address->save()) {

            $address_id = $customer_address->address_id;

            //save customer address response 
            foreach ($questions as $key => $value) {
                $customer_address_response = new CustomerAddressResponse();
                $customer_address_response->address_id = $address_id;
                $customer_address_response->address_type_question_id = $key;
                $customer_address_response->response_text = $value;                                        
                $customer_address_response->save();
            }
            
        }else{
            $json['errors'] = $customer_address->getErrors();
        }

        Yii::$app->response->format = Response::FORMAT_JSON;

        return $json;
	}

    /** 
     * Save guest info + address 
     */ 
    public function actionSaveGuestAddress() 
    {
        $area_id = Yii::$app->session->get('delivery-location');

        //save address 

        $json = array('errors' => array());

        $questions = Yii::$app->request->post('question');

        if(!$questions) {
            $questions = array();
        }

        $customer_address = new CustomerAddress();
          
        $customer_address->load(Yii::$app->request->post());
        
        //get are id from cart_id 
        $cart_item = CustomerCart::find()
            ->sessionUser()
            ->one();

        $customer_address->area_id = $area_id;

        //get city & country from area 
        $location = Location::findOne($customer_address->area_id);

        $customer_address->city_id = $location->city_id;
        $customer_address->country_id = $location->country_id;

        if ($customer_address->save()) {
            
            $address_id = $customer_address->address_id;

            //save customer address response 
            foreach ($questions as $key => $value) {

                if(!$value) 
                    continue;
                
                $customer_address_response = new CustomerAddressResponse();
                $customer_address_response->address_id = $address_id;
                $customer_address_response->address_type_question_id = $key;
                $customer_address_response->response_text = $value;                                        
                $customer_address_response->save();
            }

        } else {
            $json['errors'] = $customer_address->getErrors();
        }

        // save customer info 
        $customer = new Customer();
        $customer->scenario = 'guest';
        $customer->load(Yii::$app->request->post());

        if(!$customer->validate()) {
            $json['errors'] = array_merge($json['errors'], $customer->getErrors());
        }

        if(!$json['errors']) 
        {
            unset($json['errors']);

            //save customer info 

            Yii::$app->session->set('customer_name', $customer->customer_name);
            Yii::$app->session->set('customer_lastname', $customer->customer_last_name);
            Yii::$app->session->set('customer_email', $customer->customer_email); 
            Yii::$app->session->set('customer_mobile', $customer->customer_mobile); 

            //save address 

            Yii::$app->session->set('address_id', $customer_address->address_id);
        }

        Yii::$app->response->format = Response::FORMAT_JSON;

        return $json;
    }

	//save address 
	public function actionSaveAddress() {

		Yii::$app->response->format = Response::FORMAT_JSON;

        $json = array();

        $address_id = Yii::$app->request->post('address_id');

        if(empty($address_id)) 
        {
            return [
                'errors' => Yii::t('frontend', 'Please select address')
            ];
        }

		Yii::$app->session->set('address_id', $address_id);

		return $json;
	}

	//Display form to select payment method 
	public function actionPayment(){

		$items = CustomerCart::items();

		foreach ($items as $item) {
            
            $menu_items = CustomerCartMenuItem::find()->cartID($item['cart_id'])->all();

			$error = CustomerCart::validate_item([
    			'item_id' => $item['item_id'],
                'working_id' => $item['working_id'],
                'time_slot' => Yii::$app->session->get('event_time'),
    			'delivery_date' => Yii::$app->session->get('delivery-date'),
                'working_end_time' => $item['working_end_time'],
    			'area_id' => Yii::$app->session->get('delivery-location'),
    			'quantity' => $item['cart_quantity'],
                'menu_item' => ArrayHelper::map($menu_items, 'menu_item_id', 'quantity')
    		], true);

    		if($error) {
    			return null;
    		}
		}

        $payment_gateway = PaymentGateway::find()
            ->active()
            ->all();

		return $this->renderPartial('payment', [
            'payment_gateway' => $payment_gateway
        ]);
	}

	//validate payment method
	public function actionSavePayment(){
		
        $json = [];

		$payment_method = Yii::$app->request->post('payment_method');

		if($payment_method) {
            
            Yii::$app->session->set('payment_method', $payment_method);

        } else {

            $json['error'] = Yii::t('frontend', 'Please, select payment method!');
            
            Yii::$app->response->format = Response::FORMAT_JSON;

            return $json;
        }
	}

	//Display all data for order to get confirm 
	public function actionConfirm() {

		$items = CustomerCart::items();
		
		foreach ($items as $item) {
            $menu_items = CustomerCartMenuItem::find()->cartID($item['cart_id'])->all();

			$error = CustomerCart::validate_item([
    			'item_id' => $item['item_id'],
                'time_slot' => Yii::$app->session->get('event_time'),
    			'delivery_date' => Yii::$app->session->get('delivery-date'),
                'area_id' => Yii::$app->session->get('delivery-location'),
    			'quantity' => $item['cart_quantity'],
                'menu_item' => ArrayHelper::map($menu_items, 'menu_item_id', 'quantity')
    		], true);

    		if($error) {
    			return null;
    		}
		}

		$payment_method_code = Yii::$app->session->get('payment_method');

		$address = Yii::$app->session->get('address');

		$items = CustomerCart::items();

        return $this->renderPartial('confirm', [
            'delivery_date' => Yii::$app->session->get('delivery-date'),
            'event_time'  => Yii::$app->session->get('event_time'),
            'items' => $items,
            'address' => $address,
            'pg_link' => Url::to(['checkout/request-send'])
        ]);
	}

    public function actionSuccess() {

        // clear cart
        if(Yii::$app->user->isGuest) 
        {
            CustomerCart::deleteAll('cart_session_id = "'.Customer::currentUser().'"');
        }
        else
        {
            CustomerCart::deleteAll('customer_id = "'.Yii::$app->user->getId().'"');
        }
        
        // clear temp session data
        Yii::$app->session->remove('payment_method');
        Yii::$app->session->remove('address');

        $arr_booking_id = Yii::$app->session->get('arr_booking_id');

        if(!$arr_booking_id)
        {
            return $this->redirect(['site/index']);
        }

        //get order token from booking id 

        $order = Order::find()
            ->innerJoin('{{%booking}}', '{{%booking}}.order_id = {{%order}}.order_id')
            ->where(['booking_id' => $arr_booking_id[0]])
            ->one();

        return $this->render('success', [
            'arr_booking_id' => $arr_booking_id,
            'order' => $order
        ]);
    }

    public function actionRequestSend()
    {
        $address = Yii::$app->session->get('address_id');
        
        if ($address) {

            $area_id = Yii::$app->session->get('delivery-location');
            $cart_delivery_date = date('Y-m-d', strtotime(Yii::$app->session->get('delivery-date')));
            $time_slot = Yii::$app->session->get('event_time');

            if(Yii::$app->user->isGuest)
            {
                $customer_id = 0;
                $customer_name = Yii::$app->session->get('customer_name');
                $customer_lastname = Yii::$app->session->get('customer_lastname');
                $customer_email = Yii::$app->session->get('customer_email');
                $customer_mobile = Yii::$app->session->get('customer_mobile');
            }
            else
            {
                $customer = Customer::findOne(Yii::$app->user->getId());

                $customer_id = $customer->customer_id;
                $customer_name = $customer->customer_name;
                $customer_lastname = $customer->customer_last_name;
                $customer_email = $customer->customer_email;
                $customer_mobile = $customer->customer_mobile;
            }

            //address

            $address_id = Yii::$app->session->get('address_id');

            $arr_booking_id = Booking::checkoutConfirm(
                    $area_id,
                    $cart_delivery_date,
                    $time_slot,
                    $customer_id,
                    $customer_name,
                    $customer_lastname,
                    $customer_email,
                    $customer_mobile,
                    $address_id
                );

            Yii::$app->session->set('arr_booking_id', $arr_booking_id);
            
            return $this->redirect(['success']);

        } else {
            return $this->redirect(Yii::$app->homeUrl);
        }
    }

    public function actionRemoveAddress(){
	    if (Yii::$app->request->isAjax) {
	        if (Yii::$app->request->post('id')) {
                if (CustomerAddressResponse::deleteAll('address_id=' . Yii::$app->request->post('id'))) {
                    if (CustomerAddress::deleteAll('address_id=' . Yii::$app->request->post('id'))) {
                        return 1;
                    }
                } else {
                    return 0;
                }
            }
        }
    }
}
