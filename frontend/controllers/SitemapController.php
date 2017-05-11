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
            ->defaultCategories()
            ->nonEmptySlug()
            ->all();

        //items
        $items = VendorItem::find()
            ->defaultItems()
            ->active()
            ->approved()
            ->all();

        //vendor
        $vendors = Vendor::find()
            ->defaultVendor()
            ->approved()
            ->active()
            ->all();

        header("Content-type: text/xml");

        \Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;

    	return $this->renderPartial('index', [
                'categories' => $categories,
                'items' => $items,
                'vendors' => $vendors
            ]);
    }
}
