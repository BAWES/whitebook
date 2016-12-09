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

    public function actionIndex($category_id = '')
    {
        \Yii::$app->view->title = Yii::$app->params['SITE_NAME'].' | Things I Like';
        \Yii::$app->view->registerMetaTag(['name' => 'description', 'content' => Yii::$app->params['META_DESCRIPTION']]);
        \Yii::$app->view->registerMetaTag(['name' => 'keywords', 'content' => Yii::$app->params['META_KEYWORD']]);

        if (Yii::$app->user->isGuest && Yii::$app->request->isAjax) 
        {
            throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
        }
        elseif (Yii::$app->user->isGuest) 
        {
            Yii::$app->session->set('show_login_modal', 1);//to display login modal
            return $this->goHome();
        }

        $customer_id = Yii::$app->user->getId();

        $price = $vendor = $avail_sale = $theme = '';
        $avail_sale = $vendor = $theme = '';

        $customer_wishlist = Users::get_customer_wishlist($customer_id, $category_id, $price, $vendor, $avail_sale);

        $categories = \frontend\models\Category::find()
            ->where(['category_level' => 0, 'category_allow_sale' =>'Yes', 'trash' =>'Default'])
            ->orderBy(new \yii\db\Expression('FIELD (category_name, "Venues", "Invitations", "Food & Beverages", "Decor", "Supplies", "Entertainment", "Services", "Others", "Gift favors")'))
            ->all();

        if (Yii::$app->request->isAjax) {
            return $this->renderPartial('_items', [
                'items' => $customer_wishlist, 
            ]); 
        }

        return $this->render('index', [
            'customer_wishlist' => $customer_wishlist,
            'categories' => $categories
        ]);
    }
                    

    public function actionDelete($id)
    {
        \frontend\models\Wishlist::deleteAll(['item_id'=>$id,'customer_id'=>Yii::$app->user->identity->customer_id]);
    }
}