<?php

namespace frontend\controllers;

use Yii;
use yii\filters\VerbFilter;
use frontend\models\Users;
use frontend\models\Website;

/**
 * ThingsILikeController implements the CRUD actions for Wishlist model.
 */
class ThingsILikeController extends BaseController
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
            ],
        ];
    }

    /**
     * Lists all Wishlist models.
     *
     * @return mixed
     */

    public function actionIndex()
    {
        \Yii::$app->view->title = Yii::$app->params['SITE_NAME'].' | Things I Like';
        \Yii::$app->view->registerMetaTag(['name' => 'description', 'content' => Yii::$app->params['META_DESCRIPTION']]);
        \Yii::$app->view->registerMetaTag(['name' => 'keywords', 'content' => Yii::$app->params['META_KEYWORD']]);

        if (Yii::$app->user->isGuest) {
            Yii::$app->session->set('show_login_modal', 1);//to display login modal
            return $this->goHome();
        }

        $customer_id = Yii::$app->user->getId();

        $model = new Users();
        $wish_limit = 6;
        $offset = 0;

        $price = $vendor = $avail_sale = $theme = '';
        $avail_sale = $category_id = $vendor = $theme = '';

        $customer_wishlist = $model->get_customer_wishlist(
            $customer_id, $wish_limit, $offset, $category_id, $price, $vendor, $avail_sale, $theme);

        $customer_wishlist_count = $model->get_customer_wishlist_count(
            $customer_id, $category_id, $price, $vendor, $avail_sale, $theme);

        $website_model = new Website();
        $event_type = $website_model->get_event_types();
        $provider = new \yii\data\ArrayDataProvider([
            'allModels' => $customer_wishlist,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
        echo "<pre>";
        print_r($provider->getModels());
        exit;
        return $this->render('index', [
            'customer_wishlist' => $customer_wishlist,
            'customer_wishlist_count' => $customer_wishlist_count,
            'event_type' => $event_type,
            'provider' => $provider,
        ]);
    }

    public function actionDelete($id)
    {
        \frontend\models\Wishlist::deleteAll(['item_id'=>$id,'customer_id'=>Yii::$app->user->identity->customer_id]);

        Yii::$app->session->setFlash('success','Item removed from your wishlist');
        $this->redirect(['/things-i-like']);
    }
}