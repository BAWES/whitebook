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
use frontend\models\AddressType;


/**
 * Checkout controller.
 */
class CheckoutController extends BaseController
{
	public function init(){
        if(Yii::$app->user->isGuest) {
            $this->redirect(['/site/index']);
        }
    }

	public function actionIndex()
	{
		\Yii::$app->view->title = Yii::$app->params['SITE_NAME'].' | Checkout';
		\Yii::$app->view->registerMetaTag(['name' => 'description', 'content' => Yii::$app->params['META_DESCRIPTION']]);
		\Yii::$app->view->registerMetaTag(['name' => 'keywords', 'content' => Yii::$app->params['META_KEYWORD']]);

		//validate cart
		foreach (CustomerCart::items() as $item) {
            
            $menu_items = CustomerCartMenuItem::findAll(['cart_id' => $item['cart_id']]);

    		$errors = CustomerCart::validate_item([
    			'item_id' => $item['item_id'],
                'time_slot' => $item['time_slot'],
                'delivery_date' => $item['cart_delivery_date'],
                'area_id' => $item['area_id'],
    			'quantity' => $item['cart_quantity'],
                'menu_item' => ArrayHelper::map($menu_items, 'menu_item_id', 'quantity')
    		], true);

    		if($errors) { 
    			$this->redirect(['cart/index']);
    		}
    	}

        return $this->render('index');
	}

	//Display form to select delivery address 
	public function actionAddress(){
		
		$items = CustomerCart::items();

		foreach ($items as $item) {
            
            $menu_items = CustomerCartMenuItem::findAll(['cart_id' => $item['cart_id']]);

			$error = CustomerCart::validate_item([
    			'item_id' => $item['item_id'],
                'time_slot' => $item['time_slot'],
    			'delivery_date' => $item['cart_delivery_date'],
                'area_id' => $item['area_id'],
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

        return $this->renderPartial('address', [
            'items' => $items,
            'customer_address_modal' => $customer_address_modal,
            'addresstype' => $addresstype,
            'country' => $country
        ]);
	}

	public function actionAddAddress() {

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
            ->where(['cart_id' => Yii::$app->request->post()])
            ->one();

        $customer_address->area_id = $cart_item->area_id;

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

	//save address 
	public function actionSaveAddress() {

		$json = array();

		$addresses = Yii::$app->request->post('address');

		foreach ($addresses as $key => $value) {
			if(empty($value)) {
				$json['errors'][$key] = 'Please select address for Item - '.$value;
			}
		}
		
		Yii::$app->session->set('address', $addresses);

		Yii::$app->response->format = Response::FORMAT_JSON;

        return $json;
	}

	//Display form to select payment method 
	public function actionPayment(){

		$items = CustomerCart::items();

		foreach ($items as $item) {
            
            $menu_items = CustomerCartMenuItem::findAll(['cart_id' => $item['cart_id']]);

			$error = CustomerCart::validate_item([
    			'item_id' => $item['item_id'],
                'working_id' => $item['working_id'],
    			'delivery_date' => $item['cart_delivery_date'],
                'working_end_time' => $item['working_end_time'],
    			'area_id' => $item['area_id'],
    			'quantity' => $item['cart_quantity'],
                'menu_item' => ArrayHelper::map($menu_items, 'menu_item_id', 'quantity')
    		], true);

    		if($error) {
    			return null;
    		}
		}

        $payment_gateway = PaymentGateway::find()
            ->where(['status' => 1])
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

            $menu_items = CustomerCartMenuItem::findAll(['cart_id' => $item['cart_id']]);

			$error = CustomerCart::validate_item([
    			'item_id' => $item['item_id'],
                'time_slot' => $item['time_slot'],
    			'delivery_date' => $item['cart_delivery_date'],
                'area_id' => $item['area_id'],
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
            'items' => $items,
            'address' => $address,
            'pg_link' => Url::to(['checkout/request-send'])
        ]);
	}

    public function actionSuccess() {

        $customer_id = Yii::$app->user->getId();

        if(!$customer_id) {
            $this->redirect(['site/index']);
        }

        //clear cart 
        CustomerCart::deleteAll('customer_id = "'.Yii::$app->user->getId().'"');

        //clear temp session data
        Yii::$app->session->remove('payment_method');
        Yii::$app->session->remove('address');

        $arr_booking_id = Yii::$app->session->get('arr_booking_id');

        return $this->render('success', [
            'arr_booking_id' => $arr_booking_id,
            'booking_page' => Url::to(['booking/index'])
        ]);
    }

    public function actionRequestSend()
    {
        $address = Yii::$app->session->get('address',false);
        
        if ($address) {
            
            $arr_booking_id = Booking::checkoutConfirm();

            Yii::$app->session->set('arr_booking_id', $arr_booking_id);
            
            return $this->redirect(['success']);

        } else {
            return $this->redirect(Yii::$app->homeUrl);
        }
    }
}
