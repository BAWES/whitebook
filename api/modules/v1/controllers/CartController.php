<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\rest\Controller;
use \frontend\models\Wishlist;
/**
 * Auth controller provides the initial access token that is required for further requests
 * It initially authorizes via Http Basic Auth using a base64 encoded username and password
 */
class CartController extends Controller
{

//    public function behaviors()
//    {
//        $behaviors = parent::behaviors();
//
//        // remove authentication filter for cors to work
//        unset($behaviors['authenticator']);
//
//        // Allow XHR Requests from our different subdomains and dev machines
//        $behaviors['corsFilter'] = [
//            'class' => \yii\filters\Cors::className(),
//            'cors' => [
//                'Origin' => Yii::$app->params['allowedOrigins'],
//                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
//                'Access-Control-Request-Headers' => ['*'],
//                'Access-Control-Allow-Credentials' => null,
//                'Access-Control-Max-Age' => 86400,
//                'Access-Control-Expose-Headers' => [],
//            ],
//        ];
//
//        // Bearer Auth checks for Authorize: Bearer <Token> header to login the user
//        $behaviors['authenticator'] = [
//            'class' => \yii\filters\auth\HttpBearerAuth::className(),
//        ];
//        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
//        $behaviors['authenticator']['except'] = ['options'];
//
//        return $behaviors;
//    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        $actions = parent::actions();

        // Return Header explaining what options are available for next request
        $actions['options'] = [
            'class' => 'yii\rest\OptionsAction',
            // optional:
            'collectionOptions' => ['GET', 'POST', 'HEAD', 'OPTIONS'],
            'resourceOptions' => ['GET', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
        ];
        return $actions;
    }

    /**
     * @return array
     */
    public function actionListing()
    {
        return $this->listing();
    }


    /*
     * Method to list all item in cart
     */
    public function listing(){
        return CustomerCart::items();
    }


    /*
     * Add to WishList table method
     */
    public function actionWishlistAdd() {
        $customer_id = 182;
        $item_id = Yii::$app->request->getBodyParam("item_id");

        $exist = Wishlist::find()->where(['item_id'=>$item_id,'customer_id'=>$customer_id])->exists();
        if (!$exist) {
            $wish_modal = new Wishlist;
            $wish_modal->item_id = $item_id;
            $wish_modal->customer_id = $customer_id;
            $wish_modal->wish_status = 1;
            $wish_modal->save();
        }

        return $this->listing();
    }

    /*
     * Remove to WishList table method
     */
    public function actionWishlistRemove() {

        $customer_id = 182;
        $item_id = Yii::$app->request->getBodyParam("item_id");
        Wishlist::findOne(['item_id'=>$item_id,'customer_id'=>$customer_id])->delete();
        return $this->listing();
    }
}
