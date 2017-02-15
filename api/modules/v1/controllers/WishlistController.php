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
    public function actionWishlistList($offset,$category_id)
    {
        return $this->listing($offset,$category_id);
    }


    /*
     * Method to list all whishlist related with particular user
     */
    private function listing($offset = 0, $category){

        $customer_id = Yii::$app->user->getId();
        $limit = Yii::$app->params['limit'];
        $q = "SELECT `whitebook_vendor_item`.`item_id`,`whitebook_wishlist`.`wishlist_id`, ";
        $q .= "`whitebook_vendor_item`.`item_name`, `whitebook_vendor_item`.`item_price_per_unit`, ";
        $q .= "`whitebook_vendor`.`vendor_name`, `whitebook_image`.`image_path` FROM `whitebook_wishlist` ";
        $q .= "LEFT JOIN `whitebook_vendor_item` ON `whitebook_vendor_item`.item_id = `whitebook_wishlist`.item_id ";

        if ($category) {
            $q .= "LEFT JOIN `whitebook_vendor_item_to_category` ON `whitebook_vendor_item_to_category`.item_id = `whitebook_wishlist`.item_id ";
        }

        $q .= "LEFT JOIN `whitebook_image` ON `whitebook_vendor_item`.item_id = `whitebook_image`.item_id LEFT JOIN `whitebook_vendor` ON `whitebook_vendor_item`.vendor_id = `whitebook_vendor`.vendor_id  ";
        $q .= "WHERE (`whitebook_vendor_item`.`trash`='Default') AND (`whitebook_vendor_item`.`item_approved`='Yes') AND (`whitebook_vendor_item`.`item_status`='Active') AND ";
        $q .= "(`whitebook_wishlist`.`customer_id`=$customer_id) AND (`whitebook_vendor`.`trash`='Default') AND (`whitebook_vendor`.`approve_status`='Yes') ";

        if ($category) {
            $q .= " AND `whitebook_vendor_item_to_category`.`category_id` = '{$category}' ";
        }

        $q .= "AND (`whitebook_vendor_item`.`item_archived`='no') GROUP BY `item_id` ORDER BY `whitebook_vendor_item`.`item_name` LIMIT $limit OFFSET $offset";
        return \Yii::$app->db->createCommand($q)->queryAll();
    }

    /*
     * Add to WishList table method
     */
    public function actionWishlistAdd() {

        if (Yii::$app->user->getId()) {
            $customer_id = Yii::$app->user->getId();
            $product_id = Yii::$app->request->getBodyParam("product_id");

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
                        "id" => $wish_modal->wishlist_id,
                    ];
                } else {
                    return [
                        "operation" => "error",
                        "message" => "Error While Adding Item To Wishlist",
                        "error" => $wish_modal->errors,
                    ];
                }
            }
        } else {
            return [
                "operation" => "error",
                "message" => "Please login to add item to wishlist",
            ];
        }
    }

    /*
     * Remove to WishList table method
     */
    public function actionWishlistRemove($wishlist_id) {

        $customer_id = Yii::$app->user->getId();
        $item = Wishlist::findOne(['wishlist_id'=>$wishlist_id,'customer_id'=>$customer_id]);

        if ($item && $item->delete()) {
            return [
                "operation" => "success",
                "message" => "Item remove from wishlist successfully",
            ];
        } else {
            return [
                "operation" => "error",
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
