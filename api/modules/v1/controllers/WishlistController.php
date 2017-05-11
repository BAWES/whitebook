<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\rest\Controller;
use \frontend\models\Wishlist;
use yii\db\Query;


/**
 * Auth controller provides the initial access token that is required for further requests
 * It initially authorizes via Http Basic Auth using a base64 encoded username and password
 */
class WishlistController extends Controller
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // remove authentication filter for cors to work
        unset($behaviors['authenticator']);

        // Allow XHR Requests from our different subdomains and dev machines
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className(),
            'cors' => [
                'Origin' => Yii::$app->params['allowedOrigins'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                'Access-Control-Request-Headers' => ['*'],
                'Access-Control-Allow-Credentials' => null,
                'Access-Control-Max-Age' => 86400,
                'Access-Control-Expose-Headers' => [],
            ],
        ];

        // Bearer Auth checks for Authorize: Bearer <Token> header to login the user
        $behaviors['authenticator'] = [
            'class' => \yii\filters\auth\HttpBearerAuth::className(),
        ];
        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
        $behaviors['authenticator']['except'] = ['options'];

        return $behaviors;
    }

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
    public function actionWishlistList($offset = 0,$category_id = '')
    {
        $customer_id = Yii::$app->user->getId();

        $limit = Yii::$app->params['limit'];
        $price = $vendor = $avail_sale = $theme = '';
        $avail_sale = $vendor = $theme = '';
        $category_id = ($category_id) ? $category_id : '';
        return \frontend\models\Users::get_customer_wishlist($customer_id, $category_id, $price, $vendor, $avail_sale,$limit,$offset);
    }

    /*
     * Add to WishList table method
     */
    public function actionWishlistAdd()
    {
        if (Yii::$app->user->getId()) {
            $customer_id = Yii::$app->user->getId();
            $product_id = Yii::$app->request->getBodyParam("item_id");

            if (empty($product_id) || !isset($product_id)) {
                return [
                    "operation" => "error",
                    "code" => "0",
                    'message' => 'Invalid item ID'
                ];
            }

            $exist = Wishlist::find()->where(['item_id' => $product_id, 'customer_id' => $customer_id])->exists();
            if (!$exist) {
                $wish_modal = new Wishlist;
                $wish_modal->item_id = $product_id;
                $wish_modal->customer_id = $customer_id;
                $wish_modal->wish_status = 1;
                if ($wish_modal->save()) {
                    return [
                        "operation" => "success",
                        "message" => "Item added to wishlist Successfully",
                        "code" => "1",
                        "id" => $wish_modal->wishlist_id,
                    ];
                } else {
                    return [
                        "operation" => "error",
                        "message" => "Error While Adding Item To Wishlist",
                        "code" => "0",
                        "error" => $wish_modal->errors,
                    ];
                }
            } else {
                return [
                    "operation" => "error",
                    "message" => "Item Already Added",
                    "code" => "0",
                ];
            }
        } else {
            return [
                "operation" => "error",
                "message" => "Please login to add item to wishlist",
                "code" => "0",
            ];
        }
    }

    /*
     * Remove to WishList table method
     */
    public function actionWishlistRemove() {

        $wishlist_id = Yii::$app->request->get("wishlist_id");

        if (empty($wishlist_id) || !isset($wishlist_id)) {
            return [
                "operation" => "error",
                "code" => "0",
                'message' => 'Invalid Wishlist ID'
            ];
        }

        $customer_id = Yii::$app->user->getId();
        $item = Wishlist::findOne(['wishlist_id'=>$wishlist_id,'customer_id'=>$customer_id]);

        if ($item && $item->delete()) {
            return [
                "operation" => "success",
                "code" => "1",
                "message" => "Item remove from wishlist successfully",
            ];
        } else {
            return [
                "operation" => "error",
                "code" => "0",
                "message" => "Error While Deleting Item From Wishlist",
            ];
        }
    }

    /**
     * check is item is in wishlist or not
     */

    public function actionIsItemExist($product_id) {
        if (Yii::$app->user->getId()) {
            $exist = Wishlist::find()->where(['item_id' => $product_id, 'customer_id' => Yii::$app->user->getId()])->one();
            return ($exist) ? $exist->wishlist_id : 0;
        }
    }
}
