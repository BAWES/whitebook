<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use common\models\CustomerCart;
use common\models\CustomerAddress;
use common\models\CustomerAddressResponse;
use common\models\Country;
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
        return $this->render('index');
	}

	//Display form to select delivery address 
	public function actionAddress(){
		
		$items = CustomerCart::items();

		$customer_address_modal = new CustomerAddress();
        $addresstype = Addresstype::loadAddress();
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
		return $this->renderPartial('payment');
	}

	//validate payment method
	public function actionSavePayment(){
		
		$payment_method = Yii::$app->request->post('payment_method');

		Yii::$app->session->set('payment_method', $payment_method);
	}

	//Display all data for order to get confirm 
	public function actionConfirm() {

		$payment_method_code = Yii::$app->session->get('payment_method');

		if($payment_method_code == 'cod') {
			$payment_method = Yii::t('frontend', 'Cash On Delivery');
		} else {
			$payment_method = 'undefined!';
		}

		$address = Yii::$app->session->get('address');

		$items = CustomerCart::items();

        return $this->renderPartial('confirm', [
            'items' => $items,
            'payment_method' => $payment_method,
            'address' => $address
        ]);
	}
}
