<?php

namespace frontend\controllers;

use yii;
use yii\web\Controller;
use common\models\Category;
use common\models\VendorItem;
use common\models\Vendor;
use common\models\Cms;

/**
 * Sitemap controller.
 */
class SitemapController extends Controller
{
    public function actionIndex()
    {
    	//category 
    	$categories = Category::find()
            ->where(['trash' => 'Default'])
            ->andWhere(['!=', 'slug', ''])
            ->all();

    	//items  
    	$items = VendorItem::findAll([
    			'item_approved' => 'Yes',
    			'item_status' => 'Active',
    			'trash' => 'Default'
    		]);

    	//vendor 
    	$vendors = Vendor::findAll([
    			'vendor_status' => 'Active',
    			'approve_status' => 'Yes',
    			'trash' => 'Default'
    		]);

        header("Content-type: text/xml");

        \Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;

    	return $this->renderPartial('index', [
                'categories' => $categories,
                'items' => $items,
                'vendors' => $vendors
            ]);
    }
}
