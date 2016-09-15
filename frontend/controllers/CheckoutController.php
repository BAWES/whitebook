<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\helpers\Url;
use common\models\CustomerCart;
use common\models\CustomerAddress;
use common\models\CustomerAddressResponse;
use common\models\Country;
use common\models\Location;
use common\models\PaymentGateway;
use frontend\models\Addresstype;


/**
 * Checkout controller.
 */
class CheckoutController extends BaseController
{
	public function init(){
        if(Yii::$app->user->isGuest) {
            $this->redirect(['site/index']);
        }
    }

	public function actionIndex()
	{
		//validate cart 
		foreach (CustomerCart::items() as $item) {
    		$errors = CustomerCart::validate_item([
    			'item_id' => $item['item_id'],
    			'delivery_date' => $item['cart_delivery_date'],
    			'area_id' => $item['area_id'],
    			'quantity' => $item['cart_quantity']
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
			$error = CustomerCart::validate_item([
    			'item_id' => $item['item_id'],
    			'delivery_date' => $item['cart_delivery_date'],
    			'area_id' => $item['area_id'],
    			'quantity' => $item['cart_quantity']
    		], true);

    		if($error) {
    			return null;
    		}
		}

		$customer_address_modal = new CustomerAddress();
        $addresstype = Addresstype::loadAddresstype();
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

		$customer_address = new CustomerAddress();
          
        if ($customer_address->load(Yii::$app->request->post())) {
          
            $customer_address->customer_id = Yii::$app->user->getId();

            $location = Location::findOne($customer_address->area_id);

            $customer_address->city_id = $location->city_id;
            $customer_address->country_id = $location->country_id;

            if ($customer_address->save()) {
              
                $address_id = $customer_address->address_id;

                //save customer address response 
                $questions = Yii::$app->request->post('question');

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
			$error = CustomerCart::validate_item([
    			'item_id' => $item['item_id'],
    			'delivery_date' => $item['cart_delivery_date'],
    			'area_id' => $item['area_id'],
    			'quantity' => $item['cart_quantity']
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
			$error = CustomerCart::validate_item([
    			'item_id' => $item['item_id'],
    			'delivery_date' => $item['cart_delivery_date'],
    			'area_id' => $item['area_id'],
    			'quantity' => $item['cart_quantity']
    		], true);

    		if($error) {
    			return null;
    		}
		}

		$payment_method_code = Yii::$app->session->get('payment_method');

        $gateway = PaymentGateway::find()->where(['code' => $payment_method_code, 'status' => 1])->one();

		if(Yii::$app->language == 'en') {
			$payment_method = $gateway->name;
		} else {
			$payment_method = $gateway->name_ar;
		}

		$address = Yii::$app->session->get('address');

		$items = CustomerCart::items();

        return $this->renderPartial('confirm', [
            'items' => $items,
            'payment_method' => $payment_method,
            'address' => $address,
            'pg_link' => Url::to(['payment/'.$payment_method_code.'/index'])
        ]);
	}

    public function actionSuccess() {

        //clear cart 
        CustomerCart::deleteAll('customer_id = '.Yii::$app->user->getId());

        //clear temp session data
        Yii::$app->session->remove('payment_method');
        Yii::$app->session->remove('address');

        $order_id = Yii::$app->session->get('order_id');

        return $this->render('success', [
            'order_id' => $order_id,
            'order_page' => Url::to(['orders/index'])
        ]);
    }
}
