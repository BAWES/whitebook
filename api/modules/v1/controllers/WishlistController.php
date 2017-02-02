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
    public function actionWishlistList($offset)
    {
        return $this->listing($offset);
    }


    /*
     * Method to list all whishlist related with particular user
     */
    private function listing($offset = 0){

        $customer_id = Yii::$app->user->getId();
        $offset = 0;
        $limit = Yii::$app->params['limit'];
        $q = "SELECT  `whitebook_vendor_item`.`item_id`, `whitebook_vendor_item`.`item_id`, ";
        $q .= "`whitebook_vendor_item`.`item_name`, `whitebook_vendor_item`.`item_name_ar`, `whitebook_vendor_item`.`item_price_per_unit`, ";
        $q .= "`whitebook_vendor`.`vendor_name`, `whitebook_vendor`.`vendor_name_ar`, `whitebook_image`.`image_path` FROM ";
        $q .= "`whitebook_wishlist` LEFT JOIN `whitebook_vendor_item` ON `whitebook_vendor_item`.item_id = `whitebook_wishlist`.item_id ";
        $q .= "LEFT JOIN `whitebook_image` ON `whitebook_vendor_item`.item_id = `whitebook_image`.item_id LEFT JOIN `whitebook_vendor` ";
        $q .= "ON `whitebook_vendor_item`.vendor_id = `whitebook_vendor`.vendor_id WHERE (`whitebook_vendor_item`.`trash`='Default') ";
        $q .= "AND (`whitebook_vendor_item`.`item_approved`='Yes') AND (`whitebook_vendor_item`.`item_status`='Active') AND ";
        $q .= "(`whitebook_wishlist`.`customer_id`=$customer_id) AND (`whitebook_vendor`.`trash`='Default') AND (`whitebook_vendor`.`approve_status`='Yes') ";
        $q .= "AND (`whitebook_vendor_item`.`item_archived`='no') GROUP BY `item_id` ORDER BY `whitebook_vendor_item`.`item_name` LIMIT $limit OFFSET $offset";
        return \Yii::$app->db->createCommand($q)->queryAll();
    }

    /*
     * Add to WishList table method
     */
    public function actionWishlistAdd() {
        $customer_id = Yii::$app->user->getId();
        $item_id = Yii::$app->request->getBodyParam("item_id");

        $exist = Wishlist::find()->where(['item_id'=>$item_id,'customer_id'=>$customer_id])->exists();
        if (!$exist) {
            $wish_modal = new Wishlist;
            $wish_modal->item_id = $item_id;
            $wish_modal->customer_id = $customer_id;
            $wish_modal->wish_status = 1;
            if ($wish_modal->save()) {
                return [
                    "operation" => "success",
                    "message" => "Item Added Successfully",
                ];
            } else {
                return [
                    "operation" => "error",
                    "message" => "Error While Adding Item To Wishlist",
                ];
            }
        }
    }

    /*
     * Remove to WishList table method
     */
    public function actionWishlistRemove() {

        $customer_id = Yii::$app->user->getId();
        $item_id = Yii::$app->request->getBodyParam("item_id");
        $item = Wishlist::findOne(['item_id'=>$item_id,'customer_id'=>$customer_id]);

        if ($item && $item->delete()) {
            return [
                "operation" => "success",
                "message" => "Item Deleted Successfully",
            ];
        } else {
            return [
                "operation" => "error",
                "message" => "Error While Deleting Item From Wishlist",
            ];
        }

        return $this->listing();
    }
}
