<?php

namespace frontend\controllers;

use Yii;
use common\models\Package;
use frontend\models\Users;

/**
* Packages controller.
*/
class PackagesController extends BaseController
{	
	public function actionIndex()
    {
        \Yii::$app->view->title = Yii::$app->params['SITE_NAME'] .' | Event Packages';
        \Yii::$app->view->registerMetaTag(['name' => 'description', 'content' => 'Event Packages in The White Book - Event Planning Platform']);
        \Yii::$app->view->registerMetaTag(['name' => 'keywords', 'content' => 'Event Packages in The White Book - Event Planning Platform']);

    	$packages = Package::find()
    		->where(['status' => 1])
    		->all();

    	return $this->render('index', [
            'packages' => $packages,
        ]);
    }

    public function actionDetail($slug) 
    {
        $package = Package::findOne(['package_slug' => $slug]);

        if (!$package) {
            throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
        }

        \Yii::$app->view->title = Yii::$app->params['SITE_NAME'] . ' | ' . $package->package_name;
        \Yii::$app->view->registerMetaTag(['name' => 'description', 'content' => $package->package_description]);
        \Yii::$app->view->registerMetaTag(['name' => 'keywords', 'content' => $package->package_description]);

        $categories = \frontend\models\Category::find()
            ->where(['category_level' => 0, 'category_allow_sale' =>'Yes', 'trash' =>'Default'])
            ->orderBy(new \yii\db\Expression('FIELD (category_name, "Venues", "Invitations", "Food & Beverages", "Decor", "Supplies", "Entertainment", "Services", "Others", "Gift favors")'))
            ->all();

        if(Yii::$app->user->isGuest) {
            $wishlist_item_ids = [];
        } else {
            $wishlist = Users::get_customer_wishlist_details(Yii::$app->user->getId());
            $wishlist_item_ids = \yii\helpers\ArrayHelper::getColumn($wishlist, 'item_id'); 
        }

        return $this->render('detail', [
            'package' => $package,
            'categories' => $categories,
            'wishlist_item_ids' => $wishlist_item_ids
        ]);
    }
}